<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Achievements extends CI_Controller {

	public function index(){
		$this->load->helper('url');
		$data['active'] = 3;
		$data['displayName'] = $this->session->userdata('username');
		$data['avatar'] = $this->session->userdata('avatar');
		$this->load->view('templates/header', $data);
		$this->load->view('achievements', $data);
		$this->load->view('templates/footer');
	}

}