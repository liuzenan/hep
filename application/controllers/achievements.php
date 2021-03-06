<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Achievements extends MY_Controller
{

    public function index()
    {

        $user_id = $this->session->userdata('user_id');
        $this->daily($user_id);

    }

    public function daily($user_id)
    {

        $this->load->model('Badge_model', 'badgeModel');
        $data['badges'] = $this->badgeModel->getBadges($user_id);
        $data['achievements'] = $data['badges'];
        $data['currentTab'] = 'daily';
        $this->loadPage($data);
    }


    private function loadPage($data)
    {
        $data['active'] = 2;
        $data['displayName'] = $this->session->userdata('name');
        $data['avatar'] = $this->session->userdata('avatar');
        $data['isAdmin'] = $this->session->userdata('isadmin');
        $data['isLeader'] = $this->session->userdata('isleader');
        //echo print_r($data['badges']);
        $this->load->model('User_model', 'userModel');
        $data['notifications'] = $this->userModel->getNotifications($this->uid);
        $this->load->view('templates/header', $data);
        $this->load->view('achievements', $data);
        $this->load->view('templates/footer');
    }

    private function getBadges()
    {
        $user_id = $this->uid;
        $sql = "SELECT achievement.id as achi_id, count(userachievement.achievement_id) as num_times
		FROM achievement
		INNER JOIN userachievement
		ON achievement.id=userachievement.achievement_id
		AND userachievement.user_id=" . $user_id . "
		GROUP BY userachievement.achievement_id";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $mybadge = $query->result();
        }

        $query = $this->db->query("SELECT * FROM achievement");
        $resultSet = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $currentBadge = array();
                $currentBadge['times'] = 0;
                $currentBadge['id'] = $row->id;
                $currentBadge['name'] = $row->name;
                $currentBadge['description'] = $row->description;
                $currentBadge['badge_pic'] = $row->badge_pic;
                try {
                    if (isset($mybadge) && sizeof($mybadge) > 0) {
                        foreach ($mybadge as $badge) {
                            if (!strcmp($currentBadge['id'], $badge->achi_id)) {
                                $currentBadge['times'] = $badge->num_times;
                            }

                        }
                    }

                } catch (Exception $e) {

                }


                array_push($resultSet, $currentBadge);
            }
        }

        return $resultSet;
    }
}