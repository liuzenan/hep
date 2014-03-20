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

        $me = $this->User_model->loadUser($this->uid);
        
        $data['my_house'] = $me->house_id;

        $stepsLeaderboard = $this->Challenge_model->getWeeklyLeaderboardbySteps(false, $this->session->userdata('isTutor'));
        
        $n = 0;
        $prev_min = INF;
        $prev_house = null;
        $next_max = INF;
        $next_house = null;
        $my_house = null;
        foreach($stepsLeaderboard as $row) {
            if ($my_house && $row->steps < $next_max) {
                $next_max = $row->steps;
                $next_house = $row;
                break;
            }
            if ($row->house_id === $me->house_id) {
                $my_house = $row;
            }
            if (!$my_house && $row->steps < $prev_min) {
                $prev_min = $row->steps;
                $prev_house = $row;
            }

        }

        $data['stepsLeaderboard'] = array();
        if (count($stepsLeaderboard) > 0) {
            $data['stepsLeaderboard'][] = $stepsLeaderboard[0];
        } else {
            $data['noStepsData'] = true;
        }

        if ($prev_house && !in_array($prev_house, $data['stepsLeaderboard'])) {
            $data['stepsLeaderboard'][] = $prev_house;
        }
        if ($prev_house) {
            $data['stepsPrevHouse'] = $prev_house;
        }
        if ($my_house && !in_array($my_house, $data['stepsLeaderboard'])) {
            $data['stepsLeaderboard'][] = $my_house;
            $data['stepsTopHouse'] = $stepsLeaderboard[0];
        }
        if ($next_house && !in_array($next_house, $data['stepsLeaderboard'])) {
            $data['stepsLeaderboard'][] = $next_house;
        }

        $sleepLeaderboard = $this->Challenge_model->getWeeklyLeaderboardbySleep(false, $this->session->userdata('isTutor'));

        $n = 0;
        $prev_min = INF;
        $prev_house = null;
        $next_max = INF;
        $next_house = null;
        $my_house = null;
        foreach($sleepLeaderboard as $row) {
            if ($my_house && $row->sleep < $next_max) {
                $next_max = $row->sleep;
                $next_house = $row;
                break;
            }
            if ($row->house_id === $me->house_id) {
                $my_house = $row;
            }
            if (!$my_house && $row->sleep < $prev_min) {
                $prev_min = $row->sleep;
                $prev_house = $row;
            }

        }

        $data['sleepLeaderboard'] = array();
        if (count($sleepLeaderboard) > 0) {
            $data['sleepLeaderboard'][] = $sleepLeaderboard[0];
        } else {
            $data['noSleepData'] = true;
        }

        if ($prev_house && !in_array($prev_house, $data['sleepLeaderboard'])) {
            $data['sleepLeaderboard'][] = $prev_house;
        }
        if ($prev_house) {
            $data['sleepPrevHouse'] = $prev_house;
        }
        if ($my_house && !in_array($my_house, $data['sleepLeaderboard'])) {
            $data['sleepLeaderboard'][] = $my_house;
            $data['sleepTopHouse'] = $sleepLeaderboard[0];

        }
        if ($next_house && !in_array($next_house, $data['sleepLeaderboard'])) {
            $data['sleepLeaderboard'][] = $next_house;
        }

        $data['leaderboardHeight'] = max(count($data['stepsLeaderboard']), count($data['sleepLeaderboard'])) * 60 + 40;

        $this->loadActivityData($this->uid, $data);
        $timestr .= microtime() . "<br>";
        $data['me_today'] = $this->Activity_model->getActivityToday($this->uid);
        $data['today'] = $this->Activity_model->getLastUpdate($this->uid);

        $timestr .= microtime() . "<br>";

        $data['me_yesterday'] = $this->Activity_model->getActivityYesterday($this->uid);
        $timestr .= microtime() . "<br>";

        $data['me_today']->sleep = $this->Activity_model->getSleepData($this->uid, date("Y-m-d ", time()))->time_asleep;
        $data['me_today']->sleep = number_format($data['me_today']->sleep / 60, 2);
        $timestr .= microtime() . "<br>";

        $data['me_sleep_yesterday'] = $this->Activity_model->getSleepData($this->uid, date("Y-m-d ", time() - 60 * 60 * 24));
        $data['me_yesterday']->sleep = $data['me_sleep_yesterday']->time_asleep;
        $data['me_yesterday']->sleep = number_format($data['me_yesterday']->sleep / 60, 2);

        $timestr .= microtime() . "<br>";

        $data['delta_steps'] = number_format($this->cauculateDelta($data['me_today']->steps, $data['me_yesterday']->steps), 2);
        $data['delta_calories'] = number_format($this->cauculateDelta($data['me_today']->calories, $data['me_yesterday']->calories), 2);
        $data['delta_distance'] = number_format($this->cauculateDelta($data['me_today']->distance, $data['me_yesterday']->distance), 2);
        $data['delta_sleep'] = number_format($this->cauculateDelta($data['me_today']->sleep, $data['me_yesterday']->sleep), 2);
        $timestr .= microtime() . "<br>";
        
        $timestr .= microtime() . "<br>";

        $data['me_badges'] = $this->Badge_model->getBadges($this->uid);
        $timestr .= microtime() . "<br>";

        $data['me_completed'] = $this->Challenge_model->getIndividualChallengeCount($this->uid);
        $timestr .= microtime() . "<br>";

        
        $timestr .= microtime() . "<br>";

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