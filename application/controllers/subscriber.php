<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber extends CI_Controller {


	public function index(){
		$this->activities();
		$this->sleep();
	}

	public function activities(){
		try {
			$notifications = $this->getNotification();
			if($notifications){
				foreach($notifications as $data){
					$type = $data['collectionType'];
					if(!strcmp($data['collectionType'],"activities")){
						$user_id = $data['user_id'];
						$date = $data['date'];
						$this->getActivities($user_id, $date);
						$this->updateAchievement($user_id, $date);
					}else{
						
					}
				}
			}
		} catch (Exception $e) {
			
		}
		
	}

	public function sleep(){
		try {
			$notifications = $this->getNotification();
			if($notifications){
				foreach($notifications as $data){
					$type = $data['collectionType'];
					if(!strcmp($data['collectionType'],"sleep")){
						$user_id = $data['user_id'];
						$date = $data['date'];
						$this->getSleep($user_id, $date);
					}else{
						
					}
				}
			}
		} catch (Exception $e) {
			
		}
	}

	private function getActivities($user_id, $date){

		$keypair = $this->getUserKeyPair($user_id);
		if($keypair){
			try {
				$this->load->model('Activities_model','activities');
				$this->activities->insert_intraday_activity($user_id, $date, $keypair);
				$this->activities->sync_activity($date, '1d', $user_id, $keypair);
			} catch (Exception $e) {
				
			}
		}

	}

	private function getSleep($user_id, $date){
		$keypair = $this->getUserKeyPair($user_id);
		if($keypair){
			try {
				$basedate = $date;
				$period = '1d';
				$this->fitbitphp->setOAuthDetails($keypair['token'],$keypair['secret']);

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

				foreach($sleepData as $key=>$value){
					$sql = "INSERT INTO Sleep(user_id, `date`, total_time, time_asleep, start_time, awaken_count, min_awake, min_to_asleep, min_after_wakeup, efficiency)
							VALUES (" . $this->session->userdata('user_id') . ", '" . $key . "', " . $value['timeInBed'] . ", " . $value['minutesAsleep'] . ", '" . $value['startTime'] . "', " . $value['awakeningsCount'] . ", " . $value['minutesAwake'] . ", " . $value['minutesToFallAsleep'] . ", " . $value['minutesAfterWakeup'] . ", " . $value['efficiency'].")
							ON DUPLICATE KEY UPDATE total_time=" . $value['timeInBed'] . ", time_asleep= " . $value['minutesAsleep'] . ", start_time= '" . $value['startTime'] . "', awaken_count= " . $value['awakeningsCount'] . ", min_awake= " . $value['minutesAwake'] .", min_to_asleep= " . $value['minutesToFallAsleep'] . ", min_after_wakeup= " . $value['minutesAfterWakeup'] . ", efficiency= " . $value['efficiency'];
					$this->db->query($sql);	
				}
			} catch (Exception $e) {
				
			}
		}

	}

	private function updatePost(){

	}

	public function updateAchievement($user_id, $date){
		$this->load->model('Activities_model','activities');
		$this->load->model('User_model','usermodel');

		$dailyActivityData = $this->activities->getDailyActivityData($user_id, $date);
		$dailySleepData = $this->activities->getDailySleepData($user_id, $date);

		$lifetimeActivityData = $this->activities->getLifetimeActivityData($user_id);

		if($dailyActivityData){
			$dailySteps = intval($dailyActivityData->steps);
			$dailyFloors = intval($dailyActivityData->floors);
			$dailyCalories = intval($dailyActivityData->calories);


			$sql = "SELECT id FROM achievement
					WHERE type='daily' AND activity='steps'
					AND min_val<=" . $dailySteps;

			$query = $this->db->query($sql);


			echo $dailySteps;
			echo "\n";
			echo $dailyFloors;
			echo "\n";
			echo $dailyCalories;


			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...
					$this->usermodel->insertAchievement($user_id, $row->id, $date);
				}
			}

			$sql = "SELECT id FROM achievement
					WHERE type='daily' AND activity='floors'
					AND min_val<=" . $dailyFloors;

			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...
					$this->usermodel->insertAchievement($user_id, $row->id, $date);
				}
			}

			$sql = "SELECT id FROM achievement
					WHERE type='daily' AND activity='calories'
					AND min_val<=" . $dailyCalories;

			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...
					$this->usermodel->insertAchievement($user_id, $row->id, $date);
				}
			}
		}

		if($dailySleepData){
			$dailySleepEff = intval($dailySleepData->efficiency);
			$dailySleepStartTime = intval($dailySleepData->start_time);
			$dailySleepTime = intval($dailySleepData->time_asleep);

			$sql = "SELECT id FROM achievement
					WHERE type='daily' AND activity='sleepeff'
					AND min_val<=" . $dailySleepEff;

			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...
					$this->usermodel->insertAchievement($user_id, $row->id, $date);
				}
			}


			$sql = "SELECT id FROM achievement
					WHERE type='daily' AND activity='asleeptime'
					AND min_val<=" . $dailySleepTime;

			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...
					$this->usermodel->insertAchievement($user_id, $row->id, $date);
				}
			}

		}
		
		if($lifetimeActivityData){

			$lifetimeDistance = intval($lifetimeActivityData->total_distance);
			$lifetimeFloors = intval($lifetimeActivityData->total_floors);




			$sql = "SELECT id FROM achievement
					WHERE type='lifetime' AND activity='distance'
					AND min_val<=" . $lifetimeDistance;

			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...

					$sql = "SELECT * FROM userachievement WHERE user_id=". $user_id ." AND achievement_id=". $row->id;
					$query = $this->db->query($sql);
					if($query->num_rows()==0){
						$this->usermodel->insertAchievement($user_id, $row->id, $date);
					}

				}
			}

			$sql = "SELECT id FROM achievement
					WHERE type='lifetime' AND activity='floors'
					AND min_val<=" . $lifetimeFloors;

			$query = $this->db->query($sql);

			if($query->num_rows()>0){
				foreach ($query->result() as $row) {
					# code...
					$sql = "SELECT * FROM userachievement WHERE user_id=". $user_id ." AND achievement_id=". $row->id;
					$query = $this->db->query($sql);
					if($query->num_rows()==0){
						$this->usermodel->insertAchievement($user_id, $row->id, $date);
					}
				}
			}
		}
	}

	private function getUserKeyPair($userId){
		if($userId){
			try {
				$sql = "SELECT oauth_token, oauth_secret FROM User
						WHERE id=". $userId;
				$query = $this->db->query($sql);

				if($query->num_rows()>0){
					$row=$query->row();
					$keypair['token'] = $row->oauth_token;
					$keypair['secret'] = $row->oauth_secret;

					return $keypair;
				}

			} catch (Exception $e) {
				
			}
		}
	}

	private function getNotification(){
		try {

			$xml = simplexml_load_file($_FILES['updates']['tmp_name']);
			if($xml){
				$headers = apache_request_headers();
				$signature = $headers['X-Fitbit-Signature'];
				//$expected = hash_hmac('sha1', $_FILES['updates']['tmp_name'], '45e414dd49784ec3872a8ebbb74dcbb9&');
			if(!$signature){
				$sql = "INSERT INTO Updates(`update`)
					VALUES ('no signature, IP: ". $this->input->ip_address() ."')";
				$this->db->query($sql);	
			}else{
					$notifications=array();
					foreach ($xml as $updatedResource){
						if($updatedResource->subscriptionId){
							$user = explode("-", ((string)$updatedResource->subscriptionId), 2);
							$data['user_id'] = $user[0];
							$data['fitbit_id'] = (string) $updatedResource->ownerId;
							$data['owner_type'] = (string) $updatedResource->ownerType;
							$data['date'] = (string) $updatedResource->date;
							$data['collectionType'] = (string) $updatedResource->collectionType;
							array_push($notifications, $data);
							$sql = "INSERT INTO Updates(`update`,user_id,type)
									VALUES ('User data synced, IP: ". $this->input->ip_address() ."', ". $data['user_id'] .", '". $data['collectionType'] ."')";
							$this->db->query($sql);
						}
					}
			}
		}else{
			$sql = "INSERT INTO Updates(`update`)
					VALUES ('empty contents, IP: ". $this->input->ip_address() ."')";
			$this->db->query($sql);	
		}


		} catch (Exception $e) {
			$sql = "INSERT INTO Updates(`update`)
					VALUES ('Error, IP: ". $this->input->ip_address() ."')";
			$this->db->query($sql);
		}
		header('HTTP/1.0 204 No Content');
		header('Content-Length: 0',true);
		header('Content-Type: text/html',true);
		flush();
		return $notifications;
	}
}