<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends Admin_Controller
{

    public function index()
    {
        $this->session->set_userdata(array('stats_uid' => $this->uid));
        $this->history('steps', 'week');
    }

    public function statistics($uid = NULL)
    {
        $uid = $this->session->userdata('stats_uid');
        $data['stats'] = $this->getStats($uid);
        $data['currentTab'] = "statistics";
        $data['stats_uid'] = $uid;
        $this->loadPage($data);

    }


    public function history($type = NULL, $span = NULL, $uid = NULL)
    {
        if (!is_null($uid)) {
            $this->session->set_userdata(array('stats_uid' => $uid));
        }
        if (!is_null($span)) {
            $this->session->set_userdata(array('span' => $span));
        }
        if (!is_null($type)) {
            $this->session->set_userdata(array('type' => $type));
        }
        $span = $this->session->userdata('span');
        $type = $this->session->userdata('type');
        $uid = $this->session->userdata('stats_uid');
        //echo $type." ".$span." ".$uid;

        $data['chartTitle'] = $type;
        $currentDate = date('Y-m-d', strtotime($this->date_today));
        if ($span == "week") {
            $weekBegin = date('Y-m-d', strtotime($currentDate) - 604800);
        } else {
            $weekBegin = date('Y-m-d', strtotime($currentDate) - 604800 * 4);
        }

        $data['startDate'] = $weekBegin;
        $data['currentActivity'] = $this->getActivity($type, $weekBegin, $currentDate, $uid);
        $data['activeActivity'] = $type;
        $data['currentTab'] = "history";
        $data['span'] = $span;
        $data['stats_uid'] = $uid;


        $this->loadPage($data);
    }

    private function loadPage($data)
    {
        $data['stats_user'] = $this->User_model->loadUser($data['stats_uid']);
        $data['active'] = ($data['stats_uid'] == $this->uid) ? 'stats' : 'none';
        $data['displayName'] = $this->session->userdata('name');
        $data['avatar'] = $this->session->userdata('avatar');
        $data['isAdmin'] = $this->session->userdata('isadmin');
        $data['isLeader'] = $this->session->userdata('isleader');
        $data['isTutor'] = $this->session->userdata('isTutor');
        $data['notifications'] = $this->User_model->getNotifications($this->uid);
        $this->load->view("templates/header", $data);
        $this->load->view("stats", $data);
        $this->load->view("templates/footer");
    }

    public function data()
    {
        $currentDate = date('Y-m-d', strtotime($this->date_today));
        var_dump($this->Activity_model->get_activity(date('Y-m-d', strtotime($currentDate) - 604800), $currentDate, $this->uid));
    }

    private function getActivity($type, $startDate, $endDate, $uid)
    {

        $data = $this->Activity_model->get_activity($startDate, $endDate, $uid);

        if ($data) {
            switch ($type) {
                case 'steps':
                    # code...
                    $activity = $data['steps'];
                    break;
                case 'calories':
                    # code...
                    $activity = $data['calories'];
                    break;

                case 'distance':
                    # code...
                    $activity = $data['distance'];
                    break;

                case 'activity_calories':
                    # code...
                    $activity = $data['activity_calories'];
                    break;
                case 'elevation':
                    # code...
                    $activity = $data['elevation'];
                    break;
                case 'sleep':
                    $activity = $data['sleep'];
                    break;
                case 'sedentary':
                    $activity = $data['sedentary'];
                    break;
                default:
                    # code..
                    break;
            }


            return $activity;
        }
    }

    private function getStats($uid)
    {

        $user = $this->User_model->loadUser($uid);

        if ($user->oauth_token && $user->oauth_secret) {
            $this->fitbitphp->setOAuthDetails($user->oauth_token, $user->oauth_secret);
            $xml = $this->fitbitphp->getActivityStats();
            $best = $xml->best->tracker;
            $lifetime = $xml->lifetime->tracker;

            $stats = array();
            $sleep = $this->Activity_model->getBestSleepData($uid);
            $sleep_time = $sleep->time;
            $sleep_date = $sleep->date;
            if (empty($sleep_time)) {
                $sleep_time = 0;
                $sleep_date = "N.A.";
            } else {
                $sleep_time = $sleep_time / 60;
            }

            if (!empty($best)) {
                $stats['best'] = array(
                    'Calories' => array(
                        'date' => (string)$best->caloriesOut->date,
                        'value' => (string)$best->caloriesOut->value . " Calories"
                    ),
                    'Distance' => array(
                        'date' => (string)$best->distance->date,
                        'value' => (string)number_format((double)$best->distance->value, 2) . " Km"
                    ),
                    'Steps' => array(
                        'date' => (string)$best->steps->date,
                        'value' => (string)$best->steps->value . " Steps"
                    ),
                    'Sleep' => array(
                        'date' => (string)$sleep_date,
                        'value' => (string)number_format($sleep_time, 2) . " Hours"
                    ),
                );
            } else {
                $stats['best'] = array();
            }
            //var_dump($lifetime);
            $hepLifeTime = $this->Activity_model->getLifetimeActivityData($uid);
            if (!empty($lifetime)) {
                $stats['lifetime'] = array(
                    'Calories' => (string)$lifetime->caloriesOut . " Calories",
                    'Distance' => (string)number_format((double)$lifetime->distance, 2) . " Km",
                    'Steps' => (string)$lifetime->steps . " Steps",
                    'Sleep' => (string)number_format($this->Activity_model->getLifetimeSleepData($uid) / 60, 2) . " Hours"
                );
            }
            return $stats;
        } else {
            echo "oauth error";
        }
    }
}