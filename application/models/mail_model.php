<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends My_Model
{
    //const HepAccount = 'hepnusmedical@gmail.com';

    const HepAccount = 'hep-support@googlegroups.com';
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

    const Footer = '';
    const AnnouncementHeader = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
	"http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
	<title>HEP Announcement</title>
	</head>
	<body>';
    const AnnouncementFooter = '
	<br></body>
	</html>';

    function __construct()
    {
        parent::__construct();
        $config = Array(
            'protocol' => 'sendmail',
            'mailtype' => 'html',
            'charset' => 'utf-8'
        );
        $this->load->library('email', $config);
    }

    function sendChallengeCompletionMessage($user, $title, $time)
    {
        if ($user->challenge_email_unsub == 0) {
            $message = "Hello %s, <br><br>
			Congrats! You have just completed the challenge <b>%s</b> at %s.<br><br><br>
			";
            $msg = sprintf($message, $user->first_name . " " . $user->last_name, $title, $time);

            $this->send("HEP Challenge Completed", $msg, $user->email);
        }

    }

    public function sendAnnouncement($user_id, $title, $msg)
    {
        $uid = intval($user_id);
        $u = $this->User_model->loadUser($uid);
        $this->sendMessage($title, nl2br($msg), $u->email);
    }

    public function sendBadgeEarnedMessage($user_id, $badge_id)
    {
        $u = $this->User_model->loadUser($user_id);
        if ($u->badge_email_unsub == 0) {
            $badge = $this->Badge_model->getBadge($badge_id);
            if (isset($badge)) {
                print_r($u);
                print_r($badge);

                $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
                $this->email->to($u->email);
                $this->email->subject(sprintf(Mail_model::TitleBadgeEarned, $badge->name));

                $message = 'Congrats! You\'ve just earned %s ';
                $message .= '<br> %s<br><br>';
                $this->email->message($this->getFullHTML(sprintf($message, $badge->name, $badge->description)));

                $this->email->send();

                echo $this->email->print_debugger();
                return $message;
            }
        }

    }

    private function getFullHTML($message)
    {
        return Mail_model::Header . $message . Mail_model::Footer;
    }

    private function getAnnouncementHTML($message)
    {
        return Mail_model::AnnouncementHeader . nl2br($message) . Mail_model::AnnouncementFooter;
    }


    private function loadData($user_id, $user)
    {
        $daily = "Good Morning %s, <br><br>
		%s
		<br><br>
		Yesterday, you walked <b>%d steps (%s)</b>, 
		slept <b>%s hours (%s)</b> and burnt <b>%d calories (%s)</b>. 
		<br><br>You selected <b>%s</b> challenges yesterday and you completed <b>%d</b> of them.
		You have completed <b>%d</b> challenges in total.<br>

		<br><u>Challenges Completed Yesterday</u>: <br>
		%s
		<br><br>
		<u>Yesterday's Challenges Not Completed</u>: <br>
		%s
		<br><br>
		%s
		<u>Today you are working on the following challenges</u>:
		<br>%s<br><br>

		Because of your contribution, we now have in total <b>%dK</b> steps, <b>%d kilometers</b> of movement, <b>%sK hours</b> of sleep recorded with the system.  <br>
		That's awesome! Thanks! :)
		<br><br>";
        $ci =& get_instance();
        $ci->load->model('Activity_model');
        $ci->load->model('Badge_model');
        $data = array();


        //$data['me_today'] = $this->Activity_model->getActivityToday($user_id);
        $data['me_yesterday'] = $this->Activity_model->getActivityYesterday($user_id);
        $data['me_sleep_yesterday'] = $this->Activity_model->getSleepData($user_id, date("Y-m-d ", time() - 60 * 60 * 24));
        //var_dump($data['me_sleep_yesterday']);
        $data['me_yesterday']->sleep = number_format((float)$data['me_sleep_yesterday']->total_time / 60, 2);

        $data['me_two_days_ago'] = $this->Activity_model->getActivityOnDate($user_id, date("Y-m-d ", time() - 2 * 60 * 60 * 24));
        $data['me_sleep_two_days_ago'] = $this->Activity_model->getSleepData($user_id, date("Y-m-d ", time() - 2 * 60 * 60 * 24));
        $data['me_two_days_ago']->sleep = number_format((float)$data['me_sleep_two_days_ago']->total_time / 60, 2);

        $data['delta_steps'] = number_format($this->cauculateDelta($data['me_yesterday']->steps, $data['me_two_days_ago']->steps), 2);
        $data['delta_calories'] = number_format($this->cauculateDelta($data['me_yesterday']->calories, $data['me_two_days_ago']->calories), 2);
        $data['delta_distance'] = number_format($this->cauculateDelta($data['me_yesterday']->distance, $data['me_two_days_ago']->distance), 2);
        $data['delta_sleep'] = number_format($this->cauculateDelta($data['me_yesterday']->sleep, $data['me_two_days_ago']->sleep), 2);

        $data['me_challenges'] = $this->Challenge_model->loadUserChallenge($user_id, date("Y-m-d ", time()));
        $data['me_completed'] = $this->Challenge_model->getIndividualChallengeCount($user_id);
        $time = date("Y-m-d", time() - 60 * 60 * 24);
        $data['me_challenges_yesterday'] = $this->Challenge_model->loadUserChallenge($user_id, $time);
        $tomorrow = date("Y-m-d", time() + 60 * 60 * 24);
        $data['me_challenges_tomorrow'] = $this->Challenge_model->loadUserChallenge($user_id, $tomorrow);
        $data['avg_today'] = $this->Activity_model->getAverageActivityToday();
        $data['avg_sleep'] = $this->Activity_model->getAverageSleepToday();
        $data['avg_completed'] = number_format($this->Challenge_model->getAverageChallengeCount(), 2);
        $data['max_today'] = $this->Activity_model->getMaxActivityToday();
        $data['new_badge'] = $this->Badge_model->getBadgesByDate($user_id, date("Y-m-d ", time() - 60 * 60 * 24));

        $new_badge = "";
        if (!empty($data['new_badge'])) {
            $new_badge .= "<u>Newly earned badges</u>: <br>";

            foreach ($data['new_badge'] as $bd) {
                $new_badge .= '<b>' . $bd->name . '</b><br>';
            }
            $new_badge .= "<br><br>";
        }

        $count = 0;
        $titlesY = "";
        $titlesF = "";
        foreach ($data['me_challenges_yesterday'] as $c) {
            if ($c->progress >= 1) {
                $count++;
                $titlesY .= '<b>' . $c->title . '</b><br>';
                $titlesY .= '<i>' . $c->description . '</i><br><br>';
            } else {
                $titlesF .= '<b>' . $c->title . '</b><br>';
                $titlesF .= '<i>' . $c->description . '</i><br><br>';
            }
        }
        $titlesX = "";
        foreach ($data['me_challenges'] as $c) {
            $titlesX .= '<b>' . $c->title . '</b><br>';
            $titlesX .= '<i>' . $c->description . '</i><br><br>';

        }

        $summary = $this->Activity_model->getActivitySummary();
        $this->load->helper('file');
        $todayDate = date("Y-m-d");
        $ysdDate = date("Y-m-d", time() - 24 * 60 * 60);

        $todayMsg = read_file('./messages/' . $todayDate);
        if (empty($todayMsg)) {
            $todayMsg = read_file('./messages/' . $ysdDate);
        }

        $data["emailmsg"] = "";
        if (!empty($todayMsg)) {
            # code...
            $data["emailmsg"] = nl2br($todayMsg);
        }

        $data['msg'] = sprintf($daily,
            $user->first_name . " " . $user->last_name,
            $data["emailmsg"],
            $data['me_yesterday']->steps,
            ($data['delta_steps'] > 0 ? "up by " : "down by ") . $data['delta_steps'] . "%",

            number_format($data['me_yesterday']->sleep, 2),
            ($data['delta_sleep'] > 0 ? "up by " : "down by ") . $data['delta_sleep'] . "%",


            $data['me_yesterday']->calories,
            ($data['delta_calories'] > 0 ? "up by " : "down by ") . $data['delta_calories'] . "%",


            count($data['me_challenges_yesterday']),
            $count,
            $data['me_completed'],
            $titlesY,
            $titlesF,
            $new_badge,
            //count($data['me_challenges']),
            $titlesX,
            $summary->steps,
            $summary->distance,
            $summary->sleep


        );
        return $data;
    }

    private function cauculateDelta($today, $yesterday)
    {
        if ($yesterday == 0) {
            return $today == 0 ? 0 : 1;
        } else {
            return ($today - $yesterday) / ($yesterday) * 100;
        }
    }

    public function sendDailyReport($user_id)
    {
        echo $user_id;
        $u = $this->User_model->loadUser($user_id);
        if ($u->daily_email_unsub == 0) {
            $data = $this->loadData($user_id, $u);
            $msg = $data['msg'];
            //$this->load->view('dailyemail', $data);

            var_dump($data);
            $this->send(Mail_model::TitleDailyReport, $msg, $u->email);

            echo $this->email->print_debugger();
            return $msg;
        }

    }


    public function send($title, $msg, $to)
    {
        $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
        $this->email->to($to);
        $this->email->subject($title);
        $this->email->message($this->getFullHTML($msg));
        //$this->email->message($msg);
        $this->email->send();

        echo $this->email->print_debugger();
    }

    public function sendMessage($title, $msg, $to)
    {
        $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
        $this->email->to($to);
        $this->email->subject($title);
        $this->email->message($this->getAnnouncementHTML($msg));
        $this->email->send();
        //echo $this->email->print_debugger();
    }


}