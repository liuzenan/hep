<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {
	

	function __construct(){
		parent::__construct();
		$this->load->model("Mail_model");
	}

	public function dailyReport() {
		$this->carryOverChallenges();
		$uids = $this->User_model->loadDailyReportUsers();
		foreach($uids as $uid) {
			$this->Mail_model->sendDailyReport($uid->id);
		}

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
	public function carryOverChallenges() {
		$this->Challenge_model->carryOverChallenges();
	}
}