<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badges extends CI_Controller {

	private $uid;
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}

	public function index()
	{
		$badges = $this->Badge_model->getAllBadges();
		$data['badges'] = $badges;
		$this->loadPage($data);
	}

	private function loadPage($data){
		$data['active'] = 'badges';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('badges', $data);
		$this->load->view('templates/footer');
	}
}