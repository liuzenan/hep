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
		$user = $this->User_model->loadUser($this->uid);
		$data['house_stats'] = $this->Challenge_model->getMyHouseStats($user->house_id);
		$data['house_current'] = $this->Challenge_model->getHouseCurrentChallenges($user->house_id);
		echo "<pre>"; print_r($data);echo "</pre><br>";

		$this->loadPage($data);
	}

	private function loadPage($data){
		$data['active'] = 'house';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('house', $data);
		$this->load->view('templates/footer');
	}

}