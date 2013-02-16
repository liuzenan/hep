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

	function getLastUpdate($user_id) {
		$sql="SELECT Max(last_update) as time
				FROM   activity	
				WHERE  user_id = ?";
		$lastupdate = $this->db->query($sql, array($user_id))->row();
		if(empty($lastupdate)) {
			return "2013-02-14 00:00:00";
		}else {
			return $lastupdate->time;
		}
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
		$sql = "SELECT CEILING(avg(a.steps)) AS avg_steps, CEILING(avg(a.floors)) AS avg_floors, CEILING(avg(a.distance)) AS avg_distance, CEILING(avg(a.calories)) as avg_calories
		FROM activity AS a
		WHERE a.date=? AND (a.steps>0 OR a.floors>0 OR a.distance>0)";
		$query = $this->db->query($sql, array($today));
		return $query->row();
	}

	function getSleepData($user_id, $date){
		$sql = "SELECT Sum(time_asleep) AS total_time,
		date
		FROM   sleep
		WHERE  user_id = ?
		AND date = ?
		GROUP BY date";
		$query = $this->db->query($sql, array($user_id, $date));
		$vars = $query->row();
		if(empty($vars)) {
			$vars = new StdClass();
			$vars->user_id = $user_id;
			$vars->date =$date;
			$vars->total_time= 0;
		}
		return $vars;
	}

	function getSleepStartTime($user_id, $start) {
		$offset = strtotime("23:59:59") - strtotime($start);
		$sql = "SELECT start_time FROM sleep WHERE total_time>=? AND start_time<=? AND user_id=?";
		return $this->db->query($sql, array($offset, $start, $user_id))->row();
	}
	function getActivityStats($user_id, $start, $end) {
		$sql = "SELECT Sum(steps)     AS steps,
		Sum(calories)  AS calories,
		Sum(floors)    AS floors,
		Sum(elevation) AS elevation
		FROM   intradayactivity
		WHERE  user_id = ?
		AND activity_time>= ?
		AND activity_time<= ?";
		$query = $this->db->query($sql, array($user_id, $start, $end));
		return $query->row();
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
				$minute = 0;
				foreach($intradayCalories->dataset->intradayData as $value){

					if($minute%10 == 0) {
						$currentTime = (string) $value->time;
						$intradayActivityData[$currentTime]['calories'] = $value->value;
						$intradayActivityData[$currentTime]['level'] = $value->level;
					}else {
						$intradayActivityData[$currentTime]['calories'] += $value->value;
						$intradayActivityData[$currentTime]['level'] += $value->level;
					}
					$minute++;
				}
				$minute = 0;
				foreach($intradaySteps->dataset->intradayData as $value){
					if($minute%10 == 0) {
						$currentTime = (string) $value->time;
						$intradayActivityData[$currentTime]['steps'] = $value->value;
					}else {
						$intradayActivityData[$currentTime]['steps'] += $value->value; 
					}  
					$minute++; 
				}
				$minute = 0;

				foreach($intradayFloors->dataset->intradayData as $value){
					if($minute%10 == 0) {
						$currentTime = (string) $value->time;
						$intradayActivityData[$currentTime]['floors'] = $value->value;
					}else {
						$intradayActivityData[$currentTime]['floors'] += $value->value;
					}
					$minute++;
				}
				$minute = 0;

				foreach($intradayElevation->dataset->intradayData as $value){
					if($minute%10 == 0) {
						$currentTime = (string) $value->time;
						$intradayActivityData[$currentTime]['elevation'] = $value->value;

					} else {
						$intradayActivityData[$currentTime]['elevation'] += $value->value;
					}
					$minute++;
				}

				foreach ($intradayActivityData as $key => $value) {
					# code...
					$sql = "INSERT INTO intradayactivity(user_id, activity_time, steps, calories, calories_level, floors, elevation)
					VALUES (". $user_id .", '". $date . " " . $key ."', ". $value['steps'] .", ".
						$value['calories'] .", ". $value['level'] .", " . $value['floors'] . ", " . $value['elevation'] .") ON DUPLICATE KEY UPDATE user_id=user_id";

					$this->db->query($sql);					
				}
			}else{

			}	
		} catch (Exception $e) {

		}
	}

	function getChallengeCompletionTime($user_id, $start, $end, $threshold,$type) {

		$sql = "SELECT y.activity_time,
				       y.%s,
				       y.user_id
				FROM   (SELECT t.activity_time,
				               t.%s,
				               t.user_id,
				               (SELECT Sum(x.%s)
				                FROM   intradayactivity AS x
				                WHERE  x.user_id = ?
				                   AND x.activity_time <= ?
				                   AND x.activity_time >= ?
				                   AND x.activity_time <= t.activity_time
				                   AND x.user_id = t.user_id) AS running_total
				        FROM   intradayactivity AS t
				         WHERE  t.activity_time <= ?
           					AND t.activity_time >= ?
				        ORDER  BY t.activity_time) AS y
				WHERE  y.running_total >= ?
				ORDER  BY y.activity_time
				LIMIT  1";
		$sql = sprintf($sql, $type, $type, $type);
		$result = $this->db->query($sql, array($user_id, $end, $start, $end, $start, $threshold));
		if($result->num_rows()>0) {
			return $result->row()->activity_time;
		} else {
			return $end;
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
				$sql = "INSERT INTO activity(user_id, date, steps, floors, calories, active_score, distance, elevation, min_sedentary,
					min_lightlyactive, min_fairlyactive, min_veryactive, activity_calories)
VALUES (" . $user_id . ", '" . $key . "', " . $value['tracker_steps'] . ", " 
	. $value['tracker_floors'] . ", " . $value['tracker_caloriesOut'] . ", " 
	. $value['tracker_activeScore'] . ", " . $value['tracker_distance'] . ", " 
	. $value['tracker_elevation'] . ", " . $value['tracker_minutesSedentary'] . ", " 
	. $value['tracker_minutesLightlyActive'] . ", " . $value['tracker_minutesFairlyActive'] . ", "
	. $value['tracker_minutesVeryActive'] . ", " . $value['tracker_activityCalories'] . ")
ON DUPLICATE KEY UPDATE last_update=NOW(), steps= ". $value['tracker_steps'] .", floors= ". $value['tracker_floors']
.", calories= ". $value['tracker_caloriesOut'] .", active_score= ". $value['tracker_activeScore']
.", distance= " . $value['tracker_distance'] . ", elevation= ". $value['tracker_elevation']
. ", min_sedentary= " . $value['tracker_minutesSedentary'] . ", min_lightlyactive= "
. $value['tracker_minutesLightlyActive'] . ", min_fairlyactive= "
. $value['tracker_minutesFairlyActive'] .", min_veryactive= "
. $value['tracker_minutesVeryActive'] . ", activity_calories= "
. $value['tracker_activityCalories'];
$this->db->query($sql);	
}

} catch (Exception $e) {

}

}

}