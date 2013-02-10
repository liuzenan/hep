<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	function getActivityToday($user_id){
		$today = date("Y-m-d");
		$query = $this->db->get_where('activity', array('user_id'=>$user_id, 'date'=>$today));
		$vars = $query->row();
		if(empty($vars)) {
			$vars = new StdClass();
			$vars->user_id = $user_id;
			$vars->date =date("Y-m-d");
			$vars->steps = 0;
			$vars->floors= 0;
			$vars->calories = 0;
			$vars->active_score = 0;
			$vars->distance = 0;
			$vars->elevation = 0;
			$vars->min_sedentary = 0;
			$vars->min_lightlyactive = 0;
			$vars->min_lightlyactive = 0;
			$vars->min_veryactive = 0;
			$vars->activity_calories = 0;
		}
		return $vars;
	}

	function getActivityYesterday($user_id) {
		$yesterday = date("Y-m-d", time() - 60 * 60 * 24);
		$query = $this->db->get_where('activity', array('user_id'=>$user_id, 'date'=>$yesterday));
		$vars = $query->row();
		if(empty($vars)) {
			$vars = new StdClass();
			$vars->user_id = $user_id;
			$vars->date =date("Y-m-d", time() - 60 * 60 * 24);
			$vars->steps = 0;
			$vars->floors= 0;
			$vars->calories = 0;
			$vars->active_score = 0;
			$vars->distance = 0;
			$vars->elevation = 0;
			$vars->min_sedentary = 0;
			$vars->min_lightlyactive = 0;
			$vars->min_lightlyactive = 0;
			$vars->min_veryactive = 0;
			$vars->activity_calories = 0;
		}	
		return $vars;
	}

	function getMaxActivityToday() {
		$today = date("Y-m-d");
		//get activities data
		$sql = "SELECT max(a.steps) AS max_steps, max(a.floors) AS max_floors, max(a.distance) AS max_distance, max(a.calories) as max_calories
		FROM activity AS a
		WHERE a.date=?";
		$query = $this->db->query($sql, array($today));
		return $query->row();

	}


	function getAverageActivityToday(){
		$today = date("Y-m-d");
		//get activities data
		$sql = "SELECT avg(a.steps) AS avg_steps, avg(a.floors) AS avg_floors, avg(a.distance) AS avg_distance, avg(a.calories) as avg_calories
		FROM activity AS a
		WHERE a.date=? AND (a.steps>0 OR a.floors>0 OR a.distance>0)";
		$query = $this->db->query($sql, array($today));
		return $query->row();
	}

	function getSleepToday($user_id){
		$today = date("Y-m-d");
		$query = $this->db->get_where('sleep', array('user_id'=>$user_id, 'date'=>$today));
		$vars = $query->row();
		if(empty($vars)) {
			$vars = new StdClass();
			$vars->user_id = $user_id;
			$vars->date =date("Y-m-d");
			$vars->total_time= 0;
			$vars->time_asleep = 0;
			$vars->start_time = "00:00:00";
			$vars->awaken_count = 0;
			$vars->min_awake = 0;
			$vars->min_to_asleep = 0;
			$vars->min_after_wakeup = 0;
			$vars->efficiency = 0;
		}
		return $vars;
	}

	function getSleepYesterday($user_id) {
		$yesterday = date("Y-m-d", time() - 60 * 60 * 24);
		$query = $this->db->get_where('sleep', array('user_id'=>$user_id, 'date'=>$yesterday));
		$vars = $query->row();
		if(empty($vars)) {
			$vars = new StdClass();
			$vars->user_id = $user_id;
			$vars->date =$yesterday;
			$vars->total_time= 0;
			$vars->time_asleep = 0;
			$vars->start_time = "00:00:00";
			$vars->awaken_count = 0;
			$vars->min_awake = 0;
			$vars->min_to_asleep = 0;
			$vars->min_after_wakeup = 0;
			$vars->efficiency = 0;
		}
		return $vars;
	}


	function getAverageSleepToday(){
		$today = date("Y-m-d", time() - 60 * 60 * 24);
		//get activities data
		$sql = "SELECT avg(total_time) AS avg_time
		FROM sleep
		WHERE date='".$today."' AND total_time>0";
		$query = $this->db->query($sql);
		return $query->row();
	}




	//-------------------------

	function getDailySleepData($user_id, $date){
		$sql = "SELECT * FROM sleep
		WHERE user_id=". $user_id ." AND date='". $date ."'";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->row();
		}
	}

	function getLifetimeActivityData($user_id){
		$sql = "SELECT sum(steps) AS total_steps, sum(floors) AS total_floors, sum(calories) AS total_calories, sum(active_score) AS total_activescore, sum(distance) AS total_distance, sum(elevation) AS total_elevation, sum(activity_calories) AS total_activitycalories
		FROM activity
		WHERE user_id=". $user_id ."
		GROUP BY user_id";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->row();
		}
	}

	function getLifetimeSleepData($user_id){


	}


	function get_activity($startDate, $endDate){
		try {
			if($this->session->userdata('user_id')){
				$sql = "SELECT * FROM activity
				WHERE user_id = ". $this->session->userdata('user_id') ."
				AND date
				BETWEEN '". (string) $startDate ."' AND '" . (string) $endDate ."'
				ORDER BY date";

				$query = $this->db->query($sql);
				$resultSet['steps'] = array();
				$resultSet['floors'] = array();
				$resultSet['distance'] = array();
				$resultSet['calories'] = array();
				$resultSet['activity_calories'] = array();
				$resultSet['elevation'] = array();
				if($query->num_rows()>0){
					foreach($query->result() as $row){
						array_push($resultSet['steps'], $row->steps);
						array_push($resultSet['floors'], $row->floors);
						array_push($resultSet['calories'], $row->calories);
						array_push($resultSet['distance'], $row->distance);
						array_push($resultSet['activity_calories'], $row->activity_calories);
						array_push($resultSet['elevation'], $row->elevation);
					}

					return $resultSet;
				}
			}
		} catch (Exception $e) {

		}
	}


	function insert_intraday_activity($user_id, $date, $keypair){
		try {
			if($keypair){
				$this->fitbitphp->setOAuthDetails($keypair['token'], $keypair['secret']);
				$calories = $this->fitbitphp->getIntradayTimeSeries("calories",$date);
				$steps = $this->fitbitphp->getIntradayTimeSeries("steps",$date);
				$floors = $this->fitbitphp->getIntradayTimeSeries("floors",$date);
				$elevation = $this->fitbitphp->getIntradayTimeSeries("elevation",$date);

				$intradayCalories = $calories->{'activities-calories-intraday'};
				$intradaySteps = $steps->{'activities-steps-intraday'};
				$intradayFloors = $floors->{'activities-floors-intraday'};
				$intradayElevation = $elevation->{'activities-elevation-intraday'};

				$intradayActivityData = array();

				foreach($intradayCalories->dataset->intradayData as $value){
					$currentTime = (string) $value->time;
					$intradayActivityData[$currentTime]['calories'] = $value->value;
					$intradayActivityData[$currentTime]['level'] = $value->level;

				}

				foreach($intradaySteps->dataset->intradayData as $value){
					$currentTime = (string) $value->time;
					$intradayActivityData[$currentTime]['steps'] = $value->value;
				}
				foreach($intradayFloors->dataset->intradayData as $value){
					$currentTime = (string) $value->time;
					$intradayActivityData[$currentTime]['floors'] = $value->value;
				}
				foreach($intradayElevation->dataset->intradayData as $value){
					$currentTime = (string) $value->time;
					$intradayActivityData[$currentTime]['elevation'] = $value->value;
				}

				foreach ($intradayActivityData as $key => $value) {
					# code...
					$sql = "INSERT INTO intradayactivity(user_id, activity_time, steps, calories, calories_level, floors, elevation)
					VALUES (". $user_id .", '". $date . " " . $key ."', ". $value['steps'] .", ". $value['calories'] .", ". $value['level'] .", " . $value['floors'] . ", " . $value['elevation'] .")
					ON DUPLICATE KEY UPDATE user_id=user_id";

					$this->db->query($sql);					
				}
			}else{

			}	
		} catch (Exception $e) {

		}


	}

	function sync_activity($basedate, $period, $user_id=NULL, $keypair=NULL) {

		try {

			if($keypair){
				$this->fitbitphp->setOAuthDetails($keypair['token'], $keypair['secret']);
			}else if($this->session->userdata('oauth_token')&&$this->session->userdata('oauth_secret')){
				$this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
				$user_id = $this->session->userdata('user_id');
			}else{
				throw new Exception("no keypair");
			}


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
				VALUES (" . $user_id . ", '" . $key . "', " . $value['tracker_steps'] . ", " . $value['tracker_floors'] . ", " . $value['tracker_caloriesOut'] . ", " . $value['tracker_activeScore'] . ", " . $value['tracker_distance'] . ", " . $value['tracker_elevation'] . ", " . $value['tracker_minutesSedentary'] . ", " . $value['tracker_minutesLightlyActive'] . ", " . $value['tracker_minutesFairlyActive'] . ", " . $value['tracker_minutesVeryActive'] . ", " . $value['tracker_activityCalories'] . ")
				ON DUPLICATE KEY UPDATE steps= ". $value['tracker_steps'] .", floors= ". $value['tracker_floors'] .", calories= ". $value['tracker_caloriesOut'] .", active_score= ". $value['tracker_activeScore'] .", distance= " . $value['tracker_distance'] . ", elevation= ". $value['tracker_elevation'] . ", min_sedentary= " . $value['tracker_minutesSedentary'] . ", min_lightlyactive= " . $value['tracker_minutesLightlyActive'] . ", min_fairlyactive= " . $value['tracker_minutesFairlyActive'] .", min_veryactive= ". $value['tracker_minutesVeryActive'] . ", activity_calories= " . $value['tracker_activityCalories'];
				$this->db->query($sql);	
			}

		} catch (Exception $e) {

		}

	}

}