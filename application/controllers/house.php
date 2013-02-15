<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class House extends CI_Controller {
	
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
		

		$this->loadPage($this->loadData());
	}
	private function loadData() {
		$user = $this->User_model->loadUser($this->uid);

		$members = $this->User_model->loadGroupMemberAvatar($user->house_id);
		$badges = $this->Badge_model->getHouseBadges($user->house_id);
		$completed = $this->Challenge_model->getMyHouseStats($user->house_id);
		$current = $this->Challenge_model->getHouseCurrentChallenges($user->house_id);
		$tomorrow = $this->Challenge_model->getHouseTomorrowChallenges($user->house_id);
				
		
		$data = array();
		foreach($members as $m) {
			$data['data'][$m->id]['profile'] = $m;
		}
		foreach($badges as $uid => $b) {
			$data['data'][$uid]['badge'] = $b;
		}

		foreach($completed as $uid => $c) {
			$data['data'][$uid]['completed'] = $c;
		}

		foreach($current as $uid => $u) {
			$data['data'][$uid]['current'] = $u;
		}
		foreach($tomorrow as $uid => $u) {
			$data['data'][$uid]['tomorrow'] = $u;
		}
		$rank = $this->Challenge_model->getHouseRankAndPoints($user->house_id);
		$data['rank'] = $rank;
		return $data;

	}
	public function data() {
		echo "<pre>"; print_r($this->loadData());echo "</pre><br>";

	}

	private function loadPage($data){
		$data['active'] = 'house';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('house', $data);
		$this->load->view('templates/footer');
	}

}