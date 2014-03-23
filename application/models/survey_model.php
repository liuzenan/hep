<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Survey_model extends My_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getOpenSurveys($user_id) {
        $today = date('Y-m-d');
        $query = $this->db
            ->select('*')
            ->from('survey')
            ->join('usersurvey', "survey.id = usersurvey.survey_id AND usersurvey.user_id = $user_id", 'left')
            ->where('published', 1)
            ->where('open_date <= ', $today)
            ->where('close_date >', $today)
            ->get();
        return $query->result();
    }

    function getClosedSurveys($user_id) {
        $today = date('Y-m-d');
        $query = $this->db
            ->select('*')
            ->from('survey')
            ->join('usersurvey', "survey.id = usersurvey.survey_id AND usersurvey.user_id = $user_id", 'left')
            ->where('published', 0)
            ->or_where('open_date > ', $today)
            ->or_where('close_date <', $today)
            ->get();
        return $query->result();
    }

    function loadSurvey($survey_id, $user_id) {
        $survey = $this->db->get_where('survey', array('id' => $survey_id));
        $questions = $this->db
            ->select('*')
            ->from('surveyquestions')
            ->where('survey_id', $survey_id)
            ->order_by('weight')
            ->order_by('id')
            ->get();
        $response = $this->db->get_where('usersurvey', array('user_id' => $user_id, 'survey_id' => $survey_id));
        return array('survey' => $survey, 'questions' => $questions, 'response' => $response);
    }

    function checkAccess($survey_id, $user_id, $is_staff) {
        $survey = $this->db->get_where('survey', array('id' => $survey_id));
        $response = $this->db->get_where('usersurvey', array('user_id' => $user_id, 'survey_id' => $survey_id));
        
        if ($survey->num_rows() == 0) {
            // no such survey
            return array('result' => false, 'message' => 'Invalid survey ID');
        } else if ($is_staff) {
            // staff override
            return array('result' => true);
        } else if ($response->num_rows() > 0 && $response->row()->completed == 1) {
            // completed survey
            return array('result' => false, 'message' => 'You have completed this survey');
        } else {
            $survey = $survey->row();
            // not opened or already closed
            if ($survey->open_date > date('Y-m-d') || $survey->open_date < date('Y-m-d') || $survey->published != 1) {
                return array('result' => false, 'message' => 'You are not authorised to view this survey');
            } else {
                return array('result' => true);
            }
        }
    }

    function incompleteStudents($survey_id) {
        $query = $this->db->select('*')
            ->from('user')
            ->join('usersurvey', 'user.id = usersurvey.user_id AND usersurvey.survey_id = ' . $survey_id, 'left')
            ->where('user.staff', 0)
            ->where('user.phantom', 0)
            ->where('user.email IS NOT NULL')
            ->where('(usersurvey.completed IS NULL OR usersurvey.completed = 0)')
            ->get();
        return $query->result();
    }

    function getSurveyStats($survey_id) {
        $total = $this->db->select('COUNT(*) as count')
            ->from('user')
            ->where('email is not null')
            ->where('staff', 0)
            ->where('phantom', 0)
            ->get()->row()->count;
        $incomplete = count($this->incompleteStudents($survey_id));
        $completed = $total - $incomplete;
        return array('incomplete' => $incomplete, 'completed' => $completed);
    }

    function getResponseSummary($survey_id) {
        $questions = $this->db->select('*')
            ->from('surveyquestions')
            ->where('survey_id', $survey_id)
            ->order_by('weight')
            ->get();

        $responses = $this->db->select('*')
            ->from('usersurvey')
            ->join('user', 'user.id = usersurvey.user_id')
            ->where('user.staff', 0)
            ->where('user.phantom', 0)
            ->where('user.email IS NOT NULL')
            ->where('usersurvey.survey_id', $survey_id)
            ->where('completed', 1)
            ->get();

        $tallied = array();

        foreach ($responses->result() as $response) {
            foreach (json_decode($response->response, TRUE) as $qid => $ans) {
                // multiple-select checkboxes are stored as an array, flatten it 
                if (is_array($ans)) {
                    foreach ($ans as $option) {
                        if (!isset($tallied[$qid][$option])) {
                            $tallied[$qid][$option] = 1;
                        } else {
                            $tallied[$qid][$option] += 1;
                        }
                    }
                } else {
                    if (!isset($tallied[$qid][$ans])) {
                        $tallied[$qid][$ans] = 1;
                    } else {
                        $tallied[$qid][$ans] += 1;
                    }
                }
            }
        }

        return array($questions->result_array(), $tallied);
    }

}