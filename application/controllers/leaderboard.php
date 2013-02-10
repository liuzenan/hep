<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard extends CI_Controller {
	const female = 'FEMALE';
	const male = 'MALE';
	const all = 'all';
	const tutor = 'tutor';

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
		$this->overall();	
	}

	public function overall(){
		$data['currentTab'] = "overall";
		$data['leader'] = $this->Challenge_model->getLearderboard();
		$data['female'] = $this->Challenge_model->getLearderboardByGender(Leaderboard::female);
		$data['male'] = $this->Challenge_model->getLearderboardByGender(Leaderboard::male);
		$this->loadPage($data);
	}


	public function staff(){
		$data['currentTab'] = "staff";
		$data['staff'] = $this->Challenge_model->getTutorLearderboard();
		var_dump($data);
		$this->loadPage($data);
	}

	public function house() {
		$data['currentTab'] = "house";
		$data['house'] = $this->Challenge_model->getHouseLeaderboard();
		
		echo "<pre>"; print_r($data);echo "</pre><br>";
		$this->loadPage($data);
	}

	private function loadPage($data){
		$data['active'] = 'leaderboard';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('leaderboard', $data);
		$this->load->view('templates/footer');
	}

}