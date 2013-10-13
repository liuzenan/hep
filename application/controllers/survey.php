<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Survey extends My_Controller
{

    public function index()
    {
        $sql = "SELECT * FROM survey WHERE userid = " . $this->uid;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            # code..
            $data["submitted"] = 1;
            $data["completed_survey"] = $query->row();
        } else {
            $data["submitted"] = 0;
        }

        $completeduids = $this->User_model->loadStudentDidntCompleteSurvey();
        $data['numCompleted'] = count($completeduids);
        $this->loadPage($data);
    }

    public function submitSurvey()
    {
        $q = array();
        $insertString = "";
        for ($i = 0; $i < 15; $i++) {
            # code...
            $q[$i] = $this->input->post("q$i");
            $insertString = $insertString . $this->db->escape($q[$i]) . ", ";
        }

        $q10yes = $this->input->post("q10yes");
        $insertString = $insertString . $this->db->escape($q10yes);


        $sql = "REPLACE INTO survey VALUES( " . $this->uid . ", " . $insertString . ")";
        $this->db->query($sql);


        $this->index();

    }

    public function surveyResult($user_id)
    {
        if ($this->session->userdata('isadmin') == 1) {
            # code...
            $sql = "SELECT * FROM survey WHERE userid = " . $user_id;
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                foreach ($query->row() as $key => $value) {
                    # code...
                    $data[$key] = $value;
                }
                $this->loadSurveyResultPage($data);
            }
        }
    }

    private function loadSurveyResultPage($data)
    {
        $data['active'] = "surveyResult";
        $data['displayName'] = $this->session->userdata('name');
        $data['avatar'] = $this->session->userdata('avatar');
        $data['isAdmin'] = $this->session->userdata('isadmin');
        $data['isLeader'] = $this->session->userdata('isleader');
        $data['isTutor'] = $this->session->userdata('isTutor');

        $data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));

        $this->load->view('templates/header', $data);
        $this->load->view('templates/surveyresult', $data);
        $this->load->view('templates/footer');
    }

    private function loadPage($data)
    {
        $data['active'] = "survey";
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('survey', $data);
        $this->load->view('templates/footer');
    }

}