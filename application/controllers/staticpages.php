<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staticpages extends CI_Controller {
	public function index(){
		$this->faq();
	}

	public function faq() {
		$data['active'] = "faq";
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));

		$this->load->view('templates/header', $data);
		$this->load->view('faq', $data);
		$this->load->view('templates/footer');
	}

	public function rules() {
		$data['active'] = "rules";
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));
		$this->load->view('templates/header', $data);
		$this->load->view('rules', $data);
		$this->load->view('templates/footer');
	}
}