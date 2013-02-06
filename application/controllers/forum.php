<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum extends CI_Controller {
	public function index(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$query = $this->db->query("SELECT * FROM forum");
			if ($query->num_rows()>0) {
				$forum_list = $query->result();
			} 

			$data['forum_list'] = $forum_list;
			$this->loadView($data);			
		}
	}

	public function topic($forum_id){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$query = $this->db->query("SELECT * FROM forumtopics WHERE forum_id = " . $this->db->escape($forum_id));
			if ($query->num_rows()>0) {
				$forum_list = $query->result();
				$data['topic_list'] = $forum_list;
			
			} 

			$data['forum_id'] = $forum_id;
			$query = $this->db->query("SELECT * FROM forum WHERE id = " . $this->db->escape($forum_id));
			if ($query->num_rows()>0) {
				# code...
				$data['forum'] = $query->row();
			}
			$this->loadView($data, "topic");			
		}		
	}

	public function challenge() {
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else {
			$this->load->model('User_model','userModel');
			$this->load->model('Forum_model','forumModel');
			$forums = $this->forumModel->getChallengeForum();
			$data['threads'] = $forums;
			$data['users'] = $this->userModel->loadUsers($data['threads']['uids']);
			unset($data['threads']['uids']);
			echo "<pre>"; print_r($data);echo "</pre><br>";
			$this->loadView($data, "challenge");
		}
	}

	public function thread($topic_id){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$sql = "SELECT forumtopics.id AS topic_id, forumtopics.title as topic_title, forum.id AS forum_id, forum.title AS forum_title
					FROM forumtopics
					INNER JOIN forum
					ON forumtopics.forum_id = forum.id
					WHERE forumtopics.id = " . $this->db->escape($topic_id);

			$query = $this->db->query($sql);
			if ($query->num_rows()>0) {
				$first_row = $query->row();
				$data['first_row'] = $first_row;
			} 

			$sql = "SELECT forumthread.id AS id, forumthread.title AS title, forumthread.votes AS votes, forumthread.views AS views, count(threadpost.id) + 1 AS num_posts
					FROM forumthread
					LEFT JOIN threadpost
					ON threadpost.thread_id = forumthread.id
					WHERE topic_id = " . $this->db->escape($topic_id) . "
					GROUP BY forumthread.id";
			$query = $this->db->query($sql);
			if ($query->num_rows()>0) {
				$forum_list = $query->result();
				$data['thread_list'] = $forum_list;
			} 
			//var_dump($data['first_row']);
			$this->loadView($data, "thread");			
		}			

	}

	public function discussion($thread_id){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$sql = "SELECT forumthread.id AS thread_id, forumthread.anonymous AS anonymous, forumthread.comment AS thread_comment, forumthread.creator_id AS creator, creator.first_name AS creator_first_name, creator.last_name AS creator_last_name, forumthread.votes AS thread_votes, forumthread.create_time AS thread_create_time, forumtopics.id AS topic_id, forumtopics.title as topic_title, forum.id AS forum_id, forum.title AS forum_title, forumthread.title AS title
					FROM forumthread
					INNER JOIN forumtopics
					ON forumtopics.id = forumthread.topic_id
					INNER JOIN forum
					ON forum.id = forumtopics.forum_id
					INNER JOIN user AS creator
					ON forumthread.creator_id = creator.id
					WHERE forumthread.id = " . $this->db->escape($thread_id);

			$query = $this->db->query($sql);
			if ($query->num_rows()>0) {
				$first_row = $query->row();
				$data['first_row'] = $first_row;
			} 

			// view +1
			$this->db->query("UPDATE forumthread SET views = views + 1 WHERE id = " . $thread_id);

			$sql = "SELECT threadpost.id AS id, threadpost.anonymous AS anonymous, threadpost.comment AS comment, threadpost.vote AS votes, threadpost.thread_id AS thread_id, threadpost.time_created AS create_time, user.last_name AS last_name, user.first_name AS first_name, user_id
					FROM threadpost
					INNER JOIN user
					ON threadpost.user_id = user.id
					WHERE threadpost.thread_id = " . $this->db->escape($thread_id);

			$query = $this->db->query($sql);
			if ($query->num_rows()>0) {
				$forum_list = $query->result();
				$data['discussion_list'] = $forum_list;
			} 
			//var_dump($data['first_row']);
			$this->loadView($data, "discussion");			
		}		
	}

	public function createThread(){
		if(!$this->session->userdata('user_id')){
			$msg = array(
				"success" => true,
				"login" => false
				);
		}else{

			$title = $this->db->escape($this->input->post("title"));
			$message = $this->db->escape($this->input->post("message"));
			$anonymous = $this->input->post("anonymous");
			$subscribe = $this->input->post("subscribe");
			$topic_id = $this->input->post("topic_id");

			if ($title==null || $message == null || $title=='' || $message=='') {
				# code...
				$msg = array(
				"success" => true,
				"login" => true
				);
			} else {
			if ($anonymous) {
					$anonymous = 1;
				} else {
					$anonymous = 0;
				}

				$sql = "INSERT INTO forumthread(topic_id, title, creator_id, comment, anonymous)
						VALUES (" . $this->db->escape($topic_id) . ", " . $title . ", " . $this->session->userdata("user_id") . ", " . $message . ", " . $anonymous . ")";

				$this->db->query($sql);
				$threadpost_id = $this->db->insert_id();
				if (isset($threadpost_id)) {
					# code...
					$msg = array(
					"success" => true,
					"thread_id" => $threadpost_id
					);

					if ($subscribe) {
						$sql = "INSERT INTO postsubscription(thread_id, user_id)
								VALUES (" . $threadpost_id . ", " . $this->session->userdata("user_id") . ")";

						$this->db->query($sql);

					}
				}
			}

		}
		//var_dump($title);
		//var_dump($message);
		echo json_encode($msg);
	}

	public function newThread($topic_id){
		if(!$this->session->userdata('user_id')){
				redirect(base_url() . "login");
		}else{
			if(isset($topic_id)){
				$data['topic_id'] = $topic_id;
				$this->loadView($data, "newThread");			
			} else {
				redirect(base_url() . "forum");
			}
		}

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
			$message = $this->db->escape($this->input->post("comment"));

			if ($message!=null && $message!='') {
				# code...
				$sql = "INSERT INTO threadpost(thread_id, comment, user_id)
						VALUES (" . $thread_id . ", " . $message . ", " . $this->session->userdata("user_id") . ")";
				$this->db->query($sql);
				$threadpost_id = $this->db->insert_id();
				if (isset($threadpost_id)) {
					# code...
					$msg = array(
					"success" => true,
					"thread_id" => $threadpost_id
					);

				$query = $this->db->query("SELECT * FROM user WHERE id = " . $user_id);
				$thread = $this->db->query("SELECT * FROM forumthread WHERE id = " . $thread_id);


				if ($query->num_rows()>0 && $thread->num_rows()>0) {
					# code...
					$username = $query->row()->first_name . " " . $query->row()->last_name;
					$title = $thread->row()->title;
					$description = $username . " post a new message at the thread: " . $title;
					$url = base_url() . "forum/discussion/" . $thread_id;
					$query = $this->db->query("SELECT * FROM postsubscription WHERE thread_id = " . $thread_id);

					if ($query->num_rows()>0) {
						# code...
						$subscriptions = $query->result();
						foreach ($subscriptions as $value) {
							if ($value->user_id != $user_id) {
							$this->load->model('User_model','userModel');
							$notification_id = $this->userModel->addNotification($value->user_id, $description, $url);
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

	public function upvote(){
		$thread_id = $this->input->post("thread_id");
		$post_id = $this->input->post("post_id");
		$msg['success'] = false;
		if(!empty($thread_id)){
			$this->db->query("UPDATE forumthread SET votes = votes + 1 WHERE id = " . $thread_id);
			$msg['success'] = true;
		} 

		if (!empty($post_id)) {
			$this->db->query("UPDATE threadpost SET vote = vote + 1 WHERE id = " . $post_id);
			$msg['success'] = true;
		}

		echo json_encode($msg);
	}

	public function downvote(){
		$thread_id = $this->input->post("thread_id");
		$post_id = $this->input->post("post_id");

		$msg['success'] = false;
		if(!empty($thread_id)){
			$this->db->query("UPDATE forumthread SET votes = votes - 1 WHERE id = " . $thread_id);
			$msg['success'] = true;
		} 

		if (!empty($post_id)) {
			$this->db->query("UPDATE threadpost SET vote = vote - 1 WHERE id = " . $post_id);
			$msg['success'] = true;
		}

		echo json_encode($msg);

	}

	public function markspam(){
		$thread_id = $this->input->post("thread_id");
		$post_id = $this->input->post("post_id");
		$msg['success'] = false;
		if(!empty($thread_id)){
			$this->db->query("UPDATE forumthread SET spam = spam + 1 WHERE id = " . $thread_id);
			$msg['success'] = true;
		} 

		if (!empty($post_id)) {
			$this->db->query("UPDATE threadpost SET spam = spam + 1 WHERE id = " . $post_id);
			$msg['success'] = true;
		}

		echo json_encode($msg);

	}


	private function loadView($data, $type="forum"){
		$data['active'] = 8;
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		$this->load->model('User_model','userModel');
		$data['notifications'] = $this->userModel->getNotifications($this->session->userdata("user_id"));

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