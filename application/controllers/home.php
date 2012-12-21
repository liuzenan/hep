<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller{

	public function index(){
		$data['active'] = 1;
		$this->output->cache(200);

		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "index.php/login");
		}else{
			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM User WHERE id = '" . $user_id . "'");

			if($query->num_rows()>0){
				$row = $query->row();
				$displayName = $row->username;
				$gender =$row->gender;
				$avatar = $row->profile_pic;


				$data['avatar'] = $avatar;
				$data['gender'] = $gender;
				$data['displayName'] = $displayName;

				$date = new DateTime();
				$dateStr = $date->format('Y-m-d');
				$this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));

				$activity = $this->fitbitphp->getActivities($date);

				//get activities goal
				$data['activeGoal'] = $activity->goals->activeScore;
				$data['caloriesGoal'] = $activity->goals->caloriesOut;
				$data['distanceGoal'] = $activity->goals->distance;
				$data['floorsGoal'] = $activity->goals->floors;
				$data['stepsGoal'] = $activity->goals->steps;

				//get activities data
				$activityQuery = $this->db->query("SELECT * FROM Activity WHERE user_id='" . $user_id ."' and date='" . $dateStr . "'");
				if($activityQuery->num_rows()>0){
					$activityRow = $activityQuery->row();
					$data['activescore'] = $activityRow->active_score;
					$data['calories'] = $activityRow->calories;
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

				$postsSql= "SELECT User.username AS username, User.profile_pic AS profile_pic, Post.time AS time, Post.description AS description
								FROM Subscription
								INNER JOIN Post ON Post.user_id = Subscription.subscriber_id
								INNER JOIN User ON User.id = Post.user_id
								WHERE Subscription.user_id = '" . $user_id . "'
								ORDER BY Post.time DESC";

				$postsQuery = $this->db->query($postsSql);
				$posts = array();
				if($postsQuery->num_rows()>0){
					foreach($postsQuery->result() as $row){
						$currentPost = array(
							'username' => $row->username,
							'profile_pic' => $row->profile_pic,
							'time' => $row->time,
							'description' => $row->description
						);

						array_push($posts, $currentPost);
					}
				}

				$data['posts'] = $posts;

				$this->load->view('templates/header', $data);
				$this->load->view('home', $data);
				$this->load->view('templates/footer');


			}else{
				//error
			}
		
		}
	}
}