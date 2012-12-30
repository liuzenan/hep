<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activities_model extends CI_Model{

	function __construct(){
		parent::__construct();
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