<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}
	
	public function index(){
		$data['active'] = 0;

		$user_id = $this->session->userdata('user_id');
		$this->loadUserData($user_id, $data);
		$this->loadActivityData($user_id, $data);
		//$this->loadPosts($user_id, $data);
		$this->loadChallenges($user_id, $data);
		$this->loadBadges($user_id, $data);	
		$data['avg_points'] = $this->getAverage();

		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
		
	}

	private function loadPosts($user_id, &$data) {

	}

	private function loadUserData($user_id, &$data) {
		$this->load->model('User_model','userModel');
		$user = $this->userModel->loadUser($user_id);
		
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



		$data['avatar'] = $avatar;
		$this->session->set_userdata('avatar', $avatar);
		$this->session->set_userdata('isTutor', $isTutor);
		$this->session->set_userdata('isphantom', $isphantom);
		$this->session->set_userdata('isleader', $isleader);
		$this->session->set_userdata('isadmin', $isadmin);

		$data['gender'] = $gender;
		$data['displayName'] = $displayName;
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['notifications'] = $this->userModel->getNotifications($this->session->userdata("user_id"));
	}

	private function loadActivityData($user_id, &$data) {
		$this->load->model('Activity_model', 'activityModel');
		$activityRow = $this->activityModel->getActivityToday($user_id);
		$average = $this->activityModel->getAverageActivityToday();
		$average->sleep = $this->activityModel->getAverageSleepToday()->avg_time;
		$data['avg'] = $average;

		if($activityRow != FALSE){
			$data['activescore'] = $activityRow->active_score;
			$data['calories'] = $activityRow->activity_calories;
			$data['distance'] = $activityRow->distance;
			$data['floors'] = $activityRow->floors;
			$data['steps'] = $activityRow->steps;
		}else{
			$data['activescore'] = 0;
			$data['calories'] = 0;
			$data['distance'] = 0;
			$data['floors'] = 0;
			$data['steps'] = 0;
		}
	}

	private function loadBadges($user_id, &$data){
		$this->load->model('Badge_model', 'badgeModel');
		$data['badges'] = $this->badgeModel->getBadges($user_id);

	}

	private function loadChallenges($user_id, &$data){
		$this->load->model('Challenge_model', 'challengeModel');

		$data['challenges'] = $this->challengeModel->getCurrentChallenges($user_id);
		
	}

	private function getAverage(){
		$sql = "SELECT avg(points) AS avg_points
		FROM user
		WHERE phantom=0";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->row();
		}
		
	}
}