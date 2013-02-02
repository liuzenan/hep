<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenge_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getCurrentChallenges($user_id){
		$sql = "SELECT *
				FROM challenge
				INNER JOIN challengeparticipant
				ON challenge.id=challengeparticipant.challenge_id
				AND challengeparticipant.user_id=". $user_id ."
				WHERE challengeparticipant.completed = 0
				GROUP BY challengeparticipant.challenge_id";

		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$myChallenge = $query->result();
		}

		return $myChallenge;
	}

	function getAllChallenges($user_id){
		$sql = "SELECT challenge.id as challenge_id, count(challengeparticipant.challenge_id) as num_times
				FROM challenge
				INNER JOIN challengeparticipant
				ON challenge.id=challengeparticipant.challenge_id
				AND challengeparticipant.user_id=". $user_id ."
				WHERE challengeparticipant.completed = 0
				GROUP BY challengeparticipant.challenge_id";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$myChallenge = $query->result();
		}

		$query = $this->db->query("SELECT * FROM challenge");
		$resultSet = array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$currentChallenge = array();
				$currentChallenge['times'] = 0;
				$currentChallenge['id'] = $row->id;
				$currentChallenge['title'] = $row->title;
				$currentChallenge['description'] = $row->description;
				$currentChallenge['badge_pic'] = $row->badge_pic;
				$currentChallenge['points'] = $row->points;
				try {
					if(isset($myChallenge)&&sizeof($myChallenge)>0){
						foreach($myChallenge as $badge){
							if(!strcmp($currentChallenge['id'],$badge->challenge_id)){
								$currentChallenge['times']=$badge->num_times;
							}		

						}						
					}
					
				} catch (Exception $e) {
					
				}
				array_push($resultSet, $currentChallenge);
			}
		}

		return $resultSet;			
	}

	
}