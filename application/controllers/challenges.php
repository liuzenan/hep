<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends CI_Controller {

	private $uid;
	private $now;
	private $today_start;
	private $today_end;
	private $tomorrow_start;
	private $tomorrow_end;
	
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
		} 
	}

	public function index(){
		$data["challenges"] = $this->Challenge_model->getIndividualCurrentChallenges($this->uid);
		echo "<pre>"; print_r($data);echo "</pre><br>";

		$data["tab"] = "my";
		$this->loadPage($data);	
	}		

	public function all(){
		$data["tab"] = "all";
		
		$data["challenges"] = $this->loadAvailableChallanges();

		$data["user_id"] = $this->uid;

		$this->loadPage($data);
		
	}


	public function data() {
		echo "<pre>"; print_r($this->loadAvailableChallanges());echo "</pre><br>";
	}

	

public function loadAvailableChallanges() {
	$challenges = $this->Challenge_model->getAllChallenges();
	$joinedToday = $this->Challenge_model->loadJoinedCategory($this->uid, $this->now, $this->now);
	$joinedTomorrow = $this->Challenge_model->loadJoinedCategory($this->uid, $this->tomorrow_start, $this->tomorrow_end);
	$today = array(0=>0,1=>0,2=>0,3=>0);
	$tomorrow = array(0=>0,1=>0,2=>0,3=>0);

	foreach($joinedToday as $a) {
		$today[$a->category]++;
	}
	foreach($joinedTomorrow as $b) {
		$tomorrow[$b->category]++;
	}

	foreach($challenges as $c) {
		if($c->category>0) {
			$c->disabled_today = ($today[$c->category]>0);
			$c->disabled_tomorrow = ($tomorrow[$c->category]>0);
		} 
	}
	return $challenges;

}
public function completed(){
	$data["tab"] = "completed";
	$data["challenges"] = $this->Challenge_model->getIndividualCompletedChallenges($this->uid);
	$this->loadPage($data);

}

private function validateChallengeAvilability($challenge_id, $uid, $start_time, $end_time) {
	$current = $this->Challenge_model->loadUserChallenge($uid, $start_time, $end_time);
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

		$invalid = $this->validateChallengeAvilability($challenge_id, $uid, $this->tomorrow_start, $this->tomorrow_end);

		if((bool) $invalid) {
			$msg = array(
				"success" => false,
				"message" => $invalid
				);
		} else {

			$id = $this->Challenge_model->joinChallenge($uid, $challenge_id, $this->tomorrow_start, $this->tomorrow_end);
			$msg = array(
				"success" => true,
				"message" => "You have joined the challenge successfully."
				);
		}
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
		//TODO subscribe

		$challenge_id = $this->input->post("challenge_id");
		$uid = $this->input->post("user_id");
		$invalid = $this->validateChallengeAvilability($challenge_id, $uid, $this->now, $this->today_end);

		if((bool) $invalid) {
			$msg = array(
				"success" => false,
				"message" => $invalid
				);
		} else {

			$id = $this->Challenge_model->joinChallenge($uid, $challenge_id, $this->now, $this->today_end);
			$msg = array(
				"success" => true,
				"message" => "You have joined the challenge successfully."
				);
		}
	}
	echo json_encode($msg);

}

public function quitChallenge(){
		//TODO unsubscribe
	$this->Challenge_model->quitChallenge($this->input->post("id"));
	$msg = array(
		"success" => false,
		"message" => "You have quitted the challenge"
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