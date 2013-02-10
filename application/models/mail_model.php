<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends CI_Model{

	const HepAccount = 'hep@comp.nus.edu.sg';
	const HepName = 'Health Extended Program';
	const TitleBadgeEarned = 'Congrats for earning %s badge!';
	const TitleDailyReport = 'HEP Daily Report';

	function __construct(){
		parent::__construct();
		$this->load->library('email');
	}

	function sendBadgeEarnedMessage($user_id, $badge_id) {
		$u = $this->User_model->loadUser($user_id);
		if($u->badge_email_unsub == 0) {
			$badge = $this->Badge_model->getBadge($badge_id);
			if(isset($badge)) {
				print_r($u);
				print_r($badge);

				$this->email->from(Mail_model::HepAccount, Mail_model::HepName);
				$this->email->to($u->email); 
				$this->email->subject(sprintf(Mail_model::TitleBadgeEarned, $badge->name));

				$message = 'Congrats! You\'ve just earned %s ';
				$message .= '<br> %s<br><br>';
				$message .= 'If you don\'t want to receive this message any more, <a href="%s">you can unsubscribe</a>.';

				$link = sprintf(base_url(). "mail/unsubBadgeNotification/%d", $user_id);
				$this->email->message(sprintf($message, $badge->name, $badge->description, $link));	

				$this->email->send();

				echo $this->email->print_debugger();
				return $message;
			}
		}

	}


	public function sendDailyReport($user_id) {

		$u = $this->User_model->loadUser($user_id);
		if($u->badge_email_unsub == 0) {
			$a = $this->Activity_model;
			print_r($u);
			$today 		= $a->getActivityToday($user_id);
			$yesterday  = $a->getActivityYesterday($user_id);
			$todaySleep = $a->getSleepToday($user_id);
			$yesterdaySleep = $a->getSleepYesterday($user_id);

			$sleepAvg 	 = $a->getAverageSleepToday();
			$activityAvg = $a->getAverageActivityToday();

			$msg = "<pre>"; print_r($today); "</pre><br>";
			$msg .= "<pre>"; print_r($yesterday); "</pre><br>";
			$msg .= "<pre>"; print_r($todaySleep); "</pre><br>";
			$msg .= "<pre>"; print_r($yesterdaySleep); "</pre><br>";
			$msg .= "<pre>"; print_r($sleepAvg); "</pre><br>";
			$msg .= "<pre>"; print_r($activityAvg); "</pre><br>";
			$link = sprintf(base_url(). "mail/unsubDailyNotification/%d", $user_id);
			$msg .= sprintf('If you don\'t want to receive this message any more, you can <a href="%s">unsubscribe</a>.', $link);


			$this->email->from(Mail_model::HepAccount, Mail_model::HepName);
			$this->email->to($u->email);
			$this->email->subject(Mail_model::TitleDailyReport);
			$this->email->message($msg);
			$this->email->send();
			
			echo $this->email->print_debugger();
			return $msg;
		}

	}

	
}