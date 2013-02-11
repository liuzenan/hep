<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{
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

		$data = $this->loadData();
		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
		
	}

	private function loadData() {
		$data['active'] = 'home';
		//update progress
		$this->Challenge_model->updateActivityProgress($this->uid);

		$this->loadUserData($this->uid, $data);
		$this->loadActivityData($this->uid, $data);

		$data['me_today'] = $this->Activity_model->getActivityToday($this->uid);
		$data['me_yesterday'] = $this->Activity_model->getActivityYesterday($this->uid);

		$data['me_sleep'] = $this->Activity_model->getSleepToday($this->uid);
		$data['me_sleep_yesterday'] = $this->Activity_model->getSleepYesterday($this->uid);


		$data['delta_steps'] = number_format($this->cauculateDelta($data['me_today']->steps, $data['me_yesterday']->steps),2);
		$data['delta_floors'] = number_format($this->cauculateDelta($data['me_today']->floors, $data['me_yesterday']->floors),2);
		$data['delta_calories'] = number_format($this->cauculateDelta($data['me_today']->calories, $data['me_yesterday']->calories),2);
		$data['delta_distance'] = number_format($this->cauculateDelta($data['me_today']->distance, $data['me_yesterday']->distance),2);


		$data['me_challenges'] = $this->Challenge_model->getIndividualCurrentChallenges($this->uid);
		$data['me_badges'] = $this->Badge_model->getBadges($this->uid);
		$data['me_completed'] = $this->Challenge_model->getIndividualChallengeCount($this->uid);

		$data['avg_today'] = $this->Activity_model->getAverageActivityToday();
		$data['avg_sleep'] = $this->Activity_model->getAverageSleepToday();
		$data['avg_completed'] = number_format($this->Challenge_model->getAverageChallengeCount(),2);
		$data['max_today'] = $this->Activity_model->getMaxActivityToday();


		return $data;
	}

	public function data() {
		echo "<pre>"; print_r($this->loadData());echo "</pre><br>";
	}

	private function cauculateDelta($today, $yesterday) {
		if($yesterday == 0) {
			return $today == 0 ? 0 : 1;
		} else {
			return ($today-$yesterday)/($yesterday);
		}
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
		$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));
	}

	private function loadActivityData($user_id, &$data) {
		$activityRow = $this->Activity_model->getActivityToday($user_id);
		$average = $this->Activity_model->getAverageActivityToday();
		$average->sleep = $this->Activity_model->getAverageSleepToday()->avg_time;
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
}