<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{

	public function index(){
		$data['active'] = 1;

		if(!$this->session->userdata('user_id')){
			$this->fitbitphp->initSession("http://54.251.40.149/fitbit/index.php/login");
			$xml = $this->fitbitphp->getProfile();
			$username =(string) $xml->user->displayName;
			$fitbit_id =(string) $xml->user->encodedId;
			$oauth_token = $this->fitbitphp->getOAuthToken();
			$oauth_secret = $this->fitbitphp->getOAuthSecret();
			$profile_pic =(string) $xml->user->avatar;
			$gender =(string) $xml->user->gender;

			$query = $this->db->query("SELECT fitbit_id FROM User WHERE fitbit_id = '" . $fitbit_id . "'");
			//check if user is in database
			$user_exist = true;
			if($query->num_rows()==0){
				//user not exists
				$sql = "INSERT INTO User(fitbit_id, oauth_token, oauth_secret, profile_pic, gender, username)
						VALUES (" . $this->db->escape($fitbit_id) . ", " . $this->db->escape($oauth_token) . ", " . $this->db->escape($oauth_secret) . ", " . $this->db->escape($profile_pic) . ", " . $this->db->escape($gender) . ", " . $this->db->escape($username) . ")";

				$this->db->query($sql);
				$user_exist = false;
			}

			//set session data
			$query = $this->db->query("SELECT id FROM User WHERE fitbit_id = '" . $fitbit_id . "'");
			if($query->num_rows()>0){
				$row = $query->row();
				$userdata = array(
								'user_id' => $row->id,
								'fibit_id' => $fitbit_id,
								'oauth_secret' => $oauth_secret,
								'oauth_token' => $oauth_token,
								'username' => $username,
								'avatar' => $profile_pic
							);		
				$this->session->set_userdata($userdata);

				//get user tracker data from fitbit, this might be slow... need to optimize later
				if(!$user_exist){
					//$this->getActivites();
					//$this->getSleep();
					//$this->setFeeds();
					//$this->initPosts();
					//$this->addSubscription();
				}
				//redirect(base_url() . "index.php/home");
			}else{
				echo "something was wrong";
			}			
		}else{
			//redirect(base_url() . "index.php/home");
		}

	}

	private function getActivites(){
		/**
		*
		*	'tracker_caloriesOut', 'tracker_steps', 'tracker_distance', 'tracker_floors', 'tracker_elevation'
     	*   'tracker_activeScore', 'tracker_activityCalories', 'tracker_minutesSedentary', 'tracker_minutesLightlyActive'
		*	'tracker_minutesVeryActive', "tracker_minutesFairlyActive"
		*
		*/
		if($this->session->userdata('oauth_token')&&$this->session->userdata('oauth_secret')){
			$this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
			$basedate = 'today';
			$period = 'max';

			$tracker_caloriesOut = $this->fitbitphp->getTimeSeries('tracker_caloriesOut', $basedate, $period);
			$tracker_steps = $this->fitbitphp->getTimeSeries('tracker_steps', $basedate, $period);
			$tracker_distance = $this->fitbitphp->getTimeSeries('tracker_distance', $basedate, $period);
			$tracker_floors = $this->fitbitphp->getTimeSeries('tracker_floors', $basedate, $period);
			$tracker_elevation = $this->fitbitphp->getTimeSeries('tracker_elevation', $basedate, $period);
			$tracker_activeScore = $this->fitbitphp->getTimeSeries('tracker_activeScore', $basedate, $period);
			$tracker_activityCalories = $this->fitbitphp->getTimeSeries('tracker_activityCalories', $basedate, $period);
			$tracker_minutesSedentary = $this->fitbitphp->getTimeSeries('tracker_minutesSedentary', $basedate, $period);
			$tracker_minutesLightlyActive = $this->fitbitphp->getTimeSeries('tracker_minutesLightlyActive', $basedate, $period);
			$tracker_minutesFairlyActive = $this->fitbitphp->getTimeSeries('tracker_minutesFairlyActive', $basedate, $period);
			$tracker_minutesVeryActive = $this->fitbitphp->getTimeSeries('tracker_minutesVeryActive', $basedate, $period);		

			$activitiesData = array();

			foreach($tracker_caloriesOut as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_caloriesOut'] = $value->value;
			}

			foreach($tracker_steps as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_steps'] = $value->value;				
			}

			foreach($tracker_distance as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_distance'] = $value->value;				
			}

			foreach($tracker_floors as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_floors'] = $value->value;				
			}

			foreach($tracker_elevation as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_elevation'] = $value->value;				
			}

			foreach($tracker_activeScore as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_activeScore'] = $value->value;				
			}

			foreach($tracker_activityCalories as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_activityCalories'] = $value->value;				
			}

			foreach($tracker_minutesSedentary as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_minutesSedentary'] = $value->value;				
			}

			foreach($tracker_minutesLightlyActive as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_minutesLightlyActive'] = $value->value;				
			}

			foreach($tracker_minutesFairlyActive as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_minutesFairlyActive'] = $value->value;				
			}

			foreach($tracker_minutesVeryActive as $value){
				$currentDate = $value->dateTime;
				$activitiesData[$currentDate]['tracker_minutesVeryActive'] = $value->value;				
			}

			//insert into database
			foreach($activitiesData as $key=>$value){
				$sql = "INSERT INTO Activity(user_id, date, steps, floors, calories, active_score, distance, elevation, min_sedentary, min_lightlyactive, min_fairlyactive, min_veryactive, activity_calories)
						VALUES (" . $this->session->userdata('user_id') . ", '" . $key . "', " . $value['tracker_steps'] . ", " . $value['tracker_floors'] . ", " . $value['tracker_caloriesOut'] . ", " . $value['tracker_activeScore'] . ", " . $value['tracker_distance'] . ", " . $value['tracker_elevation'] . ", " . $value['tracker_minutesSedentary'] . ", " . $value['tracker_minutesLightlyActive'] . ", " . $value['tracker_minutesFairlyActive'] . ", " . $value['tracker_minutesVeryActive'] . ", " . $value['tracker_activityCalories'] . ")";	
				$this->db->query($sql);	
			}


		}else{
			echo 'something was wrong';
		}

	}

	private function getSleep(){

		/**
		*
     	*   'startTime', 'timeInBed', 'minutesAsleep', 'minutesAwake', 'awakeningsCount',
     	*   'minutesToFallAsleep', 'minutesAfterWakeup',
     	*   'efficiency'
		*
		*/
		if($this->session->userdata('oauth_token')&&$this->session->userdata('oauth_secret')){
			$this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
			$basedate = 'today';
			$period = 'max';

			$startTime = $this->fitbitphp->getTimeSeries('startTime', $basedate, $period);
			$timeInBed = $this->fitbitphp->getTimeSeries('timeInBed', $basedate, $period);
			$minutesAsleep = $this->fitbitphp->getTimeSeries('minutesAsleep', $basedate, $period);
			$minutesAwake = $this->fitbitphp->getTimeSeries('minutesAwake', $basedate, $period);
			$awakeningsCount = $this->fitbitphp->getTimeSeries('awakeningsCount', $basedate, $period);
			$minutesToFallAsleep = $this->fitbitphp->getTimeSeries('minutesToFallAsleep', $basedate, $period);
			$minutesAfterWakeup = $this->fitbitphp->getTimeSeries('minutesAfterWakeup', $basedate, $period);
			$efficiency = $this->fitbitphp->getTimeSeries('efficiency', $basedate, $period);


			$sleepData = array();
			foreach($startTime as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['startTime'] = $value->value;				
			}

			foreach($minutesAwake as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['minutesAwake'] = $value->value;				
			}
			foreach($timeInBed as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['timeInBed'] = $value->value;				
			}
			foreach($minutesAsleep as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['minutesAsleep'] = $value->value;				
			}

			foreach($awakeningsCount as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['awakeningsCount'] = $value->value;				
			}
			foreach($minutesToFallAsleep as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['minutesToFallAsleep'] = $value->value;				
			}

			foreach($minutesAfterWakeup as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['minutesAfterWakeup'] = $value->value;				
			}

			foreach($efficiency as $value){
				$currentDate = $value->dateTime;
				$sleepData[$currentDate]['efficiency'] = $value->value;				
			}

			//insert into database
			foreach($sleepData as $key=>$value){
				$sql = "INSERT INTO Sleep(user_id, date, total_time, time_asleep, start_time, awaken_count, min_awake, min_to_asleep, min_after_wakeup, efficiency)
						VALUES (" . $this->session->userdata('user_id') . ", '" . $key . "', " . $value['timeInBed'] . ", " . $value['minutesAsleep'] . ", '" . $value['startTime'] . "', " . $value['awakeningsCount'] . ", " . $value['minutesAwake'] . ", " . $value['minutesToFallAsleep'] . ", " . $value['minutesAfterWakeup'] . ", " . $value['efficiency'] .")";
				$this->db->query($sql);	
			}
		}else{
			echo 'something was wrong';
		}


	}

	private function setFeeds(){
		if($this->session->userdata('user_id')){

			//subscribe to himself
			$user_id = $this->session->userdata('user_id');
			$sql = "INSERT INTO Subscription(user_id, subscriber_id)
					VALUES (" . $user_id . ", " . $user_id .")";
			$this->db->query($sql);

			//subscribe to system notifications
			$sql = "INSERT INTO Subscription(user_id, subscriber_id)
					VALUES (" . $user_id . ", 0)";
			$this->db->query($sql);
		}else{
			echo 'something was wrong';
		}
	}

	private function initPosts(){
		if($this->session->userdata('user_id')){

			$user_id = $this->session->userdata('user_id');
			$query = $this->db->query("SELECT * FROM Activity WHERE user_id='". $user_id ."'");
			$stepsData= array();
			if($query->num_rows()>0){
				foreach($query->result() as $row){
					$currentData = array(
						"date" =>(string) $row->date,
						"steps" => $row->steps,
						"calories" => $row->calories
					);
					array_push($stepsData, $currentData);
				}
			}

			foreach($stepsData as $value){
				if($value['steps']>=0){
					$timeStr = $value['date'] . ' 23:59:00';
					$sql = "INSERT INTO Post(user_id, type, time, description)
							VALUES (" . $user_id . ", 'activity', '". $timeStr . "', 'took " . $value['steps'] . " steps and he burned " . $value['calories'] . " calories.')";
					$this->db->query($sql);
				}else{
					echo "something was wrong";
				}
			}
		}
	}

	private function addSubscription(){
		$user_id = $this->session->userdata('user_id');
		$this->fitbitphp->addSubscription($user_id);

	}
}