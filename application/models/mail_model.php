<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends CI_Model{

	//const HepAccount = 'hepnusmedical@gmail.com';
	const HepAccount = 'hep-support@googlegroup.com';
	const HepName = 'Health Enhancement Program';
	const TitleBadgeEarned = 'Congrats for earning %s badge!';
	const TitleDailyReport = 'HEP Daily Report';

	const Header = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	<title>HEP Email</title>
	</head>
	<body>';
	const Footer = '<br><br>Happy exercise!<br>
	HEP Team<br>--<br></body>
	</html>';
	function __construct(){
		parent::__construct();
		$this->load->library('email');
		$config['mailtype'] = 'html';

		$this->email->initialize($config);	
	}

	function sendChallengeCompletionMessage($user, $title, $time) {
		if($user->challenge_email_unsub == 0) {
			$message = "Hello %s, <br><br>
			Congradulation! You just completed the challenge %s at %s.<br><br><br>
			";
			$msg = sprintf($message, $user->first_name." ".$user->last_name, $title, $time);
			$link = sprintf(base_url(). "mail/unsubChallengeNotification/%d", $user->id);

			$msg .= sprintf('If you don\'t want to receive this message any more, <a href="%s">you can unsubscribe</a>.', $link);

			$this->send("HEP Challenge Completed", $msg, $user->email);
		}

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


	private function loadData($user_id, $user) {
		$daily = "Good Morning %s, <br><br>
		Yesterday you walked %d steps, climbed %d floors, slept %d hours and burnt %d calories. You had %d challenges yesterday and you completed %d of them.
		Now you had completed %d challenges in total.<br>

		<br><u>Yesterday's Completed Challenges</u>: <br>
		%s
		<br><br>
		<u>Yesterday's Incomplete Challenges</u>: <br>
		%s
		<br><br>
		<u>Today you have %d challenge to work on</u>:
		<br>%s<br><br>


		";
		$ci =& get_instance();
		$ci->load->model('Activity_model');
		$data;


		$data['me_today'] = $this->Activity_model->getActivityToday($user_id);
		$data['me_yesterday'] = $this->Activity_model->getActivityYesterday($user_id);

		$data['me_sleep'] = $this->Activity_model->getSleepToday($user_id);
		$data['me_sleep_yesterday'] = $this->Activity_model->getSleepYesterday($user_id);


		$data['delta_steps'] = number_format($this->cauculateDelta($data['me_today']->steps, $data['me_yesterday']->steps),2);
		$data['delta_floors'] = number_format($this->cauculateDelta($data['me_today']->floors, $data['me_yesterday']->floors),2);
		$data['delta_calories'] = number_format($this->cauculateDelta($data['me_today']->calories, $data['me_yesterday']->calories),2);
		$data['delta_distance'] = number_format($this->cauculateDelta($data['me_today']->distance, $data['me_yesterday']->distance),2);


		$data['me_challenges'] = $this->Challenge_model->getIndividualCurrentChallenges($user_id);
		$data['me_completed'] = $this->Challenge_model->getIndividualChallengeCount($user_id);
		$time = date("Y-m-d",time() - 60 * 60 * 24);	
		$data['me_challenges_yesterday'] = $this->Challenge_model->loadUserChallenge($user_id, $time);
		$tomorrow = date("Y-m-d",time() + 60 * 60 * 24);	
		$data['me_challenges_tomorrow'] = $this->Challenge_model->loadUserChallenge($user_id, $tomorrow);
		$data['avg_today'] = $this->Activity_model->getAverageActivityToday();
		$data['avg_sleep'] = $this->Activity_model->getAverageSleepToday();
		$data['avg_completed'] = number_format($this->Challenge_model->getAverageChallengeCount(),2);
		$data['max_today'] = $this->Activity_model->getMaxActivityToday();

		$count = 0;
		$titlesY="";
		$titlesF="";
		foreach($data['me_challenges_yesterday'] as $c) {
			if($c->progress >= 1) {
				$count++;
				$titlesY .= '<b>'.$c->title.'</b><br>';
				$titlesY .= '<i>'.$c->description.'</i><br><br>';
			} else {
				$titlesF .= '<b>'.$c->title.'</b><br>';
				$titlesF .= '<i>'.$c->description.'</i><br><br>';
			}
		}
		$titlesX="";
		foreach($data['me_challenges'] as $c) {
			$titlesX .= '<b>'.$c->title.'</b><br>';
			$titlesX .= '<i>'.$c->description.'</i><br><br>';

		}
		$data['msg'] = sprintf($daily, $user->first_name." ".$user->last_name, $data['me_yesterday']->steps, $data['me_yesterday']->floors, number_format($data['me_sleep_yesterday']->total_time,2),
			$data['me_yesterday']->calories, count($data['me_challenges_yesterday']), $count, $data['me_completed'], $titlesY, $titlesF, count($data['me_challenges']), $titlesX);
		return $data;
	}

	private function cauculateDelta($today, $yesterday) {
		if($yesterday == 0) {
			return $today == 0 ? 0 : 1;
		} else {
			return ($today-$yesterday)/($yesterday);
		}
	}
	public function sendDailyReport($user_id) {

		$u = $this->User_model->loadUser($user_id);
		if($u->badge_email_unsub == 0) {
			$data = $this->loadData($user_id, $u);
			//echo "<pre>"; print_r($data);echo "</pre><br>";

			//$content = 
			$link = sprintf(base_url(). "mail/unsubDailyNotification/%d", $user_id);
			$msg = $data['msg'] . sprintf('If you don\'t want to receive this message any more, you can <a href="%s">unsubscribe here</a>.', $link);

			$this->send(Mail_model::TitleDailyReport, $msg, $u->email);

			echo $this->email->print_debugger();
			return $msg;
		}

	}

	private function send($title, $msg, $to) {
		$this->email->from(Mail_model::HepAccount, Mail_model::HepName);
		$this->email->to($to);
		$this->email->subject($title);
		$this->email->message($this->getFullHTML($msg));
		$this->email->send();

		echo $this->email->print_debugger();
	}


}