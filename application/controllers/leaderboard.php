<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard extends CI_Controller {
	const female = 'FEMALE';
 	const male = 'MALE';
 	const all = 'all';
 	const tutor = 'tutor';

	public function index(){
		if($this->session->userdata('user_id')){
			$this->overall();
		}else{
			redirect(base_url().'login');
		}
	}

	public function overall(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "overall";
			$data['leader'] = $this->getOverallTop(Leaderboard::all);
			$data['female'] = $this->getOverallTop(Leaderboard::female);
			$data['male'] = $this->getOverallTop(Leaderboard::male);
			$this->loadPage($data);
		}else{
			redirect(base_url().'login');
		}
	}

	public function steps(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "steps";
			$data['leader'] = $this->getStepsTop(Leaderboard::all);
			$data['female'] = $this->getStepsTop(Leaderboard::female);
			$data['male'] = $this->getStepsTop(Leaderboard::male);
			$this->loadPage($data);
		}else{
			redirect(base_url().'login');
		}
	}

	public function floors(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "floors";
			$data['leader'] = $this->getFloorsTop(Leaderboard::all);
			$data['female'] = $this->getFloorsTop(Leaderboard::female);
			$data['male'] = $this->getFloorsTop(Leaderboard::male);
		
			$this->loadPage($data);
		}else{
			redirect(base_url().'login');
		}
	}

	public function sleep(){
		if($this->session->userdata('user_id')){
			$data['currentTab'] = "student";
			$data['leader'] = $this->getSleepTop(Leaderboard::all);
			$data['female'] = $this->getSleepTop(Leaderboard::female);
			$data['male'] = $this->getSleepTop(Leaderboard::male);

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
		$this->load->model('User_model','userModel');
		$data['notifications'] = $this->userModel->getNotifications($this->session->userdata("user_id"));
		$this->load->view('templates/header', $data);
		$this->load->view('leaderboard', $data);
		$this->load->view('templates/footer');
	}

	private function getOverallTop($type){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, user.points AS score
				FROM user
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0";
		if ($type == Leaderboard::female) {
			$sql .= " AND user.gender='".Leaderboard::female."'";
		} elseif ($type == Leaderboard::male) {
			$sql .= " AND user.gender='".Leaderboard::male."'";
		}
		
		$sql .= " ORDER BY score DESC LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	

	private function getStepsTop($type){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, sum(activity.steps) AS score
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0";
		if ($type == Leaderboard::female) {
			$sql .= " AND user.gender='".Leaderboard::female."'";
		} elseif ($type == Leaderboard::male) {
			$sql .= " AND user.gender='".Leaderboard::male."'";
		}
		$sql .=" GROUP BY user.id ORDER BY score DESC LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}

	}

	private function getFloorsTop($type){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, sum(activity.floors) AS score
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0";
		if ($type == Leaderboard::female) {
			$sql .= " AND user.gender='".Leaderboard::female."'";
		} elseif ($type == Leaderboard::male) {
			$sql .= " AND user.gender='".Leaderboard::male."'";
		}		
		$sql .=" GROUP BY user.id ORDER BY score DESC LIMIT 0, 10";
		

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	private function getSleepTop($type){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, house.name AS house, AVG(sleep.efficiency) AS score
				FROM user
				INNER JOIN sleep
				ON user.id=sleep.user_id
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0 AND sleep.efficiency>0";
		if ($type == Leaderboard::female) {
			$sql .= " AND user.gender='".Leaderboard::female."'";
		} elseif ($type == Leaderboard::male) {
			$sql .= " AND user.gender='".Leaderboard::male."'";
		}
		$sql .=" GROUP BY user.id ORDER BY score DESC LIMIT 0, 10";


		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	private function getTopTutors(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, user.points AS score
				FROM user
				WHERE user.phantom=0 AND user.staff=1
				ORDER BY score DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}


	

	private function getTopTutorSteps(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, sum(activity.steps) AS score
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				WHERE user.phantom=0 AND user.staff=1
				GROUP BY user.id
				ORDER BY score DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}
	private function getTopTutorFloors(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, sum(activity.floors) AS score
				FROM user
				INNER JOIN activity
				ON user.id=activity.user_id
				WHERE user.phantom=0 AND user.staff=1
				GROUP BY user.id
				ORDER BY score DESC
				LIMIT 0, 10";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}		
	}

	


	private function getTopTutorSleep(){
		$sql = "SELECT user.first_name AS firstname, user.last_name AS lastname, user.profile_pic AS avatar, AVG(sleep.efficiency) AS score
				FROM user
				INNER JOIN sleep
				ON user.id=sleep.user_id
				WHERE user.phantom=0 AND user.staff=1 AND sleep.efficiency>0
				GROUP BY user.id
				ORDER BY score DESC
				LIMIT 0, 10";	

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}		
	}


	private function getTopHouse(){
		$sql = "SELECT house.name AS house, sum(user.points) AS score
				FROM user
				INNER JOIN house
				ON user.house_id = house.id
				WHERE user.phantom=0 AND user.staff=0
				GROUP BY house.id
				ORDER BY score DESC";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}



}