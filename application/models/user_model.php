<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	function validateUser($user_id) {
		$sql = "SELECT id
		FROM   user
		WHERE  ( first_name IS NULL
			OR last_name IS NULL
			OR email IS NULL ) 
AND id=?";
return $this->db->query($sql, array($user_id))->row();
}

function loadUser($user_id) {
	$query = $this->db->get_where('user', array('id' => $user_id));
	if($query->num_rows()>0) {
		return $query->row();
	} else {
		show_error('Cannot find user', 501);
	}

}

function searchUser($name) {
	$sql = "SELECT Group_concat(first_name, ' ', last_name) AS name,
	user.*
	FROM   user
	GROUP  BY id
	HAVING ( Lower(name) LIKE Lower('%".$name."%') ) ";
	$query = $this->db->query($sql);
	return $query->result();
}

function loadUsers($uids) {
	$str = "";
	foreach($uids as $uid) {
		$str .= $uid.", ";
	}
	$str = substr($str,0,-2);
	$sql = "SELECT * FROM user WHERE id IN (" . implode(",", $uids) . ")";
	$query = $this->db->query($sql);
	$res = array();
	foreach($query->result() as $user) {
		$res[$user->id] = $user;
	}
	return $res;
}

function loadGroupMemberAvatar($house_id) {
	if($house_id == -1) {
		$query = $this->db->get_where('user', array('house_id' => $house_id, 'phantom'=>0));
	} else {
		$query = $this->db->get_where('user', array('house_id' => $house_id, 'phantom'=>0, 'hide_progress'=>0));
	}
	return $query->result();

}
function loadDailyReportUsers() {
	$sql = "SELECT id
	FROM   user
	WHERE  daily_email_unsub = 0";
	$query = $this->db->query($sql);
	return $query->result();
}
function insertAchievement($user_id, $achievement_id, $date){
	try {
		if($user_id&&$achievement_id&&$date){
			$sql="INSERT INTO userachievement
			VALUES(". $user_id .", ". $achievement_id .", '". $date ."')
			ON DUPLICATE KEY UPDATE user_id=user_id";
			$this->db->query($sql);
		}			
	} catch (Exception $e) {

	}

}

function getNotifications($user_id){
	try {

		$query = $this->db->query("SELECT * FROM notification WHERE user_id = " . $user_id);

		if ($query->num_rows()>0) {
					# code...
			$notification = $query->result();
			return $notification;
		}

	} catch (Exception $e) {

	}
}

public function unsubBadgeNotification($user_id) {
	$data = array('badge_email_unsub'=>1);
	$this->db->where('id',$user_id);
	$this->db->update('user', $data);
}	

public function unsubDailyNotification($user_id) {
	$data = array('daily_email_unsub'=>1);
	$this->db->where('id',$user_id);
	$this->db->update('user', $data);
}

public function unsubChallengeNotification($user_id) {
	$data = array('challenge_email_unsub'=>1);
	$this->db->where('id',$user_id);
	$this->db->update('user', $data);
}

function addNotification($user_id, $description, $url){
	try {
		$sql = "INSERT INTO notification(description, url, user_id)
		VALUES (" . $this->db->escape($description) . ", " . $this->db->escape($url) . ", " . $user_id . ")";
		$this->db->query($sql);
		$notification_id = $this->db->insert_id();
		return $notification_id;

	} catch (Exception $e) {

	}

}

function removeNotification($notification_id){
	try {
		$this->db->query("DELETE FROM notification WHERE id = " . $notification_id);
		return true;
	} catch (Exception $e) {

	}
}

}