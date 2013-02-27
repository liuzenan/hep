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

	private $disabled;

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
				$this->now = $this->today_start;
			}

		} 
	}

	public function index(){
		
		$this->completed();
	}		

	public function all(){
		$data["tab"] = "all";
		
		$data["challenges"] = $this->Challenge_model->loadAvailableChallanges($this->uid, $this->date_today, $this->date_tomorrow);

		$data["user_id"] = $this->uid;
		//echo "<pre>"; print_r($data);echo "</pre><br>";

		$this->loadPage($data);
		
	}


	public function data() {
		$data["challenges"] = $this->Challenge_model->loadUserChallenge($this->uid, $this->date_today);
		$tomorrow= $this->Challenge_model->loadUserChallenge($this->uid, $this->date_tomorrow);

		foreach($data["challenges"] as $c) {
			$data["today"][$c->category]=$c;
		}
		foreach($tomorrow as $c2) {
			$data["tomorrow"][$c2->category]=$c2;
		}

		$all = $this->Challenge_model->loadAvailableChallanges($this->uid);
		foreach($all as $c3) {
			$data['all'][$c3->category][] = $c3;
		}
		
		echo "<pre>"; print_r($data);echo "</pre><br>";
	}

	

	
	public function completed(){
		$data["tab"] = "completed";
		$data["challenges"] = $this->Challenge_model->getIndividualCompletedChallenges($this->uid);
		$this->loadPage($data);

	}

	private function validateChallengeAvilability($new, $uid, $date) {
		$current = $this->Challenge_model->loadUserChallenge($uid, $date);
		if(empty($current) && $new->challenge_id>0 && $uid>0) {
			return "";
		} else {
			$count = 0;
			// check fix category
			foreach($current as $now) {
				if($now->category==$new->category) {
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
			// check max num of times joinable
			$count = $this->Challenge_model->getParticipationCount($uid, $new->id);
			if($count>=$new->quota) {
				return "You have exceeded the maximum quota of this challenge. Please choose another one.";
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
			$old_cpid = $this->input->post("cp_id");
			if(empty($old_cpid)) {
				$old_cpid = -1;
			}


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
			$old_cpid = $this->input->post("cp_id");
			if(empty($old_cpid)) {
				$old_cpid = -1;
			}

			$msg = $this->joinChallenge($uid, $old_cpid, $challenge_id, $this->date_today);

			//auto join tomorrow
			$this->joinChallenge($uid, -1, $challenge_id, $this->date_tomorrow);

		}
		echo json_encode($msg);

	}

	public function joinChallenge($uid, $old_cpid, $challenge_id, $date) {
		//delete old
		//join challenge
		//subscribe
		//post 
		$challenge = $this->Challenge_model->loadChallenge($challenge_id);

		if($old_cpid > 0) {
			$this->Challenge_model->quitChallenge($old_cpid);
		}
		$invalid = $this->validateChallengeAvilability($challenge, $uid, $date);

		if((bool) $invalid) {
			$msg = array(
				"success" => false,
				"message" => $invalid
				);
		} else {

			//update time for "liftstyle" challenge
			if($challenge->start_time != "00:00:00") {
				$start = $date." ".$challenge->start_time;
				$end = $date." ".$challenge->end_time;
			} else {
				if ($date == $this->date_today) {
					$start = $this->now;
					$end = $this->today_end;
				} else {
					$start = $this->tomorrow_start;
					$end = $this->tomorrow_end;
				}
			}
			$id = $this->Challenge_model->joinChallenge($uid, $challenge_id, $challenge->category, $start, $end);

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
		$data['isTutor'] = $this->session->userdata('isTutor');
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('challenges', $data);
		$this->load->view('templates/footer');
	}

}