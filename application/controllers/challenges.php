<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends CI_Controller {

    private $user_id = true;
	
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		} 
	}

	public function index(){
		
		$data["challenges"] = $this->getMyChallenges();
		$data["tab"] = "my";
		$this->loadPage($data);
		
	}		

	public function all(){
		
		$data["challenges"] = $this->getAllChallenges();
		$data["tab"] = "all";
		$this->loadPage($data);
		
	}

	public function completed(){
		
		$data["challenges"] = $this->getCompletedChallenges();
		$data["tab"] = "completed";
		$this->loadPage($data);
		
	}


	private function getMyChallenges(){
		$challenges = $this->Challenge_model->getCurrentChallenges($this->uid);
		return $challenges;
	}

	private function getCompletedChallenges(){
		

	}

	private function getAllChallenges(){
		$this->load->model('Challenge_model','challengeModel');
		$challenges = $this->challengeModel->getAllChallenges($this->uid);

		return $challenges;
	}

	public function joinChallenge(){
		$title = $this->db->escape($this->input->post("title"));
		$message = $this->db->escape($this->input->post("message"));
		$anonymous = $this->input->post("anonymous");
		$subscribe = $this->input->post("subscribe");
		$topic_id = $this->input->post("topic_id");
	}

	public function quitChallenge(){

	}


	private function loadPage($data, $type="challenges"){
		$data['active'] = 1;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$this->load->model('User_model','userModel');
		$data['notifications'] = $this->userModel->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('challenges', $data);
		$this->load->view('templates/footer');
	}

}