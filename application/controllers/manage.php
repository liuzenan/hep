<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}
	
	public function index(){
		if($this->session->userdata('isadmin')){
			$data['students'] = $this->getAllUsers();
			$this->loadPage($data, "admin");
		} else {
			redirect(base_url() . "home");
		}

		
	}

	public function studentList(){
		
		if($this->session->userdata('isadmin')||$this->session->userdata('isleader')){

			$data['students'] = $this->getlist();
			$data['currentHouse'] = $this->getHouse();
			$this->loadPage($data);
		}else{
			redirect(base_url() . "home");
		}			
		
	}

	private function loadPage($data, $page="studentLeader"){
		$data['active'] = 6;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$this->load->model('User_model','userModel');
		$data['notifications'] = $this->userModel->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view($page, $data);
		$this->load->view('templates/footer');
	}

	private function getAllUsers(){
		$query = $this->db->query("SELECT * FROM user");
		if ($query->num_rows()>0) {
			# code...
			return $query->result();
		}
	}


	private function getlist(){
		$user_id = $this->uid;
		$query = $this->db->query("SELECT house_id FROM user WHERE id = " . $user_id);
		if($query->num_rows()>0){
			$house_id=$query->row()->house_id;
			$query = $this->db->query("SELECT * FROM user WHERE house_id = " . $house_id);

			if($query->num_rows()>0){
				return $query->result();
			}
		}
	}

	private function getHouse(){
		$user_id = $this->uid;
		$sql = "SELECT house.name AS house_name
		FROM house
		INNER JOIN user
		ON user.house_id = house.id
		WHERE user.id = " . $user_id;

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$house = $query->row()->house_name;
			return $house;
		}
	}

}