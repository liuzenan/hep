<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard extends CI_Controller {

	public function index(){
		$this->load->helper('url');
		$data['active'] = 3;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['currentTab'] = "student";
		$this->loadPage($data);
	}

	public function student(){
		$this->load->helper('url');
		$data['active'] = 3;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['currentTab'] = "student";
		$this->loadPage($data);
	}

	public function house(){
		$this->load->helper('url');
		$data['active'] = 3;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['currentTab'] = "house";
		$this->loadPage($data);
	}

	public function staff(){
		$this->load->helper('url');
		$data['active'] = 3;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['currentTab'] = "staff";
		$this->loadPage($data);
	}

	private function loadPage($data){
		$this->load->view('templates/header', $data);
		$this->load->view('leaderboard', $data);
		$this->load->view('templates/footer');
	}

	private function getTopStudent(){
		
	}


	private function getTopHouse(){

	}

	private function getTopStaff(){

	}



}