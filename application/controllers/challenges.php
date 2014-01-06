<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect(base_url() . "login");
        } else {
            $this->uid = $this->session->userdata('user_id');
            $this->tomorrow_start = date("Y-m-d", time() + 60 * 60 * 24) . " 00:00:00";
            $this->tomorrow_end = date("Y-m-d", time() + 60 * 60 * 24) . " 23:59:59";
            $this->today_start = date("Y-m-d") . " 00:00:00";
            $this->today_end = date("Y-m-d") . " 23:59:59";
            $this->now = date("Y-m-d G:i:s", time());
            $this->date_today = date("Y-m-d", time());
            $this->date_tomorrow = date("Y-m-d", time() + 60 * 60 * 24);
            /*
                        $this->disabled = !( $this->session->userdata('isadmin')
                            || $this->session->userdata('isleader')
                            || $this->session->userdata('isTutor'));

                        if($this->disabled) {
                            $this->date_today="2013-02-28";
                                $this->date_tomorrow="2013-03-01";
                                $this->tomorrow_start = "2013-03-01 00:00:00";
                                $this->tomorrow_end = "2013-03-01 23:59:59";
                                $this->today_start = "2013-02-28 00:00:00";
                                $this->today_end = "2013-02-28 23:59:59";
                            $this->now = $this->today_start;
                        }
            */
        }
    }

    public function index()
    {

        $this->completed();
    }

    public function all()
    {
        $data["tab"] = "all";

        $data["challenges"] = $this->Challenge_model->loadAvailableChallanges($this->uid, $this->date_today, $this->date_tomorrow);

        $this->loadPage($data);

    }

    public function history()
    {
        $data["tab"] = "history";
        $data["history"] = $this->Challenge_model->loadChallengeHistory($this->uid);
        $this->loadPage($data);
    }


    public function data()
    {
        $data["history"] = $this->Challenge_model->loadChallengeHistory($this->uid);

        $data["challenges"] = $this->Challenge_model->loadUserChallenge($this->uid, $this->date_today);
        $tomorrow = $this->Challenge_model->loadUserChallenge($this->uid, $this->date_tomorrow);

        foreach ($data["challenges"] as $c) {
            $data["today"][$c->category] = $c;
        }
        foreach ($tomorrow as $c2) {
            $data["tomorrow"][$c2->category] = $c2;
        }

        $all = $this->Challenge_model->loadAvailableChallanges($this->uid, $this->date_today, $this->date_tomorrow);
        foreach ($all as $c3) {
            $data['all'][$c3->category][] = $c3;
        }

        echo "<pre>";
        print_r($data);
        echo "</pre><br>";
    }


    public function completed()
    {
        $data["tab"] = "completed";
        $data["challenges"] = $this->Challenge_model->getIndividualCompletedChallenges($this->uid);
        $this->loadPage($data);

    }

    private function validateChallengeAvilability($new, $uid, $date)
    {
        $current = $this->Challenge_model->loadUserChallenge($uid, $date);
        if (empty($current) && $new->challenge_id > 0 && $uid > 0) {
            return "";
        } else {
            $count = 0;
            // check fix category
            foreach ($current as $now) {
                if ($now->category == $new->category) {
                    $msg = "You can join only one challenge in %s category per day. Please drop %s to join this one.";
                    $category = "";
                    switch ($now->category) {
                        case STEPS_CHALLENGE:
                            $category = "Steps";
                            break;
                        case SLEEP_CHALLENGE:
                            $category = "Sleeping";
                            break;
                        case TIMED_CHALLENGE:
                            $category = "Time Based";
                            break;
                    }
                    return sprintf($msg, $category, $now->title);
                }
            }
            // check max num of times joinable
            $count = $this->Challenge_model->getParticipationCount($uid, $new->id);
            if ($count >= $new->quota) {
                return "You have exceeded the maximum quota of this challenge. Please choose another one.";
            }
            return "";
        }

    }

    private function loadPage($data, $type = "challenges")
    {
        $data['active'] = 'challenges';
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('challenges', $data);
        $this->load->view('templates/footer');
    }

}