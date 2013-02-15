<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends CI_Controller {

	private $uid;
	private $now;
	private $today_start;
	private $today_end;
	private $tomorrow_start;
	private $tomorrow_end;
	private $date_today;
	private $date_tomorrow;
	
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
			$this->tomorrow_start = date("Y-m-d",time()+ 60 * 60 * 24). " 00:00:00";		
			$this->tomorrow_end = date("Y-m-d", time() + 60 * 60 * 24). " 23:59:59";
			$this->today_start = date("Y-m-d"). " 00:00:00";
			$this->today_end = date("Y-m-d")." 23:59:59";
			$this->now = date("Y-m-d G:i:s",time());
			$this->date_today = date("Y-m-d", time());
			$this->date_tomorrow = date("Y-m-d", time()+ 60*60*24);

		} 
	}

	public function index(){
		$data["challenges"] = $this->Challenge_model->getIndividualCurrentChallenges($this->uid);
		$tomorrow= $this->Challenge_model->loadUserChallenge($this->uid, $this->date_tomorrow);

		foreach($data["challenges"] as $c) {
			$data["today"][$c->category]=$c;
		}
		foreach($tomorrow as $c2) {
			$data["tomorrow"][$c2->category]=$c2;
		}

		$all = $this->loadAvailableChallanges();
		foreach($all as $c3) {
			$data['all'][$c3->category][] = $c3;
		}
		//echo "<pre>"; print_r($data);echo "</pre><br>";

		$data["tab"] = "my";
		$this->loadPage($data);	
	}		

	public function all(){
		$data["tab"] = "all";
		
		$data["challenges"] = $this->loadAvailableChallanges();

		$data["user_id"] = $this->uid;
		//echo "<pre>"; print_r($data);echo "</pre><br>";

		$this->loadPage($data);
		
	}


	public function data() {
		$data["challenges"] = $this->Challenge_model->getIndividualCurrentChallenges($this->uid);
		$tomorrow= $this->Challenge_model->loadUserChallenge($this->uid, $this->date_tomorrow);

		foreach($data["challenges"] as $c) {
			$data["today"][$c->category]=$c;
		}
		foreach($tomorrow as $c2) {
			$data["tomorrow"][$c2->category]=$c2;
		}

		$all = $this->loadAvailableChallanges();
		foreach($all as $c3) {
			$data['all'][$c3->category][] = $c3;
		}
		
		echo "<pre>"; print_r($data);echo "</pre><br>";
	}

	

	public function loadAvailableChallanges() {
		$challenges = $this->Challenge_model->getAllChallenges();
		$joinedToday = $this->Challenge_model->loadJoinedCategory($this->uid, $this->date_today);
		$joinedTomorrow = $this->Challenge_model->loadJoinedCategory($this->uid, $this->date_tomorrow);
		$today = array(0=>0,1=>0,2=>0,3=>0);
		$tomorrow = array(0=>0,1=>0,2=>0,3=>0);

		$todayJoined = array();
		$cpIds = array();
		foreach($joinedToday as $a) {
			$today[$a->category]++;
			$todayJoined[] = $a->challenge_id;
			$cpIds[$a->challenge_id] = $a->cp_id;
		}
		$tomorrowJoined = array();
		$cpIds2 = array();

		foreach($joinedTomorrow as $b) {
			$tomorrow[$b->category]++;
			$tomorrowJoined[] = $b->challenge_id;
			$cpIds2[$b->challenge_id] = $b->cp_id;

		}

		foreach($challenges as $c) {
			$c->user_id = $this->uid;
			$c->disabled_today = ($today[$c->category]>0);
			$c->disabled_tomorrow = ($tomorrow[$c->category]>0);
			$c->joined_today = in_array($c->id, $todayJoined);
			$c->joined_tomorrow = in_array($c->id, $tomorrowJoined);
			$c->cp_id_today = empty($cpIds[$c->id])? -1: $cpIds[$c->id];
			$c->cp_id_tomorrow = empty($cpIds2[$c->id])? -1:$cpIds2[$c->id];
			
		}


		return $challenges;

	}
	public function completed(){
		$data["tab"] = "completed";
		$data["challenges"] = $this->Challenge_model->getIndividualCompletedChallenges($this->uid);
		$this->loadPage($data);

	}

	private function validateChallengeAvilability($challenge_id, $uid, $date) {
		$current = $this->Challenge_model->loadUserChallenge($uid, $date);
		$new = $this->Challenge_model->loadChallenge($challenge_id);
		if(empty($current) && $challenge_id>0 && $uid>0) {
			return "";
		} else {
			$count = 0;
			// check fix category
			foreach($current as $now) {

				if($now->category>0 && ($now->category==$new->category)) {
					$msg = "You can join only one challenge in %s category per day. Please drop %s to join this one.";
					$category = "";
					switch($now->category) {
						case 1:
						$category = "Steps";
						break;
						case 2:
						$category = "Floors";
						break;
						case 3: 
						$category = "Sleeping";
						break;
						case 0:
						$category = "Time Based";
					}
					return sprintf($msg, $category, $now->title);
				}
			}
			return "";
		}

	}

	public function joinChallengeTomorrow() {
		if(!$this->session->userdata('user_id')){
			$msg = array(
				"success" => false,
				"login" => false
				);
		}else{

			$challenge_id = $this->input->post("challenge_id");
			$uid = $this->input->post("user_id");
			$old_cpid = $this->input->post("old_cpid");
			$msg = $this->joinChallenge($uid, $old_cpid, $challenge_id, $this->date_tomorrow);
		}
		echo json_encode($msg);

	}

	public function joinChallengeNow(){
		if(!$this->session->userdata('user_id')){
			$msg = array(
				"success" => false,
				"login" => false
				);
		}else{

			$challenge_id = $this->input->post("challenge_id");
			$challenge = $this->Challenge_model->loadChallenge($challenge_id);
			$uid = $this->input->post("user_id");
			$old_cpid = $this->input->post("old_cpid");

			$msg = $this->joinChallenge($uid, $old_cpid, $challenge_id, $this->date_today);
		}
		echo json_encode($msg);

	}

	public function joinChallenge($uid, $old_cpid, $challenge_id, $date) {
		//delete old
		//join challenge
		//subscribe
		//post 
		if($old_cpid > 0) {
			$this->Challenge_model->quitChallenge($old_cpid);
		}
		$invalid = $this->validateChallengeAvilability($challenge_id, $uid, $date);

		if((bool) $invalid) {
			$msg = array(
				"success" => false,
				"message" => $invalid
				);
		} else {
			$challenge = $this->Challenge_model->loadChallenge($challenge_id);

			//update time for "liftstyle" challenge
			if($challenge->start_time != "00:00:00") {
				$start = $date." ".$challenge->start_time;
				$end = $date." ".$challenge->end_time;
			} else {
				if ($date == $this->date_today) {
					$start = $this->today_start;
					$end = $this->today_end;
				} else {
					$start = $this->tomorrow_start;
					$end = $this->tomorrow_end;
				}
			}
			$id = $this->Challenge_model->joinChallenge($uid, $challenge_id, $start, $end);

			$this->Forum_model->subscribe($uid, $challenge_id);
			
			$user = $this->User_model->loadUser($uid);
		//	$message = $user->first_name." ".$user->last_name. " joined this challenge at ". $start .".";
		//	$this->Forum_model->createPost($uid, $challenge->thread_id, $message);
			$msg = array(
				"success" => true,
				"message" => "You have joined the challenge successfully.",
				"challenge" => $challenge
				);
		}

		return $msg;
	}

	public function quitChallenge(){
		$id = $this->input->post("id");
		$cp = $this->Challenge_model->loadChallengeParticipation($id);
		$this->Challenge_model->quitChallenge($id);
		if(!empty($cp)) {
			$this->Forum_model->unsubscribe($cp->user_id, $cp->challenge_id);
		}
		$msg = array(
			"success" => false,
			"message" => "You have quitted the challenge",
			"challenge_id" => $cp->challenge_id
			);
		echo json_encode($msg);
	}


	private function loadPage($data, $type="challenges"){
		$data['active'] = 'challenges';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('challenges', $data);
		$this->load->view('templates/footer');
	}

}