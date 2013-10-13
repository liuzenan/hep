<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Forum_model");
    }

    public function challenge()
    {

        $forums = $this->Forum_model->getChallengeForum($this->uid);
        $data['threads'] = $forums;
        $data['users'] =
            count($data['threads']['uids']) > 0
                ? $this->User_model->loadUsers($data['threads']['uids'])
                : array();
        unset($data['threads']['uids']);
        $data['active'] = 'challenge_forum';
        //echo "<pre>"; print_r($data);echo "</pre><br>";
        $this->loadView($data, "challenge");
    }

    public function general()
    {
        $forums = $this->Forum_model->getGeneralForum($this->uid);
        $data['threads'] = $forums;
        $data['users'] =
            count($data['threads']['uids']) > 0
                ? $this->User_model->loadUsers($data['threads']['uids'])
                : array();
        unset($data['threads']['uids']);
        $data['active'] = 'general_forum';
        //echo "<pre>"; print_r($data);echo "</pre><br>";
        $this->loadView($data, "general");
    }

    public function tutor()
    {
        $forums = $this->Forum_model->getTutorForum($this->uid);
        $data['threads'] = $forums;
        $data['users'] =
            count($data['threads']['uids']) > 0
                ? $this->User_model->loadUsers($data['threads']['uids'])
                : array();
        unset($data['threads']['uids']);
        $data['active'] = 'tutor_forum';
        $this->loadView($data, "general");
    }

    public function data()
    {
        $forums = $this->Forum_model->getGeneralForum($this->uid);
        $data['threads'] = $forums;
        $data['users'] =
            count($data['threads']['uids']) > 0
                ? $this->User_model->loadUsers($data['threads']['uids'])
                : array();
        unset($data['threads']['uids']);
        $data['active'] = 'general_forum';
        echo "<pre>";
        print_r($data);
        echo "</pre><br>";
    }

    public function createThread()
    {
        if (!$this->session->userdata('user_id')) {
            $msg = array(
                "success" => true,
                "login" => false
            );
        } else {
            $message = $this->input->post("message");
            //check empty message in js
            $threadpost_id = $this->Forum_model->createThread($this->uid, $message);
            if (!empty($threadpost_id)) {
                # code...
                $msg = array(
                    "success" => true,
                    "thread_id" => $threadpost_id
                );
                $this->Forum_model->subscribe($this->uid, $threadpost_id);
            }
        }
        echo json_encode($msg);
    }

    public function clearNotification()
    {
        $this->Forum_model->clearNotification($this->uid);
        echo json_encode(array("success" => true));

    }

    public function postMessage()
    {
        if (!$this->session->userdata('user_id')) {
            $msg = array(
                "success" => false,
                "login" => false
            );
        } else {
            $user_id = $this->session->userdata('user_id');
            $thread_id = $this->input->post("thread_id");
            $message = $this->input->post("comment");

            if ($message != null && $message != '') {
                # code...
                $threadpost_id = $this->Forum_model->createPost($user_id, $thread_id, $message);
                $msg = array(
                    "success" => true,
                    "thread_id" => $threadpost_id
                );

            } else {
                $msg = array(
                    "success" => false,
                    "login" => true
                );
            }
        }
        echo json_encode($msg);
    }

    public function subscribe()
    {
        if (!$this->session->userdata('user_id')) {
            $msg = array(
                "success" => false,
                "login" => false
            );
        } else {
            $user_id = $this->session->userdata('user_id');
            $thread_id = $this->input->post("thread_id");
            $this->Forum_model->subscribe($user_id, $thread_id);
            $msg = array("success" => true);
        }
        echo json_encode($msg);


    }

    public function deletePost()
    {
        $id = $this->input->post("post_id");

        $this->Forum_model->deletePost($id);
        echo json_encode(array("success" => true));

    }

    public function deleteThread()
    {

        $id = $this->input->post("thread_id");
        $this->Forum_model->archiveThread($id);
        echo json_encode(array("success" => true));

    }

    public function unsubscribe()
    {
        if (!$this->session->userdata('user_id')) {
            $msg = array(
                "success" => false,
                "login" => false
            );
        } else {
            $user_id = $this->session->userdata('user_id');
            $thread_id = $this->input->post("thread_id");
            $this->Forum_model->unsubscribe($user_id, $thread_id);
            $msg = array("success" => true);
        }
        echo json_encode($msg);

    }

    private function loadView($data, $type = "forum")
    {
        parent::loadUser($data);

        $this->load->view('templates/header', $data);
        if ($type == "forum") {
            # code...
            $this->load->view('forum', $data);
        } else {
            $this->load->view("forum/" . $type, $data);
        }

        $this->load->view('templates/footer');
    }
}