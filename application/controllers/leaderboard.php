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

		$this->loadPage($this->loadData());
	}

	private function loadData() {
		$data['currentTab'] = "overall";
		$data['leader'] = $this->Challenge_model->getLearderboard();
		return $data;
	}
	public function data() {
		
		//$data['female'] = $this->Challenge_model->getLearderboardByGender(Leaderboard::female);
		//$data['male'] = $this->Challenge_model->getLearderboardByGender(Leaderboard::male);
		//$data['house'] = $this->Challenge_model->getHouseLeaderboard();
		//$data['leader'] = $this->loadData();
		//echo "<pre>"; print_r($data['house']);echo "</pre><br>";

		return $data;
	}

	public function refresh() {
		$sql="SELECT DISTINCT user_id
		FROM   activity
		WHERE  ( steps > 0
			OR floors > 0 )
AND date = ?
UNION
SELECT DISTINCT user_id
FROM   sleep
WHERE  total_time > 0
AND date = ?";
$uids=$this->db->query($sql, array(date("Y-m-d ",time()),date("Y-m-d ",time())))->result();
foreach($uids as $uid) {
	$this->Challenge_model->updateActivityProgress($uid->user_id);
}

}


public function staff(){
	$data['currentTab'] = "staff";
	$data['staff'] = $this->Challenge_model->getTutorLearderboard();
		//var_dump($data);
	$this->loadPage($data);
}

public function house() {
	$data['currentTab'] = "house";
	$data['house'] = $this->Challenge_model->getHouseLeaderboard();

	//	echo "<pre>"; print_r($data);echo "</pre><br>";
	$this->loadPage($data);
}

private function loadPage($data){
	$data['active'] = 'leaderboard';
	$data['displayName'] = $this->session->userdata('name');
	$data['avatar'] = $this->session->userdata('avatar');
	$data['isAdmin'] = $this->session->userdata('isadmin');
	$data['isLeader'] = $this->session->userdata('isleader');
	$data['isTutor'] = $this->session->userdata('isTutor');

	$data['notifications'] = $this->User_model->getNotifications($this->uid);
	$this->load->view('templates/header', $data);
	$this->load->view('leaderboard', $data);
	$this->load->view('templates/footer');
}

}