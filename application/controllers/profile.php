<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
		}
	}

	public function index(){
		
	}

	public function viewprofile($user_id){
		
		
		$data['active'] ='profile';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');

		$query = $this->db->query("SELECT * FROM user WHERE id=" . $user_id);

		if($query->num_rows()>0){

			$data['userdata'] = $query->row();
			$sql = "SELECT achievement.badge_pic, achievement.name
			FROM achievement
			INNER JOIN userachievement
			ON achievement.id = userachievement.achievement_id
			WHERE userachievement.user_id = ". $user_id;
			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				$data['userachievement'] = $query->result();
			}

			$sql = "SELECT * FROM post 
			WHERE user_id = " . $user_id . "
			ORDER BY time DESC
			LIMIT 0, 10";

			$query = $this->db->query($sql);
			if($query->num_rows()>0){
				$data['userposts'] = $query->result();
			}

			$sql = "SELECT event.title, event.id, event.date, event.event_image
			FROM event
			INNER JOIN eventparticipant
			ON event.id = eventparticipant.event_id
			WHERE eventparticipant.user_id = " . $user_id;

			$query = $this->db->query($sql);
			if($query->num_rows()>0){
				$data['userevents'] = $query->result();
			}
			
			$data['notifications'] = $this->User_model->getNotifications($this->session->userdata("user_id"));

			$this->load->view('templates/header', $data);
			$this->load->view('profile', $data);
			$this->load->view('templates/footer');

		}			
		

	}
}