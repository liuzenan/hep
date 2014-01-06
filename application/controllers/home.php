<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function index()
    {
        $data = $this->loadData();
        $this->load->view('templates/header', $data);
        $this->load->view('home', $data);
        $this->load->view('templates/footer');

    }

    public function loadData()
    {
        parent::loadUser($data);
        $data['active'] = 'home';
        $timestr = "";

        $this->loadActivityData($this->uid, $data);
        $timestr .= microtime() . "<br>";
        $data['me_today'] = $this->Activity_model->getActivityToday($this->uid);
        $data['today'] = $this->Activity_model->getLastUpdate($this->uid);

        $timestr .= microtime() . "<br>";

        $data['me_yesterday'] = $this->Activity_model->getActivityYesterday($this->uid);
        $timestr .= microtime() . "<br>";

        $data['me_today']->sleep = $this->Activity_model->getSleepData($this->uid, date("Y-m-d ", time()))->total_time;
        $data['me_today']->sleep = number_format($data['me_today']->sleep / 60, 2);
        $timestr .= microtime() . "<br>";

        $data['me_sleep_yesterday'] = $this->Activity_model->getSleepData($this->uid, date("Y-m-d ", time() - 60 * 60 * 24));
        $data['me_yesterday']->sleep = $data['me_sleep_yesterday']->total_time;
        $data['me_yesterday']->sleep = number_format($data['me_yesterday']->sleep / 60, 2);

        $timestr .= microtime() . "<br>";

        $data['delta_steps'] = number_format($this->cauculateDelta($data['me_today']->steps, $data['me_yesterday']->steps), 2);
        $data['delta_calories'] = number_format($this->cauculateDelta($data['me_today']->calories, $data['me_yesterday']->calories), 2);
        $data['delta_distance'] = number_format($this->cauculateDelta($data['me_today']->distance, $data['me_yesterday']->distance), 2);
        $data['delta_sleep'] = number_format($this->cauculateDelta($data['me_today']->sleep, $data['me_yesterday']->sleep), 2);
        $timestr .= microtime() . "<br>";


        $cs1 = $this->Challenge_model->loadUserChallenge($this->uid, $this->date_today);
        foreach ($cs1 as $c1) {
            $data['me_challenges'][$c1->category] = $c1;
        }
        $timestr .= microtime() . "<br>";

        $data['me_badges'] = $this->Badge_model->getBadges($this->uid);
        $timestr .= microtime() . "<br>";

        $data['me_completed'] = $this->Challenge_model->getIndividualChallengeCount($this->uid);
        $timestr .= microtime() . "<br>";

        $cs2 = $this->Challenge_model->loadUserChallenge($this->uid, date("Y-m-d ", time() - 60 * 60 * 24));
        foreach ($cs2 as $c2) {
            $data['me_challenges_yesterday'][$c2->category] = $c2;
        }
        $timestr .= microtime() . "<br>";

        $cs3 = $this->Challenge_model->loadUserChallenge($this->uid, $this->date_tomorrow);
        foreach ($cs3 as $c3) {
            $data['me_challenges_tomorrow'][$c3->category] = $c3;
        }
        $timestr .= microtime() . "<br>";

        $data['avg_today'] = $this->Activity_model->getAverageActivityToday();
        $timestr .= microtime() . "<br>";

        $data['avg_today']->avg_sleep = number_format($this->Activity_model->getAverageSleepToday() / 60, 2);
        $timestr .= microtime() . "<br>";

        $data['avg_completed'] = number_format($this->Challenge_model->getAverageChallengeCount(), 2);
        $timestr .= microtime() . "<br>";

        //$data['max_today'] = $this->Activity_model->getMaxActivityToday();
        $data['max_today'] = new stdClass;
        $data['max_today']->max_steps = max($data['avg_today']->avg_steps, $data['me_today']->steps);
        $data['max_today']->max_distance = max($data['avg_today']->avg_distance, $data['me_today']->distance);
        $data['max_today']->max_calories = max($data['avg_today']->avg_calories, $data['me_today']->calories);
        $data['max_today']->max_sleep = max($data['avg_today']->avg_sleep, $data['me_today']->sleep);

        $data['all_challenge'] = $this->Challenge_model->loadAvailableChallanges($this->uid, $this->date_today, $this->date_tomorrow);
        foreach ($data['all_challenge'] as $c) {
            $data['all'][$c->category][] = $c;
        }
        $timestr .= microtime() . "<br>";

        //$data['profiling'] = $timestr;
        $data['my_points'] = $this->Challenge_model->getTotalPoints($this->uid);
        $data['cohor_points'] = number_format($this->Challenge_model->getAveragePoints(), 1);
        return $data;
    }

    /*
        public function email() {

            $data = $this->loadData();
            $str = $this->load->view('templates/header', $data, true);
            $str .= $this->load->view('home', $data, true);
            $str .= $this->load->view('templates/footer', true);
            $this->Mail_model->send("hello", $str, "wangshasg@gmail.com");
            echo $str;
        }
    */
    public function data()
    {
        echo "<pre>";
        print_r($this->loadData());
        echo "</pre><br>";
    }

    private function cauculateDelta($today, $yesterday)
    {
        if ($yesterday == 0) {
            return $today == 0 ? 0 : 1;
        } else {
            return ($today - $yesterday) / ($yesterday);
        }
    }


    private function loadActivityData($user_id, &$data)
    {
        $activityRow = $this->Activity_model->getActivityToday($user_id);
        $average = $this->Activity_model->getAverageActivityToday();
        $average->avg_sleep = number_format($this->Activity_model->getAverageSleepToday() / 60, 2);
        $data['avg'] = $average;

        $data['activescore'] = $activityRow->active_score;
        $data['calories'] = $activityRow->activity_calories;
        $data['distance'] = $activityRow->distance;
        $data['steps'] = $activityRow->steps;

    }
}