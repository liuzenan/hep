<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Survey extends My_Controller
{

    public function index()
    {
        $this->load->model('Survey_model', 'survey');
        $data['surveys'] = $this->survey->getOpenSurveys($this->uid);

        $isTutor =  $this->session->userdata('isTutor');
        $isAdmin =  $this->session->userdata('isAdmin');
        if((!empty($isTutor) && $isTutor==1)  || (!empty($isAdmin) && $isAdmin==1)) {
            $data['closedSurveys'] = $this->survey->getClosedSurveys($this->uid);
        } else {
            $data['closedSurveys'] = array();
        }

        $this->loadPage($data, 'survey_list');
    }

    public function old() {
        $this->loadPage(array(), 'survey');

    }

    function authorise($survey_id) {
        $this->load->model('Survey_model', 'survey');

        $isTutor =  $this->session->userdata('isTutor');
        $isAdmin =  $this->session->userdata('isAdmin');
        if((!empty($isTutor) && $isTutor==1)  || (!empty($isAdmin) && $isAdmin==1)) { 
            $isStaff = true;
        } else {
            $isStaff = false;
        }

        $result = $this->survey->checkAccess($survey_id, $this->session->userdata('user_Id'), $isStaff);
        if (! $result['result']) {
             $this->session->set_flashdata('survey-error', $result['message']);
             redirect(base_url() . 'survey');
        } else {
            return true;
        }
     
    }

    public function id($survey_id) {
        $this->authorise($survey_id);

        $this->load->model('Survey_model', 'survey');
        $query = $this->survey->loadSurvey($survey_id, $this->session->userdata('user_id'));
        $surveyQuery = $query['survey'];
        $questionsQuery = $query['questions'];

        $data['survey'] = $surveyQuery->row();
        $data['questions'] = $questionsQuery->result();
        
        if ($query['response']->num_rows()) {
            $data['response'] = json_decode($query['response']->row()->response, TRUE);
        }
        $this->loadPage($data, 'survey_form');
    }

    public function submitSurvey($survey_id)
    {
        $this->authorise($survey_id);
        
        $uid = $this->session->userdata('user_id');
        $answer = $this->input->post();
        if (empty($answer)) {
            redirect(base_url() . 'survey');
        }
        $btn = $answer['submitBtn'];
        unset($answer['submitBtn']);

        $query = $this->db->get_where('usersurvey', 
            array('user_id' => $uid, 'survey_id' => $survey_id));
        $rows = $query->num_rows();

        $this->db->set('survey_id', $survey_id);
        $this->db->set('user_id', $this->uid);
        $this->db->set('response', json_encode($answer));
        $this->db->set('completed', $btn == 'draft' ? 0 : 1);
        if ($rows) {
            $this->db->where('survey_id', $survey_id);
            $this->db->where('user_id', $uid);
            $this->db->update('usersurvey');
        } else {
            $this->db->insert('usersurvey');
        }

        if ($btn == 'draft') {
            $this->session->set_flashdata('survey-message', 'Draft has been saved!');
            redirect(base_url() . 'survey/id/'.$survey_id);
        } else {
            $this->session->set_flashdata('survey-message', 'Your response has been recorded!');
            redirect(base_url() . 'survey');
        }

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


        $this->load->view('templates/header', $data);
        $this->load->view('templates/surveyresult', $data);
        $this->load->view('templates/footer');
    }

    private function loadPage($data, $page)
    {
        $data['active'] = 'survey';
        parent::loadUser($data);
        $data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));
        $this->load->helper('date');    
        $this->load->helper('form');
  
        $this->load->view('templates/header', $data);
        $this->load->view($page, $data);
        $this->load->view('templates/footer');
    }

}