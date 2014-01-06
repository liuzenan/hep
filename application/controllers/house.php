<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class House extends MY_Controller
{

    public function index()
    {
       $this->loadPage($this->loadData());
    }

    private function loadData()
    {
        $user = $this->User_model->loadUser($this->uid);

        $members = $this->User_model->loadGroupMemberAvatar($user->house_id);
        $badges = $this->Badge_model->getHouseBadges($user->house_id);
        $sleep = $this->Challenge_model->getHouseSleepStats($user->house_id);
        $steps = $this->Challenge_model->getHouseStepsStats($user->house_id);

        $data = array();
        foreach ($members as $m) {
            $data['data'][$m->id]['profile'] = $m;
        }
        foreach ($badges as $uid => $b) {
            $data['data'][$uid]['badge'] = $b;
        }
        foreach ($sleep as $row) {
            $data['data'][$row->id]['sleep'] = $row;
        }
        foreach ($steps as $row) {
            $data['data'][$row->id]['steps'] = $row;
        }

        $rank = $this->Challenge_model->getHouseRankAndPoints($user->house_id);
        $data['rank'] = $rank;
        return $data;

    }

    public function data()
    {
        echo "<pre>";
        print_r($this->loadData());
        echo "</pre><br>";

    }

    private function loadPage($data)
    {
        $data['active'] = 'house';
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('house', $data);
        $this->load->view('templates/footer');
    }

}