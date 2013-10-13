<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Staticpages extends My_Controller
{
    public function index()
    {
        $this->faq();
    }

    public function faq()
    {
        $data['active'] = "faq";
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('faq', $data);
        $this->load->view('templates/footer');
    }

    public function rules()
    {
        $data['active'] = "rules";
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('rules', $data);
        $this->load->view('templates/footer');
    }
}