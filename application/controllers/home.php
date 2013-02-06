<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{

	public function index(){
		$data['active'] = 0;

		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$user_id = $this->session->userdata('user_id');
			$this->loadUserData($user_id, $data);
			$this->loadActivityData($user_id, $data);
			$this->loadPosts($user_id, $data);
			$this->loadChallenges($user_id, $data);
			$this->loadBadges($user_id, $data);	
			$data['avg_points'] = $this->getAverage();

			$this->load->view('templates/header', $data);
			$this->load->view('home', $data);
			$this->load->view('templates/footer');
		}
	}

	private function loadPosts($user_id, &$data) {
		$postsSql= "SELECT User.id AS post_user_id, User.first_name AS first_name, User.last_name AS last_name, User.profile_pic AS profile_pic, Post.time AS time, Post.description AS description, Post.type AS type
								FROM Subscription
								INNER JOIN Post ON Post.user_id = Subscription.subscriber_id
								INNER JOIN User ON User.id = Post.user_id
								WHERE Subscription.user_id = '" . $user_id . "'
								AND Post.time <= NOW()
								ORDER BY Post.time DESC
								LIMIT 0, 5";

		$postsQuery = $this->db->query($postsSql);
		$posts = array();
		if($postsQuery->num_rows()>0){
			foreach($postsQuery->result() as $row){
				$currentPost = array(
					'user_id' => $row->post_user_id,
					'username' => $row->first_name . ' ' . $row->last_name,
					'profile_pic' => $row->profile_pic,
					'time' => $row->time,
					'description' => $row->description,
					'type' =>$row->type
				);

				array_push($posts, $currentPost);
			}
		}

		$data['posts'] = $posts;
	}

	private function loadUserData($user_id, &$data) {
		$this->load->model('User_model','userModel');
		$user = $this->userModel->loadUser($user_id);
		
		if($this->session->userdata('name')){
			$displayName = $this->session->userdata('name');
		}else{
			$displayName = $user->first_name . ' ' . $user->last_name;
		}

		$this->session->set_userdata('name', $displayName);
		$this->session->set_userdata('username', $user->username);
		$gender =$user->gender;
		$avatar = $user->profile_pic;
		$isTutor = $user->staff;
		$isleader = $user->leader;
		$isadmin = $user->admin;
		$isphantom = $user->phantom;



		$data['avatar'] = $avatar;
		$this->session->set_userdata('avatar', $avatar);
		$this->session->set_userdata('isTutor', $isTutor);
		$this->session->set_userdata('isphantom', $isphantom);
		$this->session->set_userdata('isleader', $isleader);
		$this->session->set_userdata('isadmin', $isadmin);

		$data['gender'] = $gender;
		$data['displayName'] = $displayName;
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['notifications'] = $this->userModel->getNotifications($this->session->userdata("user_id"));
	}

	private function loadActivityData($user_id, &$data) {
		$this->load->model('Activity_model', 'activityModel');
		$activityRow = $this->activityModel->getActivityToday($user_id);
		if($activityRow != FALSE){
			$data['activescore'] = $activityRow->active_score;
			$data['calories'] = $activityRow->activity_calories;
			$data['distance'] = $activityRow->distance;
			$data['floors'] = $activityRow->floors;
			$data['steps'] = $activityRow->steps;
		}else{
			$data['activescore'] = 0;
			$data['calories'] = 0;
			$data['distance'] = 0;
			$data['floors'] = 0;
			$data['steps'] = 0;
		}
	}

	private function loadBadges($user_id, &$data){
		$this->load->model('Badge_model', 'badgeModel');
		$data['badges'] = $this->badgeModel->getBadges($user_id);

	}

	private function loadChallenges($user_id, &$data){
		$this->load->model('Challenge_model', 'challengeModel');

		$data['challenges'] = $this->challengeModel->getCurrentChallenges($user_id);
		
	}

	private function getAverage(){
		$sql = "SELECT avg(points) AS avg_points
				FROM user
				WHERE phantom=0";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->row();
		}
		
	}

	public function postMessage(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$msg = $this->input->post('message');
			if($msg){
				$sql = "INSERT INTO Post(user_id, type, description)
						VALUES (" . $this->session->userdata('user_id') . ", 1, " . $this->db->escape($msg) . ")";
				$this->db->query($sql);

				$last_id = $this->db->insert_id();

				$postsSql= "SELECT User.id AS post_user_id, User.first_name AS first_name, User.last_name AS last_name, User.profile_pic AS profile_pic, Post.time AS time, Post.description AS description, Post.type AS type
								FROM Subscription
								INNER JOIN Post ON Post.user_id = Subscription.subscriber_id
								INNER JOIN User ON User.id = Post.user_id
								WHERE Post.id = '" . $last_id  . "'";

				$postsQuery = $this->db->query($postsSql);
				if($postsQuery->num_rows()>0){
					$row = $postsQuery->row(); 
					$time = $row->time;
					date_default_timezone_set('UTC'); 
					$timestamp = strtotime((string) $time); 
					$posts = array(
							'user_id' => $row->post_user_id,
							'username' => $row->first_name . ' ' . $row->last_name,
							'profile_pic' => $row->profile_pic,
							'time' => $timestamp,
							'description' => $row->description,
							'type' =>$row->type
					);
					$data = array(
						'success'=>true,
						'posts'=>$posts

					);
					echo json_encode($data);

				}else{
					$data = array(
						'success'=>false
					);
					echo json_encode($data);
				}

			}else{
					$data = array(
						'success'=>false
					);
					echo json_encode($data);
			}
		}
	}
}