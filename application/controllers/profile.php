<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}

	public function index(){
		$this->user($this->session->userdata('user_id'));
	}

	public function user($user_id) {
		$this->loadUserData($user_id, $data);
		print_r($data);
		$data['active'] = "profile";
		$this->load->view('templates/header', $data);
		$this->load->view('profile', $data);
		$this->load->view('templates/footer');
	}
	private function loadUserData($user_id, &$data) {
		$user = $this->User_model->loadUser($user_id);
		
		if($this->session->userdata('name')){
			$displayName = $this->session->userdata('name');
		}else{
			$displayName = $user->first_name . ' ' . $user->last_name;
		}

		$this->session->set_userdata('name', $displayName);
		$this->session->set_userdata('username', $user->username);
		$gender =$user->gender;
		$avatar = $user->profile_pic;
		$isTutor = $user->staff;
		$isleader = $user->leader;
		$isadmin = $user->admin;
		$isphantom = $user->phantom;

		$data['badge_email_unsub'] = $user->badge_email_unsub;
		$data['daily_email_unsub'] = $user->daily_email_unsub;
		$data['badge_email_unsub'] = $user->badge_email_unsub;
		$data['house_id'] = $user->house_id;
		$data['gender'] = $user->gender;
		$data['email'] = $user->email;
		$data['avatar'] = $avatar;
		$this->session->set_userdata('avatar', $avatar);
		$this->session->set_userdata('isTutor', $isTutor);
		$this->session->set_userdata('isphantom', $isphantom);
		$this->session->set_userdata('isleader', $isleader);
		$this->session->set_userdata('isadmin', $isadmin);
		$data['first_name'] = $user->first_name;
		$data['last_name'] = $user->last_name;
		$data['gender'] = $gender;
		$data['displayName'] = $displayName;
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		$data['isPhantom'] = $isphantom;
		$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));
	}
}