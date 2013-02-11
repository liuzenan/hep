<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller {
	private $uid;
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}

	public function challenge() {
		
		$forums = $this->Forum_model->getChallengeForum();
		$data['threads'] = $forums;
		$data['users'] = 
		count($data['threads']['uids'])>0
		? $this->User_model->loadUsers($data['threads']['uids'])
		: array();
		unset($data['threads']['uids']);
		$data['active'] = 'challenge_forum';
		//echo "<pre>"; print_r($data);echo "</pre><br>";
		$this->loadView($data, "challenge");
	}

	public function general() {
		$forums = $this->Forum_model->getGeneralForum($this->uid);
		$data['threads'] = $forums;
		$data['users'] = 
		count($data['threads']['uids'])>0
		? $this->User_model->loadUsers($data['threads']['uids'])
		: array();		unset($data['threads']['uids']);
		$data['active'] = 'general_forum';
		echo "<pre>"; print_r($data);echo "</pre><br>";
		$this->loadView($data, "general");
	}

	public function tutor() {
		$forums = $this->Forum_model->getTutorForum();
		$data['threads'] = $forums;
		$data['users'] = 
		count($data['threads']['uids'])>0
		? $this->User_model->loadUsers($data['threads']['uids'])
		: array();		unset($data['threads']['uids']);
		$data['active'] = 'tutor_forum';
		echo "<pre>"; print_r($data);echo "</pre><br>";
		$this->loadView($data, "challenge");
	}
	

	public function createThread(){
		if(!$this->session->userdata('user_id')){
			$msg = array(
				"success" => true,
				"login" => false
				);
		}else{
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


	public function postMessage() {
		if(!$this->session->userdata('user_id')){
			$msg = array(
				"success" => false,
				"login" => false
				);
		}else{
			$user_id = $this->session->userdata('user_id');
			$thread_id = $this->input->post("thread_id");
			$message = $this->input->post("comment");

			if ($message!=null && $message!='') {
				# code...
				
				$threadpost_id = $this->Forum_model->createPost($user_id, $thread_id, $message);
				if (isset($threadpost_id)) {
					# code...
					$msg = array(
						"success" => true,
						"thread_id" => $threadpost_id
						);

					$user = $this->User_model->loadUser($user_id);
					$thread = $this->Forum_model->loadThread($thread_id);

					if (isset($user) && isset($thread)) {
					# code...
						$username = $user->first_name . " " . $user->last_name;
						$title = $thread->message;
						$description = $username . " post a new message at the thread: " . $title;
						$url = base_url() . "forum/thread/" . $thread_id;

						$subscribers = $this->Forum_model->loadThreadSubscribers($thread_id);

						if (count($subscribers)>0) {
						# code...
							foreach ($subscribers as $value) {
								if ($value->user_id != $user_id) {	
									$notification_id = $this->User_model->addNotification($value->user_id, $description, $url);
								}
							}
						}
					}
				}
			} else {
				$msg = array(
					"success" => false,
					"login" => true
					);
			}
		}
		echo json_encode($msg);
	}


	private function loadView($data, $type="forum"){
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));

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