<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mail_model extends My_Model
{
    //const HepAccount = 'hepnusmedical@gmail.com';

    const HepAccount = 'hep-support@googlegroups.com';
    const HepName = 'Health Enhancement Programme';
    const TitleBadgeEarned = 'Congrats for earning %s badge!';
    const TitleDailyReport = 'HEP Daily Report';
    const TitleInvitation = 'HEP Registration Reminder';

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
        $this->config->load('mail');
        $config = $this->config->item('mailconfig');
        $this->load->library('email', $config);
    }

    public function sendAnnouncement($emails, $title, $msg)
    {
        foreach ($emails as $bccs) {
            $this->sendMessage($title, nl2br($msg), $bccs);
        }
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
		Yesterday, you walked <b>%d steps (%s)</b>, 
		slept <b>%s hours (%s)</b> and burnt <b>%d calories (%s)</b>.<br/><br/> 

        %s

        %s

		Because of your contribution, we now have in total <b>%sK</b> steps, <b>%s kilometers</b> of movement, <b>%sK hours</b> of sleep recorded with the system.  <br>
		That's awesome! Thanks! :)

        <br/><br/>
        Health Enhancement Programme
		<br><br>
        <small><a href=\"%s\">Click here</a> to unsubscribe from daily reports.</small>";
        $ci =& get_instance();
        $ci->load->model('Activity_model');
        $ci->load->model('Badge_model');
        $me = $this->User_model->loadUser($user_id);
        $data = array();

        $day = date( "w", time());
        $house_stats = '';
        if ($day == WEEKLY_TALLY_PROCESS_DAY) {
            $house_id = $me->house_id;
            if ($house_id > 0) {
                $house_info = $this->db->get_where('house', array('id' => $house_id))->row();
                $house_msg = "Last week, your house was ranked #%s on the steps leaderboard earning %s points, and ranked
                #%s on the sleep leaderboard, earning %s points.<br/><br/>";
                $house_stats = sprintf($house_msg, $house_info->last_steps_rank, $house_info->last_steps_score, 
                    $house_info->last_sleep_rank, $house_info->last_sleep_score);
            }
        }

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

        $data['avg_today'] = $this->Activity_model->getAverageActivityToday();
        $data['avg_sleep'] = $this->Activity_model->getAverageSleepToday();
        $data['max_today'] = $this->Activity_model->getMaxActivityToday();
        $data['new_badge'] = $this->Badge_model->getBadgesByDate($user_id, date("Y-m-d ", time() - 60 * 60 * 24 * BADGE_DELAY_DAYS));

        $new_badge = "";
        if (!empty($data['new_badge'])) {
            $badge_date = date("j F, Y ", time() - 60 * 60 * 24 * BADGE_DELAY_DAYS);
            $new_badge .= "<u>Newly earned badges for $badge_date</u>: <br>";

            foreach ($data['new_badge'] as $bd) {
                $new_badge .= '<b>' . $bd->name . '</b><br>';
            }
            $new_badge .= "<br><br>";
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
            $data["emailmsg"] = nl2br($todayMsg).'<br/><br/>';
        }

        $unsub_link = base_url() . 'mail/unsubDailyNotification/' . urlencode($me->email);

        $data['msg'] = sprintf($daily,
            $user->first_name . " " . $user->last_name,
            $data["emailmsg"],
            $data['me_yesterday']->steps,
            ($data['delta_steps'] > 0 ? "up by " : "down by ") . $data['delta_steps'] . "%",

            number_format($data['me_yesterday']->sleep, 2),
            ($data['delta_sleep'] > 0 ? "up by " : "down by ") . $data['delta_sleep'] . "%",

            $data['me_yesterday']->calories,
            ($data['delta_calories'] > 0 ? "up by " : "down by ") . $data['delta_calories'] . "%",

            $new_badge,
            $house_stats,
            $summary->steps,
            $summary->distance,
            $summary->sleep,
            $unsub_link


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

    public function sendInvitation($name, $email, $code) {
        $msg = "Dear " . $name . ", <br/><br/>
        It seems that you either have not signed up with HEP Platform, or your information on the platform is incomplete.  
        We will be starting data collection on Monday 20 Jan 2014, so we would be really grateful if you would register with
        the HEP Platform with your Fitbit account and fill in the relevant information as soon as possible. If we do not have
        everyone registered on time, our data collection would be incomplete and this would have severe impact on the research study. <br/><br/>

        Your HEP registration code is:<br/>
        <p style=\"font-size:x-large\">".$code."</p><br/>

        Please register on the <a href=\"http://hep.d2.comp.nus.edu.sg/\">HEP Platform</a> with
        your Fitbit account and fill in the relevant information as soon as possible.<br/><br/>

        If you have any questions or encounter any issues, please email us at <a href=\"mailto:hep-support@googlegroups.com\">hep-support@googlegroups.com</a>.
        Those of you who encountered a \"your registration code has already been used\" error would be pleased to know that the issue has been resolved.
        <br/><br/>
        Thank you for your cooperation.
        <br/><br/>

        Health Enhancement Programme";

        $this->send(Mail_model::TitleInvitation, $msg, $email);
    }

    public function sendReminder($name, $email, $mode='activities') {
        $msg = "Dear $name, <br/><br/>
        It seems that there have been no recorded $mode on your Fitbit account for the past few days.<br/><br/>

        If you forgot to sync your device with Fitbit, please remember to do so. A daily sync is not required but recommended.<br/>
        Once your tracker is within range of your wireless sync dongle that is connected to a USB port, it will automatically 
        upload any stored data to your account.<br/>
        Alternatively, you can sync your tracker using your <a href=\"http://www.fitbit.com/sg/devices\">smartphones</a>. 
        <br/><br/>
        Without regular activity and sleep data from your tracker, the data collection would be incomplete and this would have negative impact on the
        research study. Remember that tracking and syncing your activity and sleep data regularly also allows you to earn badges and, more importantly,
        contribute to your house! :)
        <br/><br/>

        If you need help on <a href=\"https://help.fitbit.com/customer/portal/topics/79805-syncing-your-data-/articles\">syncing your data</a> or 
        <a href=\"https://help.fitbit.com/customer/portal/articles/176101-how-do-i-track-my-sleep-\">tracking your sleep</a>, information can be found
        on <a href=\"https://help.fitbit.com/#product_flex\">Fitbit Help</a>.
        <br/><br/>
        If you misplaced your Fitbit tracker, or if it is damaged, please inform your house reps and email us at
        <a href=\"mailto:hep-support@googlegroups.com\">hep-support@googlegroups.com</a>.
        <br/><br/>
        Thank you for your cooperation.
        <br/><br/>

        Health Enhancement Programme";
        $title = "HEP - Having trouble syncing?";

        $this->send($title, $msg, $email);
    }

    public function send($title, $msg, $to)
    {
        $this->email->clear();

        $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
        $this->email->to($to);
        $this->email->subject($title);
        $this->email->message($this->getFullHTML($msg));
        //$this->email->message($msg);
        if (! $this->email->send()) {
            // try again
            sleep(1);
            $this->email->clear();

            $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
            $this->email->to($to);
            $this->email->subject($title);
            $this->email->message($this->getFullHTML($msg));

            if (! $this->email->send()) {
                $data = array('message' => 'DeliveryFailed-'.$to.'-'.$title,
                    'content' =>$msg. '_END_OF_MSG_' . $this->email->print_debugger());
                $this->db->insert('log', $data);
            }
        };

        //echo $this->email->print_debugger();
    }

    public function sendMessage($title, $msg, $bccs)
    {
        $this->email->clear();

        $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
        $this->email->bcc($bccs);
        $this->email->subject($title);
        $this->email->message($this->getAnnouncementHTML($msg));
        if (! $this->email->send()) {
            // try again
            sleep(1);
            $this->email->clear();

            $this->email->from(Mail_model::HepAccount, Mail_model::HepName);
            $this->email->bcc($bccs);
            $this->email->subject($title);
            $this->email->message($this->getAnnouncementHTML($msg));

            if (! $this->email->send()) {
                $data = array('message' => 'DeliveryFailed-'.$title,
                    'content' =>implode(', ', $bccs) . '_END_OF_LIST_' . $msg. '_END_OF_MSG_' . $this->email->print_debugger());
                $this->db->insert('log', $data);
            }
        };
        //echo $this->email->print_debugger();
    }


}