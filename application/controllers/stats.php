<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {
	private $uid;
	private $accessible;
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		
			$this->accessible = ($this->session->userdata('isadmin') || $this->session->userdata('isTutor'));
		}
	}
	public function index(){
		$this->session->set_userdata(array('stats_uid'=>$this->uid));			
		$this->history('steps', 'week');		
	}

	public function statistics($uid = NULL){
		if(!is_null($uid) && $this->accessible) {
			$this->session->set_userdata(array('stats_uid'=>$uid));			
		}
		$uid = $this->session->userdata('stats_uid');
		$data['stats'] = $this->getStats($uid);
		$data['currentTab'] = "statistics";
		$data['stats_uid'] = $uid;
		$this->loadPage($data);
		
	}



	public function history($type=NULL, $span=NULL, $uid = NULL){
		if(!is_null($uid) && $this->accessible) {
			$this->session->set_userdata(array('stats_uid'=>$uid));			
		} 
		if(!is_null($span)) {
			$this->session->set_userdata(array('span'=>$span));			
		}
		if(!is_null($type)) {
			$this->session->set_userdata(array('type'=>$type));				
		}
		$span = $this->session->userdata('span');
		$type = $this->session->userdata('type');
		$uid = $this->session->userdata('stats_uid');
		echo $type." ".$span." ".$uid;

		$data['chartTitle'] = $type;
		$currentDate = date('Y-m-d');

		if ($span=="week") {
			$weekBegin = date('Y-m-d', strtotime($currentDate)-604800);
		} else if ($span=="month") {
			$weekBegin = date('Y-m-d', strtotime($currentDate)-604800*4);
		}
		

		$data['startDate'] = $weekBegin;
		$data['currentActivity'] = $this->getActivity($type, $weekBegin, $currentDate, $uid);
		$data['activeActivity'] = $type;
		$data['currentTab'] = "history";
		$data['span'] = $span;
		$data['stats_uid'] = $uid;

		
		$this->loadPage($data);
	}

	private function loadPage($data) {
		$data['stats_user'] = $this->User_model->loadUser($data['stats_uid']);
		$data['active'] = ($data['stats_uid'] == $this->uid) ? 'stats' : 'none';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view("templates/header", $data);
		$this->load->view("stats", $data);
		$this->load->view("templates/footer");
	}

	public function data() {
		$currentDate = date('Y-m-d');
		var_dump($this->Activity_model->get_activity(date('Y-m-d', strtotime($currentDate)-604800), $currentDate , $this->uid));
	}

	private function getActivity($type, $startDate, $endDate, $uid){

		$data = $this->Activity_model->get_activity($startDate, $endDate, $uid);
		
		if($data){
			switch ($type) {
				case 'steps':
					# code...
				$activity = $data['steps'];
				break;
				case 'floors':
					# code...
				$activity = $data['floors'];
				break;
				case 'calories':
					# code...
				$activity = $data['calories'];
				break;

				case 'distance':
					# code...
				$activity = $data['distance'];
				break;

				case 'activity_calories':
					# code...
				$activity = $data['activity_calories'];
				break;
				case 'elevation':
					# code...
				$activity = $data['elevation'];
				break;
				case 'sleep':
				$activity = $data['sleep'];
				break;
				default:
					# code..
				break;
			}

			
			return $activity;
		}
	}

	private function getStats($uid){

		$user = $this->User_model->loadUser($uid);

		if($user->oauth_token&&$user->oauth_secret){
			$this->fitbitphp->setOAuthDetails($user->oauth_token, $user->oauth_secret);
			$xml = $this->fitbitphp->getActivityStats();
			$best = $xml->best->tracker;
			$lifetime = $xml->lifetime->tracker;
			
			$stats =array();
			if(!empty($best)) {
				$stats['best'] = array(
					'calories' => array(
						'date'=>(string) $best->caloriesOut->date,
						'value'=>(string) $best->caloriesOut->value . " Calories"
						),
					'distance' => array(
						'date'=>(string) $best->distance->date,
						'value'=>(string) $best->distance->value . " km"
						),
					'floors' => array(
						'date'=>(string) $best->floors->date,
						'value'=>(string) $best->floors->value . " levels"
						),
					'steps' => array(
						'date'=>(string) $best->steps->date,
						'value'=>(string) $best->steps->value . " steps"
						)
					);
			}else {
				$stats['best'] = array();
			}

			if(!empty($lifetime)) {
				$stats['lifetime'] = array(
					'calories' => (string) $lifetime->caloriesOut . " Calories",
					'distance' => (string) $lifetime->distance . " km",
				//'floors' => (string) $lifetime->floors . " levels",
					'steps' => (string) $lifetime->steps . " steps"
					);
			}
			return $stats;
		}else{
			echo "oauth error";
		}
	}
}