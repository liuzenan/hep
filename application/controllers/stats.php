<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}
	public function index(){
		
		$this->statistics();
		
	}

	public function statistics(){
		$data['active'] = 5;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['stats'] = $this->getStats();
		$data['currentTab'] = "statistics";
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$this->load->model('User_model','userModel');
		$data['notifications'] = $this->userModel->getNotifications($this->uid);
		$this->load->view("templates/header", $data);
		$this->load->view("stats", $data);
		$this->load->view("templates/footer");
	}

	public function history($type='steps', $span="week"){
		$data['active'] = 5;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['chartTitle'] = $type;
		date_default_timezone_set('UTC');
		$currentDate = date('Y-m-d');

		if ($span=="week") {
			$weekBegin = date('Y-m-d', strtotime($currentDate)-604800);
		} else if ($span=="month") {
			$weekBegin = date('Y-m-d', strtotime($currentDate)-604800*4);
		}
		

		$data['startDate'] = $weekBegin;
		$data['currentActivity'] = $this->getActivity($type, $weekBegin, $currentDate);
		$data['activeActivity'] = $type;
		$data['currentTab'] = "history";
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['span'] = $span;
		$this->load->model('User_model','userModel');
		$data['notifications'] = $this->userModel->getNotifications($this->uid);
		$this->load->view("templates/header", $data);
		$this->load->view("stats", $data);
		$this->load->view("templates/footer");
	}

	private function getActivity($type, $startDate, $endDate){

		try {
			$this->load->model('Activity_model','activities');
			$data = $this->activities->get_activity($startDate, $endDate);
		} catch (Exception $e) {
		}
		
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
				default:
					# code..
				break;
			}

			if(count($activity)<7){
				$num = count($activity);
				for($num;$num<7;$num++){
					array_push($activity, 0);
				}
			}
			return $activity;
		}
	}

	private function getStats(){
		if($this->session->userdata('oauth_token')&&$this->session->userdata('oauth_secret')){
			$this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
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