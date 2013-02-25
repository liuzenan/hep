<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{
	private $uid;
	private $now;
	private $today_start;
	private $today_end;
	private $tomorrow_start;
	private $tomorrow_end;
	private $date_today;
	private $date_tomorrow;
	private $disabled;
	public function __construct() {
		parent::__construct();
				$this->load->model("Mail_model");

		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {

			//validate
			$valid = $this->User_model->validateUser($this->session->userdata('user_id'));
			if(!empty($valid)) {
				redirect(base_url() . "signup");
			} else {
				$this->uid = $this->session->userdata('user_id');
				$this->load->library('ga_api');

				$this->tomorrow_start = date("Y-m-d",time()+ 60 * 60 * 24). " 00:00:00";		
				$this->tomorrow_end = date("Y-m-d", time() + 60 * 60 * 24). " 23:59:59";
				$this->today_start = date("Y-m-d"). " 00:00:00";
				$this->today_end = date("Y-m-d")." 23:59:59";
				$this->now = date("Y-m-d G:i:s",time());
				$this->date_today = date("Y-m-d", time());
				$this->date_tomorrow = date("Y-m-d", time()+ 60*60*24);

				$this->disabled = !( $this->session->userdata('isadmin')
					|| $this->session->userdata('isleader')
					|| $this->session->userdata('isTutor'));

				if($this->disabled) {
					$this->date_today="2013-02-28";
					$this->date_tomorrow="2013-03-01";
					$this->tomorrow_start = "2013-03-01 00:00:00";		
					$this->tomorrow_end = "2013-03-01 23:59:59";
					$this->today_start = "2013-02-28 00:00:00";
					$this->today_end = "2013-02-28 23:59:59";
				}
			}
		}
	}


	
	public function index(){
			
		$this->Challenge_model->preAllocateChallenge($this->uid, $this->date_today, $this->date_tomorrow);
		
		$data = $this->loadData();
		$this->load->view('templates/header', $data);
		$this->load->view('home', $data);
		$this->load->view('templates/footer');
		
	}

	public function loadData() {
		$data['active'] = 'home';
		//echo xdebug_get_profiler_filename();

		//xdebug_start_trace('/tmp/xdebug/trace' . rand(1,9999999));
		//xdebug_stop_trace();

		//update progress
		//$this->Challenge_model->updateActivityProgress($this->uid);
		$timestr = "";
		$timestr .=  microtime()."<br>";
		$this->loadUserData($this->uid, $data);
		$timestr .=		 microtime()."<br>";

		$this->loadActivityData($this->uid, $data);
		$timestr .= microtime()."<br>";
		$data['me_today'] = $this->Activity_model->getActivityToday($this->uid);
		$data['today'] = $this->Activity_model->getLastUpdate($this->uid);

		$timestr .=		 microtime()."<br>";

		$data['me_yesterday'] = $this->Activity_model->getActivityYesterday($this->uid);
		$timestr .= microtime()."<br>";

		$data['me_today']->sleep = $this->Activity_model->getSleepData($this->uid, date("Y-m-d ",time()))->total_time;
		$data['me_today']->sleep = number_format($data['me_today']->sleep/60,2);
		$timestr .= microtime()."<br>";

		$data['me_sleep_yesterday'] = $this->Activity_model->getSleepData($this->uid, date("Y-m-d ",time() - 60 * 60 * 24));
		$data['me_yesterday']->sleep = $data['me_sleep_yesterday']->total_time;
		$data['me_yesterday']->sleep = number_format($data['me_yesterday']->sleep/60,2);

		$timestr .= microtime()."<br>";

		$data['delta_steps'] = number_format($this->cauculateDelta($data['me_today']->steps, $data['me_yesterday']->steps),2);
		$data['delta_floors'] = number_format($this->cauculateDelta($data['me_today']->floors, $data['me_yesterday']->floors),2);
		$data['delta_calories'] = number_format($this->cauculateDelta($data['me_today']->calories, $data['me_yesterday']->calories),2);
		$data['delta_distance'] = number_format($this->cauculateDelta($data['me_today']->distance, $data['me_yesterday']->distance),2);
		$data['delta_sleep'] = number_format($this->cauculateDelta($data['me_today']->sleep, $data['me_yesterday']->sleep),2);
		$timestr .= microtime()."<br>";


		$cs1 = $this->Challenge_model->loadUserChallenge($this->uid, $this->date_today);
		foreach($cs1 as $c1) {
			$data['me_challenges'][$c1->category]=$c1;
		}
		$timestr .= microtime()."<br>";

		$data['me_badges'] = $this->Badge_model->getBadges($this->uid);
		$timestr .= microtime()."<br>";

		$data['me_completed'] = $this->Challenge_model->getIndividualChallengeCount($this->uid);
		$timestr .= microtime()."<br>";

		$cs2 = $this->Challenge_model->loadUserChallenge($this->uid, date("Y-m-d ",time() - 60 * 60 * 24));
		foreach($cs2 as $c2) {
			$data['me_challenges_yesterday'][$c2->category]=$c2;
		}
		$timestr .= microtime()."<br>";

		$cs3= $this->Challenge_model->loadUserChallenge($this->uid, $this->date_tomorrow);
		foreach($cs3 as $c3) {
			$data['me_challenges_tomorrow'][$c3->category]=$c3;
		}
		$timestr .= microtime()."<br>";

		$data['avg_today'] = $this->Activity_model->getAverageActivityToday();
		$timestr .= microtime()."<br>";

		$data['avg_today']->avg_sleep = number_format($this->Activity_model->getAverageSleepToday()/60,2);
		$timestr .= microtime()."<br>";

		$data['avg_completed'] = number_format($this->Challenge_model->getAverageChallengeCount(),2);
		$timestr .= microtime()."<br>";

		//$data['max_today'] = $this->Activity_model->getMaxActivityToday();
		$data['max_today'] = new stdClass;
		$data['max_today']->max_steps = max($data['avg_today']->avg_steps, $data['me_today']->steps);
		$data['max_today']->max_floors = max($data['avg_today']->avg_floors, $data['me_today']->floors);
		$data['max_today']->max_distance = max($data['avg_today']->avg_distance,$data['me_today']->distance);
		$data['max_today']->max_calories = max($data['avg_today']->avg_calories,$data['me_today']->calories);
		$data['max_today']->max_sleep = max($data['avg_today']->avg_sleep, $data['me_today']->sleep);

		$data['all_challenge'] = $this->Challenge_model->loadAvailableChallanges($this->uid, $this->date_today, $this->date_tomorrow);
		foreach($data['all_challenge'] as $c) {
			$data['all'][$c->category][]=$c;	
		}
		$timestr .= microtime()."<br>";

		//$data['profiling'] = $timestr;
		$data['my_points'] = $this->Challenge_model->getTotalPoints($this->uid);
		$data['cohor_points'] = number_format($this->Challenge_model->getAveragePoints(),1);
		return $data;
	}
/*
	public function email() {
		
		$data = $this->loadData();
		$str = $this->load->view('templates/header', $data, true);
		$str .= $this->load->view('home', $data, true);
		$str .= $this->load->view('templates/footer', true);
		$this->Mail_model->send("hello", $str, "wangshasg@gmail.com");
		echo $str;
	}
*/
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
		//$isadmin = $user->admin;
		$isphantom = $user->phantom;



		$data['avatar'] = $avatar;
		$this->session->set_userdata('avatar', $avatar);
		$this->session->set_userdata('isTutor', $isTutor);
		$this->session->set_userdata('isphantom', $isphantom);
		$this->session->set_userdata('isleader', $isleader);
		//$this->session->set_userdata('isadmin', $isadmin);

		$data['gender'] = $gender;
		$data['displayName'] = $displayName;
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		$data['isPhantom'] = $isphantom;
		$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));
	}

	private function loadActivityData($user_id, &$data) {
		$activityRow = $this->Activity_model->getActivityToday($user_id);
		$average = $this->Activity_model->getAverageActivityToday();
		$average->avg_sleep = number_format($this->Activity_model->getAverageSleepToday()/60,2);
		$data['avg'] = $average;

		$data['activescore'] = $activityRow->active_score;
		$data['calories'] = $activityRow->activity_calories;
		$data['distance'] = $activityRow->distance;
		$data['floors'] = $activityRow->floors;
		$data['steps'] = $activityRow->steps;
		
	}
}