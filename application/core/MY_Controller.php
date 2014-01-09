<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sha
 * Date: 22/9/13
 * Time: 10:52 PM
 * To change this template use File | Settings | File Templates.
 */

class My_Controller extends CI_Controller {
    protected $uid;
    protected  $now;
    protected  $today_start;
    protected  $today_end;
    protected  $tomorrow_start;
    protected  $tomorrow_end;
    protected  $date_today;
    protected  $date_tomorrow;
    protected  $disabled;
    protected  $data;

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('user_id')) {
            redirect(base_url() . "login");
        } else {

            //validate
            $this->load->model('User_model');
            $this->load->model('Activity_model');
            $this->load->model('Challenge_model');
            $this->load->model('Badge_model');

            $validUser = $this->User_model->validateUser($this->session->userdata('user_id'));
            $validInfo = $this->User_model->validateUserInfo($this->session->userdata('user_id'));
            if (!$validUser) {
                redirect(base_url() . 'logout');
            } else if (!empty($valid)) {
                redirect(base_url() . "signup");
            } else if (! $this->User_model->hasAccess($this->session->userdata('user_id'))) {
                redirect(base_url() . 'welcome/thankyou');
            } else {
                $this->uid = $this->session->userdata('user_id');
                $this->load->library('ga_api');

                $this->tomorrow_start = date("Y-m-d", time() + 60 * 60 * 24) . " 00:00:00";
                $this->tomorrow_end = date("Y-m-d", time() + 60 * 60 * 24) . " 23:59:59";
                $this->today_start = date("Y-m-d") . " 00:00:00";
                $this->today_end = date("Y-m-d") . " 23:59:59";
                $this->now = date("Y-m-d G:i:s", time());
                $this->date_today = date("Y-m-d", time());
                $this->date_tomorrow = date("Y-m-d", time() + 60 * 60 * 24);

                $this->isDemo = true;

                if ($this->isDemo) {
                    $this->date_today = "2013-03-10";
                    $this->date_tomorrow = "2013-03-11";
                    $this->tomorrow_start = "2013-03-11 00:00:00";
                    $this->tomorrow_end = "2013-03-11 23:59:59";
                    $this->today_start = "2013-03-10 00:00:00";
                    $this->today_end = "2013-03-10 23:59:59";
                }

                $this->loadUser($data);
            }
        }
    }

    protected function loadUser(&$data) {
        if(!$this->session->userdata('displayName')) {
            $user = $this->User_model->loadUser($this->uid);
            //TODO check where name comes from
            if ($this->session->userdata('name')) {
                $displayName = $this->session->userdata('name');
            } else {
                $displayName = $user->first_name . ' ' . $user->last_name;
            }

            $this->session->set_userdata('displayName', $displayName);
            $this->session->set_userdata('username', $user->username);
            $this->session->set_userdata('fitbit_id', $user->fitbit_id);
            $this->session->set_userdata('gender', $user->gender);
            $this->session->set_userdata('avatar', $user->profile_pic);
            $this->session->set_userdata('isTutor', $user->staff);
            $this->session->set_userdata('isPhantom', $user->phantom);
            $this->session->set_userdata('isLeader', $user->leader);
            $this->session->set_userdata('isAdmin', $user->admin);
        }



        $data['avatar'] = $this->session->userdata('avatar');
        $data['fitbit_id'] = $this->session->userdata('fitbit_id');
        $data['gender'] = $this->session->userdata('gender');
        $data['displayName'] = $this->session->userdata('displayName');
        $data['isLeader'] = $this->session->userdata('isLeader');
        $data['isTutor'] = $this->session->userdata('isTutor');
        $data['isPhantom'] = $this->session->userdata('isPhantom');
        $data['isAdmin'] = $this->session->userdata('isAdmin');
        $data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));
    }

}