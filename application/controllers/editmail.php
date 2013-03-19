<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editmail extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}

	public function index() {

		if($this->session->userdata('isadmin')){
			$this->load->helper('file');
			$data["emailmsg"] = read_file('emailmsg.txt');
			$this->loadPage($data);
		} else {
			redirect(base_url() . "home");
		}
	}

	public function updateEmailMessage(){
		$emailMsg = $this->input->post("msg");
		$emailMsg = htmlspecialchars($emailMsg);
		$this->load->helper('file');
		if (!write_file('emailmsg.txt', $emailMsg)) {
			# code...
			$msg['success'] = false;
			
		} else {
			$msg['success'] = true;
			
		}
		echo json_encode($msg);
	}

	private function loadPage($data){
		$data['active'] = 'mail';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('mail', $data);
		$this->load->view('templates/footer');
	}

}