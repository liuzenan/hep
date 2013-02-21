<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Masquerade extends CI_Controller {
	
	private $uid;
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}


	public function index(){
		$data = array();		
		$this->loadPage($data);
	}

	public function suggestions()
	{
		// Search term from jQuery
		$term = $this->input->post('term');

		// Do mysql query or what ever
		$users = $this->User_model->searchUser($term);

		$arr = array();
		foreach($users as $u) {
			$arr[] = $u->first_name." ".$u->last_name;
		}

		// Return data
		echo json_encode($arr);
	}
	
	public function switchUser() {
		// Search term from jQuery
		$term = $this->input->post('term');
		//echo $term;
		// Do mysql query or what ever
		$users = $this->User_model->searchUser($term);
		$row;
		foreach($users as $u) {
			$row = $u;
			break;
		}
		$masquerade_id = $this->session->userdata('user_id');
		$userdata = array(
			'user_id' => $row->id,
			'fibit_id' => $row->fitbit_id,
			'oauth_secret' => $row->oauth_secret,
			'oauth_token' => $row->oauth_token,
			'username' => $row->username,
			'avatar' => $row->profile_pic,
			'isleader'=> $row->leader,
			'isTutor' => $row->staff,
			'isAdmin' => 1,
			'name' => $row->first_name
			);

		$origin_id = $this->session->userdata('masquerade_id');
		if(empty($origin_id)) {
			$userdata['masquerade_id'] = $masquerade_id;
		} 

		$this->session->set_userdata($userdata);
		echo json_encode(array("success" => true));
	}

	public function switchBack() {
		$row = $this->User_model->loadUser($this->session->userdata('masquerade_id'));
		
		$userdata = array(
			'user_id' => $row->id,
			'fibit_id' => $row->fitbit_id,
			'oauth_secret' => $row->oauth_secret,
			'oauth_token' => $row->oauth_token,
			'username' => $row->username,
			'avatar' => $row->profile_pic,
			'isadmin'=> $row->admin,
			'isleader'=> $row->leader,
			'isTutor' => $row->staff,
			'masquerade_id' => $this->session->userdata('user_id'),
			'name' => $row->first_name
			);
		$this->session->set_userdata($userdata);
		echo json_encode(array("success" => true));

	}

	private function loadPage($data){
		$data['active'] = 'masquerade';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');

		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('masquerade', $data);
		$this->load->view('templates/footer');
	}

}