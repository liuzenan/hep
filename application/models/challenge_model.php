<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Challenge_model extends CI_Model{

	const table_challenge = 'challenge';
	const table_challenge_participant = 'challengeparticipant';
	const col_cp_uid = 'user_id';
	const col_cp_cid = 'challenge_id';
	const col_cp_id = 'id';
	const col_c_id = 'id';

	function __construct(){
		parent::__construct();
	}

	function joinChallenge($user_id, $challenge_id, $start_time, $end_time) {
		$data = array(
			'user_id'=>$user_id,
			'challenge_id'=>$challenge_id,
			'start_time'=>$start_time,
			'end_time'=>$end_time
			);
		$this->db->insert(Challenge_model::table_challenge_participant,$data);
		
	}

	function quitChallenge($id) {
		return $this->db->delete(Challenge_model::table_challenge_participant,
									array(Challenge_model::col_cp_id=>$id));
	}

	function completeChallenge($id, $complete_time) {
		$data = array('complete_time', $complete_time);
		$this->db->where('id',$id);
		$this->db->upate(Challenge_model::table_challenge_participant,
			$data);
	}

	function getHouseCurrentChallenges($house_id) {
		$sql = "SELECT * 
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id IN (SELECT id FROM user WHERE house_id = ?) 
		WHERE challengeparticipant.start_time < NOW() AND challengeparticipant.end_time > NOW() 
		GROUP BY challengeparticipant.challenge_id";
	
		$query = $this->db->query($sql, array($house_id));
		return $query->result();
	}

	function getHouseCompletedChallenges($house_id) {
		$sql = "SELECT challenge.* , count(challengeparticipant.challenge_id) as times
		FROM challenge
		INNER JOIN challengeparticipant
		ON challenge.id=challengeparticipant.challenge_id
		AND challengeparticipant.user_id IN (SELECT id FROM user WHERE house_id = ?) 
		WHERE challengeparticipant.complete_time > challengeparticipant.start_time
		GROUP BY challengeparticipant.challenge_id";
		
		$query = $this->db->query($sql, array($house_id));
		return $query->result();
	}

	function getIndividualCurrentChallenges($user_id){

		$sql = "SELECT *
		FROM challenge
		INNER JOIN challengeparticipant
		ON challenge.id=challengeparticipant.challenge_id
		AND challengeparticipant.user_id= ?
		WHERE challengeparticipant.start_time < NOW() AND challengeparticipant.end_time > NOW() 
		GROUP BY challengeparticipant.challenge_id";
	
		$query = $this->db->query($sql, array($user_id));
		return $query->result();
	}

	function getIndividualCompletedChallenges($user_id){
		$sql = "SELECT challenge.* , count(challengeparticipant.challenge_id) as times
		FROM challenge
		INNER JOIN challengeparticipant
		ON challenge.id=challengeparticipant.challenge_id
		AND challengeparticipant.user_id= ?
		WHERE challengeparticipant.complete_time > challengeparticipant.start_time
		GROUP BY challengeparticipant.challenge_id";

		$query = $this->db->query($sql, array($user_id));
		return $query->result();		
	}

	function getAllChallenges() {
		$query = $this->db->get(Challenge_model::table_challenge);
		return $query->result();
	}

	
}