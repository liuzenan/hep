<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller
{
    private $uid;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect(base_url() . "login");
        } else {
            $this->uid = $this->session->userdata('user_id');
        }
    }

    public function index()
    {

        $this->load->helper('form');
        if (!$this->session->userdata('user_id')) {
            redirect(base_url() . "login");
        } else {
            $this->uid = $this->session->userdata('user_id');
            $query = $this->db->get('house');
            $houses = [];
            foreach ($query->result() as $row) {
                $houses[$row->id] = $row->name;
            }
            $data['houses'] = $houses;
            $data['defaultCode'] = defined(DEFAULT_REGISTRATION_CODE) ? DEFAULT_REGISTRATION_CODE : '';
            $this->load->view('signup', $data);
        }

    }

    public function linkFB()
    {

        $this->load->view('linkFacebook');

    }

    public function fbLogin()
    {
        $username = $this->input->post('username');

        if ($username) {
            $data = array(
                'username' => $username,
                'fb' => 1
            );
            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('user', $data);
            echo 'success';
        } else {
            echo 'user info not complete. username: ' . $username . ' email: ' . $email . ' firstname: ' . $firstname . ' lastname: ' . $lastname;
        }

    }

    public function updateProfilePic()
    {
        $profile_pic = $this->input->post('profile_pic');

        if ($profile_pic) {
            $data = array(
                'profile_pic' => $profile_pic
            );
            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('user', $data);
            echo 'success';
        } else {
            echo 'user info not complete. username: ' . $username . ' email: ' . $email . ' firstname: ' . $firstname . ' lastname: ' . $lastname;
        }

    }


    public function submit()
    {
        $msg = array('success' => false, 'message' => '');

        $firstname = trim($this->input->post("firstname"));
        $lastname = trim($this->input->post("lastname"));
        $email = trim($this->input->post("email"));
        $house = trim($this->input->post("house"));
        $code = strtoupper(trim($this->input->post('registrationcode')));

        $query = $this->db->get_where('registration', array('code' => $code));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $access = $row->access;
            $used = $row->used;
            $is_super = $row->supercode;

            if (!$is_super) {
                // if ($used) {
                //     $msg['message'] = 'The registration code has already been used.';
                //     echo json_encode($msg); 
                //     return false;
                // } 
                if ($house < 0) {
                    $msg['message'] = 'You cannot register as a Tutor.';
                    echo json_encode($msg);
                    return false;
                } else {
                    
                }
            }

        } else {
            $msg['message'] = 'You have entered an invalid registration code';
            echo json_encode($msg); 
            return false;
        }

        if ($firstname && $lastname && $email) {
            $data = array();

            $data['first_name'] = $firstname;
            $data['last_name'] = $lastname;
            $data['email'] = $email;
            $data['admin'] = 0;
            $data['leader'] = 0;
            $data['phantom'] = 0;
            $data['staff'] = 0;
            $data['access'] = $access;
            $data['code'] =  $code;

            if ($house != "0") {
                $data['house_id'] = $house;
            }
            if ($house == -1) {
                $data['staff'] = 1;
                $data['hide_progress'] = 1;
            } else {
                $data['hide_progress'] = 0;

            }
            $this->db->where('id', $this->session->userdata('user_id'));
            $this->db->update('user', $data);

            $data = array();
            $data['used'] = 1;
            $this->db->where('code', $code);
            $this->db->update('registration', $data);
        } else {
            $msg['message'] = 'Make sure that you have filled in all fields';
            echo json_encode($msg);
            return false;
        }

        $msg['success'] = true;
        echo json_encode($msg);

    }
}