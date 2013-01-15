<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenges extends CI_Controller {

	public function index(){
		$this->load->helper('url');
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['events'] = $this->loadRecentEvents();
			$data['workouts'] = $this->loadRecentWorkouts();
			$this->loadView("recent","recent", $data);
		}
	}

	public function viewevent($event_id){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$query = $this->db->query('SELECT * FROM event WHERE id=' . $event_id);
			if($query->num_rows()>0){
				$data['event'] = $query->row();

				$sql = "SELECT user.id, user.first_name, user.last_name, user.profile_pic
						FROM user
						INNER JOIN eventparticipant
						ON user.id = eventparticipant.user_id
						WHERE eventparticipant.event_id = " . $event_id;

				$query = $this->db->query($sql);
				if($query->num_rows()>0){
					$data['participants'] = $query->result();
				}

				$sql = "SELECT user.id, user.first_name, user.last_name, user.profile_pic, eventcomment.comment, eventcomment.time_created
						FROM eventcomment
						INNER JOIN user
						ON eventcomment.user_id = user.id
						WHERE eventcomment.event_id = " . $event_id . "
						ORDER BY eventcomment.time_created DESC";

				$query = $this->db->query($sql);
				if($query->num_rows()>0){
					$data['comments'] = $query->result();
				}

				$query = $this->db->query("SELECT * FROM eventparticipant WHERE user_id=" . $this->session->userdata('user_id') . " AND event_id=". $event_id);
				if($query->num_rows()>0){
					$data['joined'] = true;
				}else{
					$data['joined'] = false;
				}

			$data['active'] = 1;
			$data['displayName'] = $this->session->userdata('name');
			$data['avatar'] = $this->session->userdata('avatar');
			$data['isAdmin'] = $this->session->userdata('isadmin');
			$data['isLeader'] = $this->session->userdata('isleader');
			$this->load->view('templates/header', $data);
			$this->load->view('viewChallenge', $data);
			$this->load->view('templates/footer');

			}


		}
	}

	public function postComment(){
		$comment = $this->input->post('message');
		$event_id = $this->input->post('event_id');
		if($comment&&$event_id){
			try {
			$sql = "INSERT INTO eventcomment(event_id, user_id, comment)
					VALUES (". $event_id .", ". $this->session->userdata('user_id') .", ". $this->db->escape($comment) .")";

			$this->db->query($sql);

			$data = array(
						'success'=>true
					);
			echo json_encode($data);				
			} catch (Exception $e) {
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

	public function recentEvents(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['events'] = $this->loadRecentEvents();
			$this->loadEventSection("recent", $data);
		}
	}

	public function recentWorkouts(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['workouts'] = $this->loadRecentWorkouts();
			$this->loadWorkoutSection("recent", $data);
		}
	}

	public function popularEvents(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['events'] = $this->loadPopularEvents();
			$this->loadEventSection("popular", $data);
		}
	}

	public function popularWorkouts(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['workouts'] = $this->loadPopularWorkouts();
			$this->loadWorkoutSection("popular", $data);
		}
	}

	public function myChallenge(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['events'] = $this->loadMyRecentEvents();
			$data['workouts'] = $this->loadMyRecentWorkouts();
			$this->loadMyChallenges("recent","recent", $data);
		}
	}

	public function myRecentEvents(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['events'] = $this->loadMyRecentEvents();
			$this->loadEventSection("recent", $data);
		}
	}

	public function myRecentWorkouts(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['workouts'] = $this->loadMyRecentWorkouts();
			$this->loadWorkoutSection("recent", $data);
		}
	}

	public function myPopularEvents(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['events'] = $this->loadMyPopularEvents();
			$this->loadEventSection("popular", $data);
		}
	}

	public function myPopularWorkouts(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$data['workouts'] = $this->loadMyPopularWorkouts();
			$this->loadWorkoutSection("popular", $data);
		}
	}

	public function createChallenge(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$this->loadNewChallenge();
		}
	}


	public function newChallenge(){
		if($this->session->userdata('user_id')){
			try {
				$title = $this->input->post('title');
				$description = $this->input->post('description');
				$date = $this->input->post('date');
				$time = $this->input->post('time');
				$frequency = $this->input->post('frequency');
				$location = $this->input->post('location');
				$level = $this->input->post('level');

				$sql = "INSERT INTO event(title, description, creator, `date`, frequency, time, location, min_level)
						VALUES (". $this->db->escape($title) .", ". $this->db->escape($description) .", ". $this->session->userdata('user_id') .", '". $date ."', '". $frequency ."', '". $time ."', '". $location ."', '". $level ."')";
				$this->db->query($sql);	
				$msg['success'] = true;
			} catch (Exception $e) {
				$msg['success'] = false;
			}

		}else{
			$msg['success'] = false;
		}

		echo json_encode($msg);
	}

	public function joinEvent(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$event_id = $this->input->post('event_id');
			if($event_id){
				$sql = "SELECT * FROM eventparticipant
						WHERE event_id='". $event_id ."'
						AND user_id='". $this->session->userdata('user_id') ."'";
				$query = $this->db->query($sql);
				if($query->num_rows==0){
					$sql = "INSERT INTO eventparticipant(event_id, user_id)
							VALUES (". $event_id .", ". $this->session->userdata('user_id') .")";
					$this->db->query($sql);
					$data = array(
						"success"=>true
					);
				}else{
					$data = array(
						"success"=>false
					);
				}
			}else{
				$data = array(
					"success"=>false
				);
			}
		}

		echo json_encode($data);
	}

	private function loadMyRecentEvents(){
		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id=eventparticipant.event_id
		    	WHERE event.frequency = 'once'
		    	AND eventparticipant.user_id = '" . $this->session->userdata('user_id') . "'
		    	GROUP BY event.id
		    	ORDER BY time_created";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant,
						"my_challenge"=>true
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;
	}

	private function loadMyPopularEvents(){
		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id = eventparticipant.event_id
		    	WHERE event.frequency = 'once'
		    	AND eventparticipant.user_id = '" . $this->session->userdata('user_id') . "'
		    	GROUP BY event.id
		    	ORDER BY num_participant DESC";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant,
						"my_challenge"=>true
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;
	}

	private function loadMyRecentWorkouts(){
		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id = eventparticipant.event_id
		    	WHERE event.frequency <> 'once'
		    	AND eventparticipant.user_id = '" . $this->session->userdata('user_id') . "'
		    	GROUP BY event.id
		    	ORDER BY time_created";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"frequency" => $row->frequency,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant,
						"my_challenge"=>true
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;
	}

	private function loadMyPopularWorkouts(){
		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id = eventparticipant.event_id
		    	WHERE event.frequency <> 'once'
		    	AND eventparticipant.user_id = '" . $this->session->userdata('user_id') . "'
		    	GROUP BY event.id
		    	ORDER BY num_participant DESC";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"frequency" => $row->frequency,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant,
						"my_challenge"=>true
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;
	}

	private function loadRecentEvents(){

		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id=eventparticipant.event_id
		    	WHERE event.frequency = 'once'
		    	GROUP BY event.id
		    	ORDER BY time_created";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;

	}

	private function loadRecentWorkouts(){

		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id = eventparticipant.event_id
		    	WHERE event.frequency <> 'once'
		    	GROUP BY event.id
		    	ORDER BY time_created";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"frequency" => $row->frequency,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;

	}

	private function loadPopularEvents(){
		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id = eventparticipant.event_id
		    	WHERE event.frequency = 'once'
		    	GROUP BY event.id
		    	ORDER BY num_participant DESC";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;
	}

	private function loadPopularWorkouts(){
		$sql = "SELECT *, COUNT(eventparticipant.user_id) AS num_participant
				FROM event
		    	LEFT JOIN eventparticipant
		    	ON event.id = eventparticipant.event_id
		    	WHERE event.frequency <> 'once'
		    	GROUP BY event.id
		    	ORDER BY num_participant DESC";

		$query = $this->db->query($sql);
		$events = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentEvent = array(
						"id" => $row->id,
						"title" => $row->title,
						"description" => $row->description,
						"creator" =>$row->creator,
						"date" => $row->date,
						"time" => $row->time,
						"frequency" => $row->frequency,
						"location" =>$row->location,
						"min_level" => $row->min_level,
						"image" => $row->event_image,
						"time_created"=>$row->time_created,
						"num_participant"=>$row->num_participant
					);

				array_push($events, $currentEvent);
			}
		}

		return $events;
	}

	private function loadView($eventTab, $workOutTab, $data=array()){
			$data['active'] = 1;
			$data['displayName'] = $this->session->userdata('name');
			$data['avatar'] = $this->session->userdata('avatar');
			$data['currentEventTab'] = $eventTab;
			$data['currentWorkoutTab'] = $workOutTab;
			$data['isAdmin'] = $this->session->userdata('isadmin');
			$data['isLeader'] = $this->session->userdata('isleader');
			$this->load->view('templates/header', $data);
			$this->load->view('challenges', $data);
			$this->load->view('templates/footer');
	}

	private function loadEventSection($type, $data=array()){
		$data['currentEventTab']=$type;
		$this->load->view('templates/events',$data);
	}

	private function loadWorkoutSection($type, $data=array()){
		$data['currentWorkoutTab']=$type;
		$this->load->view('templates/workouts',$data);
	}

	private function loadMyChallenges($eventTab, $workOutTab, $data=array()){
			$data['active'] = 1;
			$data['displayName'] = $this->session->userdata('name');
			$data['avatar'] = $this->session->userdata('avatar');
			$data['currentEventTab'] = $eventTab;
			$data['currentWorkoutTab'] = $workOutTab;
			$data['isAdmin'] = $this->session->userdata('isadmin');
			$data['isLeader'] = $this->session->userdata('isleader');
			$this->load->view('templates/header', $data);
			$this->load->view('myChallenges', $data);
			$this->load->view('templates/footer');
	}

	private function loadNewChallenge($data=array()){
			$data['active'] = 1;
			$data['displayName'] = $this->session->userdata('name');
			$data['avatar'] = $this->session->userdata('avatar');
			$data['isAdmin'] = $this->session->userdata('isadmin');
			$data['isLeader'] = $this->session->userdata('isleader');
			$this->load->view('templates/header', $data);
			$this->load->view('createChallenge', $data);
			$this->load->view('templates/footer');
	}

}