<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends CI_Controller {

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
		$data["challenges"] = $this->Challenge_model->getIndividualCurrentChallenges($this->uid);
				echo "<pre>"; print_r($data);echo "</pre><br>";

		$data["tab"] = "my";
		$this->loadPage($data);	
	}		

	public function all(){
		$data["tab"] = "all";
		
		$data["challenges"] = $this->loadAvailableChallanges();

		$this->loadPage($data);
		
	}

	public function data() {
		echo "<pre>"; print_r($this->loadAvailableChallanges());echo "</pre><br>";


	}

	public function loadAvailableChallanges() {
		$challenges = $this->Challenge_model->getAllChallenges();
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
		echo "<pre>"; print_r($current);echo "</pre><br>";
		if(empty($current)) {
			return "";
		} else {
			$count = 0;
			// check fix category
			foreach($current as $now) {
				
				if($now->category>0 && ($now->category==$new->category)) {
					$msg = "You can join only one challenge in <b><i>%s</i></b> category per day. Please drop <b><i>%s</i></b> to join this one.";
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
					}
					return sprintf($msg, $category, $now->title);
				}

				if($now->category == 0) {
					$count++;
					if($count == 3) {
						return "You can join maximum three <b><i>time based</i></b> challenges per day. Please drop existing challenges to join this one";
					}
				}
			}
			return "";
		}

	}

	public function joinChallengeTomorrow() {
		$challenge_id = 1;//$this->input->post("challenge_id");
		$uid = 50;//$this->input->post("uid");
		$start_time = date("Y-m-d",time()+ 60 * 60 * 24). " 00:00:00";		
		$end_time = date("Y-m-d", time() + 60 * 60 * 24). " 23:59:59";

		$invalid = $this->validateChallengeAvilability($challenge_id, $uid, $start_time, $end_time);

		if((bool) $invalid) {
			$msg = array(
				"success" => false,
				"message" => $invalid
				);
			echo json_encode($msg);
		} else {
			
			$id = $this->Challenge_model->joinChallenge($uid, $challenge_id, $start_time, $end_time);
			$msg = array(
				"success" => true,
				"message" => "You have joined the challenge successfully."
				);
			echo json_encode($msg);
		}
	}

	public function joinChallengeNow(){
		//TODO subscribe
		$challenge_id = 1;//$this->input->post("challenge_id");
		$uid = 50;// $this->input->post("uid");
		$start_time = date("Y-m-d G:i:s",time());		
		$end_time = date("Y-m-d"). " 23:59:59";

		$invalid = $this->validateChallengeAvilability($challenge_id, $uid, $start_time, $end_time);

		if((bool) $invalid) {
			$msg = array(
				"success" => false,
				"message" => $invalid
				);
			echo json_encode($msg);
		} else {
			
			$id = $this->Challenge_model->joinChallenge($uid, $challenge_id, $start_time, $end_time);
			$msg = array(
				"success" => true,
				"message" => "You have joined the challenge successfully."
				);
			echo json_encode($msg);
		}
		//return 
	}

	public function quitChallenge(){
		//TODO unsubscribe
		return $this->Challenge_model->quitChallenge($this->input->post("id"));
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