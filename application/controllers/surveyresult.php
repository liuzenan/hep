<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Surveyresult extends Admin_Controller
{

    public function index()
    {
        $house_id = 1;
        $this->house($house_id);
    }

    public function house($house_id)
    {
        $sql = "SELECT * FROM survey
				INNER JOIN user
				ON survey.userid = user.id
				WHERE user.house_id = " . $this->db->escape($house_id);

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            # code...
            $data['surveyResult'] = $query->result();


        }

        $data['house_id'] = $house_id;
        $this->loadPage($data);
    }

    public function all()
    {
        $sql = "SELECT * FROM survey
				INNER JOIN user
				ON survey.userid = user.id";


        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            # code...
            $data['surveyResult'] = $query->result();
        }

        $data['house_id'] = "all";
        $this->loadPage($data);
    }

    public function count()
    {
        $sql1 = "SELECT user.first_name, user.last_name, user.email FROM user
				WHERE user.id NOT IN ( SELECT userid FROM survey )
				AND user.phantom = 0
				AND user.staff = 0";

        $query1 = $this->db->query($sql1);

        if ($query1->num_rows() > 0) {
            # code...
            $data['not_complete'] = $query1->result();
        }

        $sql2 = "SELECT user.first_name, user.last_name, user.email FROM user
				WHERE user.id IN ( SELECT userid FROM survey )
				AND user.phantom = 0
				AND user.staff = 0";

        $query2 = $this->db->query($sql2);

        if ($query2->num_rows() > 0) {
            # code...
            $data['complete'] = $query2->result();
        }

        $data['house_id'] = "count";
        $this->loadPage($data);
    }

    private function loadPage($data)
    {
        $data['active'] = 'surveyresult';
        parent::loadUser($data);
        $this->load->view('templates/header', $data);
        $this->load->view('surveyresult', $data);
        $this->load->view('templates/footer');
    }

}