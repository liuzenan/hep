<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard extends CI_Controller {

	public function index(){
		if($this->session->userdata('user_id')){
			$this->student();
		}else{
			redirect(base_url().'login');
		}
	}

	public function student(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "student";
			$data['leader'] = $this->getTopStudent();
			$data['topSteps'] = $this->getTopStudentSteps();
			$data['topFloors'] = $this->getTopStudentFloors();
			$data['topSleep'] = $this->getTopStudentSleep();
			$this->loadPage($data);
		}else{
			redirect(base_url().'login');
		}
	}

	public function house(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "house";
			$data['leader'] = $this->getTopHouse();
			$this->loadPage($data);
		}else{
			redirect(base_url().'login');
		}

	}

	public function staff(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "staff";
			$data['leader'] = $this->getTopTutors();
			$data['topSteps'] = $this->getTopTutorSteps();
			$data['topFloors'] = $this->getTopTutorFloors();
			$data['topSleep'] = $this->getTopTutorSleep();
			$this->loadPage($data);
		}else{
			redirect(base_url().'login');
		}

	}

	private function loadPage($data){
		$data['active'] = 3;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$this->load->view('templates/header', $data);
		$this->load->view('leaderboard', $data);
		$this->load->view('templates/footer');
	}

	private function getTopStudent(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, user.points AS total_points
				FROM user
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0
				ORDER BY total_points DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	private function getTopTutors(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, user.points AS total_points
				FROM user
				WHERE user.phantom=0 AND user.staff=1
				ORDER BY total_points DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	private function getTopStudentSteps(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, sum(activity.steps) AS total_steps
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0
				GROUP BY user.id
				ORDER BY total_steps DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}

	}

	private function getTopTutorSteps(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, sum(activity.steps) AS total_steps
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				WHERE user.phantom=0 AND user.staff=1
				GROUP BY user.id
				ORDER BY total_steps DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	private function getTopStudentFloors(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, sum(activity.floors) AS total_floors
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0
				GROUP BY user.id
				ORDER BY total_floors DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	private function getTopTutorFloors(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, sum(activity.floors) AS total_floors
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				WHERE user.phantom=0 AND user.staff=1
				GROUP BY user.id
				ORDER BY total_floors DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}		
	}

	private function getTopStudentSleep(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, AVG(sleep.efficiency) AS avg_sleep
				FROM user
				INNER JOIN sleep
				ON user.id=sleep.user_id
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0 AND sleep.efficiency>0
				GROUP BY user.id
				ORDER BY avg_sleep DESC
				LIMIT 0, 10";	

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}


	private function getTopTutorSleep(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, AVG(sleep.efficiency) AS avg_sleep
				FROM user
				INNER JOIN sleep
				ON user.id=sleep.user_id
				WHERE user.phantom=0 AND user.staff=1 AND sleep.efficiency>0
				GROUP BY user.id
				ORDER BY avg_sleep DESC
				LIMIT 0, 10";	

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}		
	}


	private function getTopHouse(){
		$sql = "SELECT house.name AS house, sum(user.points) AS total_points
				FROM user
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0
				GROUP BY house.id
				ORDER BY total_points DESC";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}



}