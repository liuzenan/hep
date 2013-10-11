<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surveyresult extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}


	public function index() {
		$house_id = 1;
		$this->house($house_id);
	}

	public function house($house_id){
		$sql = "SELECT * FROM survey
				INNER JOIN user
				ON survey.userid = user.id
				WHERE user.house_id = " . $this->db->escape($house_id);

		$query = $this->db->query($sql);

		if ($query->num_rows()>0) {
			# code...
			$data['surveyResult'] = $query->result();
			
			
		}		

		$data['house_id'] = $house_id;
		$this->loadPage($data);
	}

	public function all() {
		$sql = "SELECT * FROM survey
				INNER JOIN user
				ON survey.userid = user.id";


		$query = $this->db->query($sql);

		if ($query->num_rows()>0) {
			# code...
			$data['surveyResult'] = $query->result();
		}

		$data['house_id'] = "all";
		$this->loadPage($data);
	}

	public function count(){
		$sql1 = "SELECT user.first_name, user.last_name, user.email FROM user
				WHERE user.id NOT IN ( SELECT userid FROM survey )
				AND user.phantom = 0
				AND user.staff = 0";

		$query1 = $this->db->query($sql1);

		if ($query1->num_rows()>0) {
			# code...
			$data['not_complete'] = $query1->result();
		}

		$sql2 = "SELECT user.first_name, user.last_name, user.email FROM user
				WHERE user.id IN ( SELECT userid FROM survey )
				AND user.phantom = 0
				AND user.staff = 0";

		$query2 = $this->db->query($sql2);

		if ($query2->num_rows()>0) {
			# code...
			$data['complete'] = $query2->result();
		}

		$data['house_id'] = "count";
		$this->loadPage($data);
	}

	private function loadPage($data){
		$data['active'] = 'surveyresult';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('surveyresult', $data);
		$this->load->view('templates/footer');
	}

}