<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends Admin_Controller
{

    public function index()
    {
        $this->house(1);

    }

    public function house($house_id)
    {
        if ($this->session->userdata('isadmin')) {

            $data['students'] = $this->getAllUsers($house_id);
            foreach ($data['students'] as $student) {
                # code...
                $sql = "SELECT * FROM survey WHERE userid = " . $student->id;
                $query = $this->db->query($sql);
                if ($query->num_rows() > 0) {
                    $data['survey'][$student->id] = 1;
                }
            }
            $data['house_id'] = $house_id;
            $this->loadPage($data, "admin");
        } else {
            redirect(base_url() . "home");

        }
    }

    public function user($user_id)
    {
        if ($this->session->userdata('isadmin') && $user_id) {
            $data['user'] = $this->currentUser($user_id);
            $data['invalidperiod'] = $this->User_model->getInvalidPeriod($user_id);
            var_dump($data['invalidperiod']);
            $this->loadPage($data, "manageuser");
            $this->session->set_userdata(array("manage_user_id" => $user_id));
        } else {
            redirect(base_url() . "home");
        }
    }

    public function update()
    {
        if ($this->session->userdata('isadmin')) {
            $firstname = $this->input->post("firstname");
            $lastname = $this->input->post("lastname");
            $email = $this->input->post("email");
            $house = $this->input->post("house");
            $gender = $this->input->post("gender");
            $phantom = $this->input->post("phantom");
            $leader = $this->input->post("leader");

            $badge_email = $this->input->post("badge_email_unsub");
            $daily_email = $this->input->post("daily_email_unsub");
            $challenge_email = $this->input->post("challenge_email_unsub");
            $hide_progress = $this->input->post("hide_progress");
            $admin = $this->input->post("admin");
            $staff = ($house == -1) ? 1 : 0;
            $data = array();

            $data['first_name'] = $firstname;
            $data['last_name'] = $lastname;
            $data['email'] = $email;
            $data['house_id'] = $house;
            $data['staff'] = $staff;
            $data['gender'] = $gender;
            $data['admin'] = empty($phantom) ? 0 : 1;
            $data['phantom'] = empty($phantom) ? 0 : 1;
            $data['leader'] = empty($leader) ? 0 : 1;
            $data['phantom'] = empty($phantom) ? 0 : 1;
            $data['badge_email_unsub'] = empty($badge_email) ? 0 : 1;
            $data['daily_email_unsub'] = empty($daily_email) ? 0 : 1;
            $data['challenge_email_unsub'] = empty($challenge_email) ? 0 : 1;
            $data['hide_progress'] = empty($hide_progress) ? 0 : 1;

            //$data['badge_email_unsub'] = empty($badge_email)?1:0;
            //$data['daily_email_unsub'] = empty($daily_email)?1:0;
            //$data['challenge_email_unsub'] = empty($challenge_email)?1:0;
            //$data['hide_progress'] = empty($hide_progress)?1:0;
            $uid = $this->session->userdata('manage_user_id');

            //$data['house_id'] = $house;
            $this->db->where('id', $uid);
            $this->db->update('user', $data);
        }
        echo json_encode(array('success' => true, 'uid' => $uid));
    }

    public function invalid()
    {

        $sql = "SELECT
		sum(c.points)  AS points,
		count(cp.id) AS challenges,
		u.*
		FROM   user AS u,
		challengeparticipant AS cp,
		challenge as c
		WHERE 
		c.id = cp.challenge_id
		AND cp.user_id = u.id
		AND cp.progress >= 1
		AND cp.inactive = 0
		AND u.staff = 0
		AND u.id IN (select id from user where house_id = 0 or house_id IS NULL or first_name is null or last_name is null or email is null)
		GROUP BY u.id
		ORDER BY sum(c.points) DESC, sum(cp.complete_time-cp.start_time) ASC";

        $query1 = $this->db->query($sql);
        $progress = array();
        foreach ($query1->result() as $row) {
            $progress[$row->id] = $row;
        }

        $query = $this->db->query("select * from user where house_id = 0 or house_id IS NULL or first_name is null or last_name is null or email is null");
        foreach ($query->result() as $row2) {
            if (empty($progress[$row2->id])) {
                $row2->challenges = 0;
                $row2->points = 0;
                $progress[$row2->id] = $row2;
            }
        }

        if ($this->session->userdata('isadmin')) {

            $data['students'] = $progress;
            $data['house_id'] = 0;
            $this->loadPage($data, "admin");
        } else {
            redirect(base_url() . "home");

        }
    }

    /*
        public function studentList(){

            if($this->session->userdata('isadmin')||$this->session->userdata('isleader')){
                $data['students'] = $this->getlist();
                $data['currentHouse'] = $this->getHouse();
                $this->loadPage($data);
            }else{
                redirect(base_url() . "home");
            }

        }
    */

    private function currentUser($user_id)
    {
        $query = $this->db->query("SELECT * FROM user WHERE id = " . $user_id);
        if ($query->num_rows() > 0) {
            # code...
            return $query->row();
        }
    }

    private function loadPage($data, $page = "studentLeader")
    {
        $data['active'] = 'manage';
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view($page, $data);
        $this->load->view('templates/footer');
    }

    private function getAllUsers($house_id)
    {

        $sql = "SELECT
		sum(c.points)  AS points,
		count(cp.id) AS challenges,
		u.*
		FROM   user AS u,
		challengeparticipant AS cp,
		challenge as c
		WHERE 
		c.id = cp.challenge_id
		AND cp.user_id = u.id
		AND cp.inactive = 0
		AND cp.progress >= 1
		AND u.staff = 0
		AND u.house_id = ?
		GROUP BY u.id
		ORDER BY sum(c.points) DESC, sum(cp.complete_time-cp.start_time) ASC";

        $query1 = $this->db->query($sql, array($house_id));
        $progress = array();
        foreach ($query1->result() as $row) {
            $progress[$row->id] = $row;
        }

        $query = $this->db->query("SELECT * FROM user WHERE house_id = ?", array($house_id));
        foreach ($query->result() as $row2) {
            if (empty($progress[$row2->id])) {
                $row2->challenges = 0;
                $row2->points = 0;
                $progress[$row2->id] = $row2;
            }
        }

        return $progress;
    }

    /*
        private function getlist(){
            $user_id = $this->uid;
            $query = $this->db->query("SELECT house_id FROM user WHERE id = " . $user_id);
            if($query->num_rows()>0){
                $house_id=$query->row()->house_id;
                $query = $this->db->query("SELECT * FROM user WHERE house_id = " . $house_id);

                if($query->num_rows()>0){
                    return $query->result();
                }
            }
        }

        private function getHouse(){
            $user_id = $this->uid;
            $sql = "SELECT house.name AS house_name
            FROM house
            INNER JOIN user
            ON user.house_id = house.id
            WHERE user.id = " . $user_id;

            $query = $this->db->query($sql);
            if($query->num_rows()>0){
                $house = $query->row()->house_name;
                return $house;
            }
        }
       */
}