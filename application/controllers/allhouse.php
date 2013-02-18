<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Allhouse extends CI_Controller {
	
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
		$this->loadPage($this->loadData(1));
	}

	public function house($house_id) {
		$this->loadPage($this->loadData($house_id));
	}
	private function loadData($house_id) {
		$data = array();

		$data['house_id'] = $house_id;
		$members = $this->User_model->loadGroupMemberAvatar($house_id);
		$badges = $this->Badge_model->getHouseBadges($house_id);
		$completed = $this->Challenge_model->getMyHouseStats($house_id);
		$current = $this->Challenge_model->getHouseCurrentChallenges($house_id);
		$tomorrow = $this->Challenge_model->getHouseTomorrowChallenges($house_id);

		
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
		$rank = $this->Challenge_model->getHouseRankAndPoints($house_id);
		$data['rank'] = $rank;
		return $data;

	}
	public function data() {
		echo "<pre>"; print_r($this->loadData(-1));echo "</pre><br>";

	}

	private function loadPage($data){
		$data['active'] = 'allhouse';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');

		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('allhouse', $data);
		$this->load->view('templates/footer');
	}

}