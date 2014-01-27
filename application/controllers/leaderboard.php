<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leaderboard extends MY_Controller
{
    const female = 'FEMALE';
    const male = 'MALE';
    const all = 'all';
    const tutor = 'tutor';

    public function index()
    {
        $this->house();
    }

    public function overall()
    {

        $this->loadPage($this->loadData());
    }

    private function loadData()
    {
        $data['currentTab'] = "overall";
        $data['stepsLeader'] = $this->Challenge_model->getLeaderboardbySteps();
        $data['sleepLeader'] = $this->Challenge_model->getLeaderboardbySleep();
        return $data;
    }

    public function data()
    {

        //$data['female'] = $this->Challenge_model->getLearderboardByGender(Leaderboard::female);
        //$data['male'] = $this->Challenge_model->getLearderboardByGender(Leaderboard::male);
        //$data['house'] = $this->Challenge_model->getHouseLeaderboard();
        //$data['leader'] = $this->loadData();
        //echo "<pre>"; print_r($data['house']);echo "</pre><br>";

        return $data;
    }

    public function refresh()
    {
        $sql = "SELECT DISTINCT user_id
		FROM   activity
		WHERE  ( steps > 0
			OR floors > 0 )
AND date = ?
UNION
SELECT DISTINCT user_id
FROM   sleep
WHERE  total_time > 0
AND date = ?";
        $uids = $this->db->query($sql, array(date("Y-m-d ", time()), date("Y-m-d ", time())))->result();
        foreach ($uids as $uid) {
            $this->Challenge_model->updateActivityProgress($uid->user_id);
        }

    }


    public function staff()
    {
        $data['currentTab'] = "staff";
        $data['stepsLeader'] = $this->Challenge_model->getTutorLeaderboardbySteps();
        $data['sleepLeader'] = $this->Challenge_model->getTutorLeaderboardbySleep();
        //var_dump($data);
        $this->loadPage($data);
    }

    public function house()
    {
        $data['currentTab'] = "house";
        $data['house'] = $this->Challenge_model->getHouseLeaderboard();

        //	echo "<pre>"; print_r($data);echo "</pre><br>";
        $this->loadPage($data);
    }

    public function weekly() {
        $data['currentTab'] = "weekly";
        $me = $this->User_model->loadUser($this->uid);
        $data['my_house'] = $me->house_id;

        $data['steps'] = $this->Challenge_model->getWeeklyLeaderboardbySteps();
        
        $data['sleep'] = $this->Challenge_model->getWeeklyLeaderboardbySleep();
        
        $this->loadPage($data);
    }

    public function lastweek() {
        $data['currentTab'] = "lastweek";
        $me = $this->User_model->loadUser($this->uid);
        $data['my_house'] = $me->house_id;

        $data['steps'] = $this->Challenge_model->getWeeklyLeaderboardbySteps(true);
        
        $data['sleep'] = $this->Challenge_model->getWeeklyLeaderboardbySleep(true);
        
        $this->loadPage($data);
    }

    private function loadPage($data)
    {
        $data['active'] = 'leaderboard';
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('leaderboard', $data);
        $this->load->view('templates/footer');
    }

}