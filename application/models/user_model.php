<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{

	// var $first_name = '';
	// var $last_name = '';
	// var $fitbit_id ='';
	// var $oauth_secret ='';
	// var $oauth_token ='';
	// var $profile_pic ='';
	// var $gender = '';
	// var $house_id = 0;
	// var $username = '';
	// var $email = '';
	// var $admin = 0;
	// var $phantom = 0;
	// var $staff = 0;
	// var $leader = 0;

	function __construct(){
		parent::__construct();
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

}