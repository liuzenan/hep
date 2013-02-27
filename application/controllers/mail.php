<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {


	function __construct(){
		parent::__construct();
		$this->load->model("Mail_model");
	}

	public function dailyReport() {
		$this->Challenge_model->carryOverToToday();
		$this->Challenge_model->carryOverToTomorrow();
		$this->updateBadge();
		$uids = $this->User_model->loadDailyReportUsers();
		foreach($uids as $uid) {
			$this->Mail_model->sendDailyReport($uid->id);
		}

	}

	public function preAllocateChallenges() {
		$sql = "select id from user where house_id>0 and phantom=0";
		$query = $this->db->query($sql);
		foreach($query->result() as $row) {
			$this->Challenge_model->preAllocateChallenge($row->id,"2013-02-28", "2013-03-01");
		}

	}

	public function updateBadge() {
		$this->Badge_model->scanBadge();
	}

	public function sendNewBadgeEarnedNotification($user_id, $badge_id) {
		echo $this->Mail_model->sendBadgeEarnedMessage($user_id, $badge_id);
	}

	public function sendDailyReport($user_id) {
		echo $this->Mail_model->sendDailyReport($user_id);

	}

	public function unsubBadgeNotification($user_id) {
		$this->User_model->unsubBadgeNotification($user_id);
		echo "You have been sucessfully unsubscribed from badge notification";
	}	

	public function unsubChallengeNotification($user_id) {
		$this->User_model->unsubChallengeNotification($user_id);
		echo "You have been sucessfully unsubscribed from challenge notification"; 
	}

	public function unsubDailyNotification($user_id) {
		$this->User_model->unsubDailyNotification($user_id);
		echo "You have been sucessfully unsubscribed from daily notification";
	}


}