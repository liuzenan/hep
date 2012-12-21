<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends CI_Controller {

	public function index(){
		$this->output->cache(200);
		$data['active'] = 2;
		$data['activeTab'] = 1;
		if($this->session->userdata('user_id')){
			$data['displayName'] = $this->session->userdata('username');
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM Activity WHERE user_id='". $user_id ."'");
			$stepsData= array();
			if($query->num_rows()>0){
				foreach($query->result() as $row){
					array_push($stepsData, $row->steps);
				}
			}
			$data['stepsData'] = $stepsData;
			$data['chartTitle'] = 'Steps';
			$this->load->view('templates/header', $data);
			$this->load->view('activity', $data);
			$this->load->view('templates/footer');			
		}else{
			redirect(base_url() . "index.php/login");
		}

	}

	public function floors(){
		$this->output->cache(200);
		$data['active'] = 2;
		$data['activeTab'] = 2;
		if($this->session->userdata('user_id')){
			$data['displayName'] = $this->session->userdata('username');
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM Activity WHERE user_id='". $user_id ."'");
			$stepsData= array();
			if($query->num_rows()>0){
				foreach($query->result() as $row){
					array_push($stepsData, $row->floors);
				}
			}
			$data['stepsData'] = $stepsData;
			$data['chartTitle'] = 'Floors Climbed';
			$this->load->view('templates/header', $data);
			$this->load->view('activity', $data);
			$this->load->view('templates/footer');			
		}else{
			redirect(base_url() . "index.php/login");
		}
	}

	public function distance(){
		$this->output->cache(200);
		$data['active'] = 2;
		$data['activeTab'] = 3;
		if($this->session->userdata('user_id')){
			$data['displayName'] = $this->session->userdata('username');
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM Activity WHERE user_id='". $user_id ."'");
			$stepsData= array();
			if($query->num_rows()>0){
				foreach($query->result() as $row){
					array_push($stepsData, $row->distance);
				}
			}
			$data['stepsData'] = $stepsData;
			$data['chartTitle'] = 'Total Distance';
			$this->load->view('templates/header', $data);
			$this->load->view('activity', $data);
			$this->load->view('templates/footer');			
		}else{
			redirect(base_url() . "index.php/login");
		}
	}

	public function calories(){
		$this->output->cache(200);
		$data['active'] = 2;
		$data['activeTab'] = 4;
		if($this->session->userdata('user_id')){
			$data['displayName'] = $this->session->userdata('username');
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM Activity WHERE user_id='". $user_id ."'");
			$stepsData= array();
			if($query->num_rows()>0){
				foreach($query->result() as $row){
					array_push($stepsData, $row->calories);
				}
			}
			$data['stepsData'] = $stepsData;
			$data['chartTitle'] = 'Calories Burned';
			$this->load->view('templates/header', $data);
			$this->load->view('activity', $data);
			$this->load->view('templates/footer');			
		}else{
			redirect(base_url() . "index.php/login");
		}
	}

	public function activeScore(){
		$this->output->cache(200);
		$data['active'] = 2;
		$data['activeTab'] = 5;
		if($this->session->userdata('user_id')){
			$data['displayName'] = $this->session->userdata('username');
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM Activity WHERE user_id='". $user_id ."'");
			$stepsData= array();
			if($query->num_rows()>0){
				foreach($query->result() as $row){
					array_push($stepsData, $row->active_score);
				}
			}
			$data['stepsData'] = $stepsData;
			$data['chartTitle'] = 'Activity Score';
			$this->load->view('templates/header', $data);
			$this->load->view('activity', $data);
			$this->load->view('templates/footer');			
		}else{
			redirect(base_url() . "index.php/login");
		}
	}


}