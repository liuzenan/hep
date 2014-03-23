<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surveyresult extends Admin_Controller
{

    public function index()
    {
        redirect(base_url() . 'survey');
    }

    public function view($survey_id) {

        $this->load->model('Survey_model', 'survey');
        $data = $this->survey->getSurveyStats($survey_id);
        list($data['questions'], $data['tallied']) = $this->survey->getResponseSummary($survey_id);
        $data['survey_id'] = $survey_id;
        $data['survey'] = $this->survey->loadSurvey($survey_id, -1)['survey']->row();
        $data['currentTab'] = 'view';
        $this->loadPage($data);
    }

    public function incomplete($survey_id) {
        $this->load->model('Survey_model', 'survey');
        $data['students'] = $this->survey->incompleteStudents($survey_id);
        $data['survey'] = $this->survey->loadSurvey($survey_id, -1)['survey']->row();
        $data['survey_id'] = $survey_id;
        $data['currentTab'] = 'incomplete';
        $this->loadPage($data);
    }

    private function loadPage($data)
    {
        $data['active'] = 'surveyresult';
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('survey_results', $data);
        $this->load->view('templates/footer');
    }

}