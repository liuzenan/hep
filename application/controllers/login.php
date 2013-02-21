<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{
	const BASELINE = "2013-02-13";
	public function index(){
		if(!$this->session->userdata('user_id')){
			$this->fitbitphp->initSession(base_url() . "login/callBack");
			$this->callBack();
		}else{
			redirect(base_url() . "home");
		}
	}

	public function callBack(){

		$this->fitbitphp->initSession(base_url() . "login/callBack");
		$xml = $this->fitbitphp->getProfile();
		$username =(string) $xml->user->displayName;
		$fitbit_id =(string) $xml->user->encodedId;
		$oauth_token = $this->fitbitphp->getOAuthToken();
		$oauth_secret = $this->fitbitphp->getOAuthSecret();
		$profile_pic =(string) $xml->user->avatar;
		$gender =(string) $xml->user->gender;
		$query = $this->db->query("SELECT fitbit_id FROM user WHERE fitbit_id = '" . $fitbit_id . "'");

			//check if user is in database
		$user_exist = true;
		if($query->num_rows()==0){
				//user not exists
			$sql = "INSERT INTO user(fitbit_id, oauth_token, oauth_secret, profile_pic, gender, username)
			VALUES (" . $this->db->escape($fitbit_id) . ", " . $this->db->escape($oauth_token) . ", " . $this->db->escape($oauth_secret) . ", " . $this->db->escape($profile_pic) . ", " . $this->db->escape($gender) . ", " . $this->db->escape($username) . ")";
			$this->db->query($sql);
			$user_exist = false;
		}
			//set session data
		$query = $this->db->query("SELECT * FROM user WHERE fitbit_id = '" . $fitbit_id . "'");
		if($query->num_rows()>0){
			$row = $query->row();
			$userdata = array(
				'user_id' => $row->id,
				'fibit_id' => $fitbit_id,
				'oauth_secret' => $oauth_secret,
				'oauth_token' => $oauth_token,
				'username' => $username,
				'avatar' => $profile_pic,
				'isadmin'=> $row->admin,
				'isleader'=> $row->leader,
				'isTutor' => $row->staff
				);		
			$this->session->set_userdata($userdata);

			if($user_exist){
				//update user authen token and secret
				$authData=array(
					'oauth_token'=>$oauth_token,
					'oauth_secret'=>$oauth_secret
					);

				$this->db->where('id',$this->session->userdata('user_id'));
				$this->db->update('user', $authData);

				//check if email is in database
				if($row->email==NULL){
					redirect(base_url() . "signup");
				}else{
					redirect(base_url() . "home");
				}
			}else{
				$this->load->view("loading");
			}

		}

	}

	public function firstRun(){
		//get user tracker data from fitbit, this might be slow... need to optimize later
		try{
			$this->getActivites();
			$this->getSleep();
			//$this->initPosts();
			$this->addSubscription();	
			$msg['success'] = true;
		} catch (Exception $E){
			$msg['success'] = false;
		}

		echo json_encode($msg);
	}

	private function getActivites(){
		$user_id = $this->session->userdata('user_id');
		$this->load->model('Activity_model','activities');
		$this->activities->sync_activity('today', Login::BASELINE, $user_id);
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
			$period = Login::BASELINE;

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
				$sql = "INSERT INTO sleep(user_id, date, total_time, time_asleep, start_time, awaken_count, min_awake, min_to_asleep, min_after_wakeup, efficiency)
				VALUES (" . $this->session->userdata('user_id') . ", '" . $key . "', " . $value['timeInBed'] . ", " . $value['minutesAsleep'] . ", '" . $value['startTime'] . "', "
					. $value['awakeningsCount'] . ", " . $value['minutesAwake'] . ", " . $value['minutesToFallAsleep'] .
					", " . $value['minutesAfterWakeup'] . ", " . $value['efficiency'] .")";
$this->db->query($sql);	
}
}else{
	echo 'something was wrong';
}


}

private function initPosts(){
	if($this->session->userdata('user_id')){

		$user_id = $this->session->userdata('user_id');
		$query = $this->db->query("SELECT * FROM activity WHERE user_id='". $user_id ."'");
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

		
	}
}

public function facebookLogin(){
	$facebookUsername = $this->input->post('username');
	$query = $this->db->query("SELECT * FROM user WHERE username = '" . $facebookUsername . "'");
	if ($query->num_rows()>0) {
		$row = $query->row();
		if (empty($row->email)) {
			$msg['success'] = false;
			$msg['error'] = 'noemail';
		} else {
			$userdata = array(
				'user_id' => $row->id,
				'fibit_id' => $row->fitbit_id,
				'oauth_secret' => $row->oauth_secret,
				'oauth_token' => $row->oauth_token,
				'username' => $row->username,
				'avatar' => $row->profile_pic,
				'isadmin'=> $row->admin,
				'isleader'=> $row->leader,
				'isTutor' => $row->staff
				);		
			$this->session->set_userdata($userdata);
			$msg['success'] = true;				
		}
	} else {
		$msg['success'] = false;
		$msg['error'] = 'nouser';
	}

	echo json_encode($msg);
}

public function linkWithFacebook(){
	$email = $this->input->post("email");
	$username = $this->input->post("username");
	$query = $this->db->query("SELECT * FROM user WHERE email = " . $this->db->escape($email));
	if ($query->num_rows()>0) {
		$row = $query->row();
		$sql = "UPDATE user SET username = " . $this->db->escape($username) . "WHERE id = " . $row->id;
		$this->db->query($sql);
		$userdata = array(
			'user_id' => $row->id,
			'fibit_id' => $row->fitbit_id,
			'oauth_secret' => $row->oauth_secret,
			'oauth_token' => $row->oauth_token,
			'username' => $this->db->escape($username),
			'avatar' => $row->profile_pic,
			'isadmin'=> $row->admin,
			'isleader'=> $row->leader,
			'isTutor' => $row->staff 
			);		
		$this->session->set_userdata($userdata);			
		$msg["success"] = true;
	} else {
		$msg["success"] = false;
	}

	echo json_encode($msg);
}

private function addSubscription(){

	try {
		$user_id =(string) $this->session->userdata('user_id');
		$activity_id = $user_id . "-activities";
		$sleep_id = $user_id . "-sleep";
		$this->fitbitphp->addSubscription($activity_id, "/activities", "activities");
		$this->fitbitphp->addSubscription($sleep_id, "/sleep", "sleep");
	} catch (Exception $e) {
	}
}
}