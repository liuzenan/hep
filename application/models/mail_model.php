<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends CI_Model{

	const HepAccount = 'hep@comp.nus.edu.sg';
	const HepName = 'Health Extended Program';
	const TitleBadgeEarned = 'Congrats for earning %s badge!';
	const TitleDailyReport = 'HEP Daily Report';

	const Header = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	<title>HEP Email</title>
	</head>
	<body>';
	const Footer = '</body>
	</html>';
	function __construct(){
		parent::__construct();
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$this->email->initialize($config);	
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
				$this->email->message($this->getFullHTML(sprintf($message, $badge->name, $badge->description, $link)));	

				$this->email->send();

				echo $this->email->print_debugger();
				return $message;
			}
		}

	}

	private function getFullHTML($message) {
		return Mail_model::Header . $message . Mail_model::Footer;
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
			echo "<pre>" . print_r($u) . "</pre><br>";
			echo "<pre>" . print_r($today) . "</pre><br>";
			echo "<pre>" . print_r($yesterday) . "</pre><br>";
			echo "<pre>" . print_r($todaySleep) . "</pre><br>";
			echo "<pre>" . print_r($yesterdaySleep) . "</pre><br>";
			echo "<pre>" . print_r($sleepAvg) . "</pre><br>";
			$msg .= "<pre>" . print_r($activityAvg) ."</pre><br>";

			//$content = 
			$link = sprintf(base_url(). "mail/unsubDailyNotification/%d", $user_id);
			$msg .= sprintf('If you don\'t want to receive this message any more, you can <a href="%s">unsubscribe</a>.', $link);


			$this->email->from(Mail_model::HepAccount, Mail_model::HepName);
			$this->email->to($u->email);
			$this->email->subject(Mail_model::TitleDailyReport);
			$this->email->message($this->getFullHTML($msg));
			$this->email->send();
			
			echo $this->email->print_debugger();
			return $msg;
		}

	}

	
}