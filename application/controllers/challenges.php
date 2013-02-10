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
		$data["tab"] = "my";
		$this->loadPage($data);	
	}		

	public function all(){
		$data["tab"] = "all";
		$challenges = $this->Challenge_model->getAllChallenges();
		$data["challenges"] = $challenges;
		$this->loadPage($data);
		
	}

	public function completed(){
		$data["tab"] = "completed";
		$data["challenges"] = $this->Challenge_model->getIndividualCompletedChallenges($this->uid);
		$this->loadPage($data);
		
	}


	public function joinChallenge(){
		//TODO subscribe
		
		$challenge_id = $this->input->post("challenge_id");
		$start_time = $this->input->post("start_time");
		$end_time = $this->input->post("end_time");
		return $this->Challenge_model->joinChallenge($uid, $challenge_id, $start_time, $end_time);
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