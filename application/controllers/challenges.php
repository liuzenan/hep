<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends CI_Controller {

	public function index(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data["challenges"] = $this->getMyChallenges();
			$data["tab"] = "my";
			$this->loadPage($data);
		}
	}		

	public function all(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data["challenges"] = $this->getAllChallenges();
			$data["tab"] = "all";
			$this->loadPage($data);
		}		
	}

	public function completed(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data["challenges"] = $this->getCompletedChallenges();
			$data["tab"] = "completed";
			$this->loadPage($data);
		}			
	}


	private function getMyChallenges(){
		$this->load->model('Challenge_model','challengeModel');
		$challenges = $this->challengeModel->getCurrentChallenges($this->session->userdata("user_id"));
		return $challenges;
	}

	private function getCompletedChallenges(){
		

	}

	private function getAllChallenges(){
		$this->load->model('Challenge_model','challengeModel');
		$challenges = $this->challengeModel->getAllChallenges($this->session->userdata("user_id"));

		return $challenges;
	}

	public function joinChallenge(){

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
			$data['notifications'] = $this->userModel->getNotifications($this->session->userdata("user_id"));
			$this->load->view('templates/header', $data);
			$this->load->view('challenges', $data);
			$this->load->view('templates/footer');
	}

}