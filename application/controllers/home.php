<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{

	public function index(){
		$data['active'] = 0;
		//$this->output->cache(200);

		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM User WHERE id = '" . $user_id . "'");

			if($query->num_rows()>0){
				$row = $query->row();
				if($this->session->userdata('name')){
					$displayName = $this->session->userdata('name');
				}else{
					$displayName = $row->first_name . ' ' . $row->last_name;
				}

				$this->session->set_userdata('name', $displayName);
				$this->session->set_userdata('username', $row->username);
				$gender =$row->gender;
				$avatar = $row->profile_pic;
				$isleader = $row->leader;
				$isadmin = $row->admin;
				$isphantom = $row->phantom;



				$data['avatar'] = $avatar;
				$this->session->set_userdata('avatar', $avatar);
				$this->session->set_userdata('isphantom', $isphantom);
				$this->session->set_userdata('isleader', $isleader);
				$this->session->set_userdata('isadmin', $isadmin);

				$data['gender'] = $gender;
				$data['displayName'] = $displayName;
				$data['isAdmin'] = $this->session->userdata('isadmin');
				$data['isLeader'] = $this->session->userdata('isleader');

				$dateStr = date("Y-m-d");
				//get activities data
				$activityQuery = $this->db->query("SELECT * FROM Activity WHERE user_id='" . $user_id ."' and date='" . $dateStr . "'");
				if($activityQuery->num_rows()>0){
					$activityRow = $activityQuery->row();
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

				$postsSql= "SELECT User.first_name AS first_name, User.last_name AS last_name, User.profile_pic AS profile_pic, Post.time AS time, Post.description AS description, Post.type AS type
								FROM Subscription
								INNER JOIN Post ON Post.user_id = Subscription.subscriber_id
								INNER JOIN User ON User.id = Post.user_id
								WHERE Subscription.user_id = '" . $user_id . "'
								AND Post.time <= NOW()
								ORDER BY Post.time DESC";

				$postsQuery = $this->db->query($postsSql);
				$posts = array();
				if($postsQuery->num_rows()>0){
					foreach($postsQuery->result() as $row){
						$currentPost = array(
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
				$data['exp'] = $this->getExp();

				$this->load->helper('level');
				$data['level'] = getLevel(intval($data['exp']));
				$data['nextlevelpoints'] = getNextLevelPoints($data['level']+1);

				$this->load->view('templates/header', $data);
				$this->load->view('home', $data);
				$this->load->view('templates/footer');


			}else{
				//error
			}
		
		}
	}

	private function getExp(){
		$user_id = $this->session->userdata('user_id');
		
		$sql = "SELECT sum(activity.steps) AS total_steps, sum(activity.floors) AS total_floors, sum(activity.active_score) AS total_score
				FROM activity
				WHERE activity.user_id = " . $user_id . "
				GROUP BY activity.user_id";

		$query1 = $this->db->query($sql);

		$sql = "SELECT sum(efficiency) AS total_eff
				FROM sleep
				WHERE sleep.user_id = " . $user_id . "
				GROUP BY sleep.user_id";
		$query2 = $this->db->query($sql);

		if($query2->num_rows()>0&&$query1->num_rows()>0){
			$result = $query1->row();
			$steps = $result->total_steps;
			$floors = $result->total_floors;
			$score = $result->total_score;

			$result2 = $query2->row();
			$eff = $result2->total_eff;
			$this->load->helper('level');
			$expPoint = getExpPoints(intval($steps), intval($floors), intval($score), intval($eff));
			return $expPoint;
		}
		
	}

	private function getAverage(){
		
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

				$postsSql= "SELECT User.first_name AS first_name, User.last_name AS last_name, User.profile_pic AS profile_pic, Post.time AS time, Post.description AS description, Post.type AS type
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