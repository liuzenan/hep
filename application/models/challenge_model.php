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

	function loadUserChallenge($user_id, $start_time, $end_time) {
		$sql = "SELECT * 
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id = ?
		WHERE challengeparticipant.start_time <= ? AND challengeparticipant.end_time >= ?";
		$query = $this->db->query($sql, array($user_id, $start_time, $end_time));
		return $query->result();
	}

	function loadChallenge($challenge_id) {
		$query = $this->db->get_where(Challenge_model::table_challenge, array('id' => $challenge_id));
		if($query->num_rows()>0) {
			return $query->row();
		}
	}
	function joinChallenge($user_id, $challenge_id, $start_time, $end_time) {
		$data = array(
			'user_id'=>$user_id,
			'challenge_id'=>$challenge_id,
			'start_time'=>$start_time,
			'end_time'=>$end_time
			);
		$this->db->insert(Challenge_model::table_challenge_participant,$data);
		return $this->db->insert_id();
		
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
		";

		$query = $this->db->query($sql, array($house_id));
		$challenges = $query->result();
		$res = array();
		foreach($challenges as $c) {
			if(!isset($res[$c->user_id])) {
				$res[$c->user_id] = array();
			}
			$res[$c->user_id][] = $c;
		}
		
		return $res;
	}

	function getHouseCompletedChallenges($house_id) {
		$sql = "SELECT challenge.* , count(challengeparticipant.id) as times
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
		/*
		//$this->load->model('Activity_model');
		$status = $this->Activity_model->getActivityStats($user_id, $c->start_time, $c->start_time, $c->end_time);
		foreach($data as $c) {
			if($c->steps_value != 0) {
				$c->steps_progress = num_format($status->steps/$c->steps_value,2);
			}
			if($c->floor_value != 0) {
				$c->steps_progress = num_format($status->floors/$c->floor_value,2);
			}
			if($c->sleep_value != 0) {
				$value = $this->Activity_model->getSleepToday($user_id);
				$c->sleep_value_progress = num_format($value->total_time/$c->sleep_value, 2);
			}

			if($c->sleep_time != 0) {
				$value = $this->Activity_model->getSleepStartTime($user_id);
				if(empty($value)) {
					$c->sleep_time_progress = 0;
				} else {
					$c->sleep_time_progress = 1;
				}
			}
		}*/
	}
	function updateActivityProgress() {
		$ci =& get_instance();
		$ci->load->model('Mymodel');
		$ci->load->model('Activity_model');
		$status = $this->Activity_model->getActivityStats($user_id, $c->start_time, $c->start_time, $c->end_time);
		foreach($data as $c) {
			if($c->steps_value != 0) {
				$progress = num_format($status->steps/$c->steps_value,2);
				$this->updateProgress($c->id, $progress);
			}
			if($c->floor_value != 0) {
				$progress = num_format($status->floors/$c->floor_value,2);
				$this->updateProgress($c->id, $progress);

			}
			if($c->sleep_value != 0) {
				$value = $this->Activity_model->getSleepToday($user_id);
				$progress = num_format($value->total_time/$c->sleep_value, 2);
				$this->updateProgress($c->id, $progress);
			}

			if($c->sleep_time != 0) {
				$value = $this->Activity_model->getSleepStartTime($user_id);
				if(empty($value)) {
					$progress = 0;
				} else {
					$progress = 1;
				}
				$this->updateProgress($c->id, $progress);
			}
		}
	}

	function updateProgress($cp_id, $progress) {
		if($progress >= 1.0) {
			$data = array('progress'=>1, 'complete_time'=>date("Y-m-d H:i:s"));

		} else {
			$data = array('progress'=>$progress);

		}
		$this->db->where('id',$cp_id);
		$this->db->update(Challenge_model::table_challenge_participant, $data);
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

	function getIndividualChallengeCount($user_id) {
		$sql = "SELECT Count(cp.id) AS count
		FROM   challengeparticipant AS cp
		WHERE  cp.user_id = ?
		AND cp.complete_time > cp.start_time";
		return $this->db->query($sql,array($user_id))->row()->count;
	}

	function getAverageChallengeCount() {
		$sql1 = "SELECT count(id) AS count
		FROM   user
		WHERE  admin = 0
		AND phantom = 0
		AND staff = 0";
		$count = $this->db->query($sql1)->row()->count;

		$sql2 = "SELECT Count(cp.id) AS total
		FROM   challengeparticipant AS cp
		LEFT JOIN (SELECT id
			FROM   user
			WHERE  admin = 0
			AND phantom = 0
			AND staff = 0) AS temp
ON cp.user_id = temp.id
WHERE  cp.complete_time > cp.start_time";
$total = $this->db->query($sql2)->row()->total;

return $total/$count;		
}

function getAllChallenges() {
	$query = $this->db->get(Challenge_model::table_challenge);
	return $query->result();
}

function getLearderboard() {
	$sql = "SELECT u.first_name  AS firstname,
	u.last_name   AS lastname,
	u.profile_pic AS avatar,
	u.house_id    AS house_id,
	h.name        AS house,
	Count(cp.id)  AS score
	FROM   user AS u,
	house AS h,
	challengeparticipant AS cp
	WHERE  u.house_id = h.id
	AND cp.user_id = u.id
	AND cp.complete_time > cp.start_time
	AND u.phantom = 0
	AND u.staff = 0
	GROUP BY u.id
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 10";
	$query = $this->db->query($sql);
	return $query->result();
}

function getLearderboardByGender($gender) {
	$sql = "SELECT u.first_name  AS firstname,
	u.last_name   AS lastname,
	u.profile_pic AS avatar,
	u.house_id    AS house_id,
	h.name        AS house,
	Count(cp.id)  AS score
	FROM   user AS u,
	house AS h,
	challengeparticipant AS cp
	WHERE  u.house_id = h.id
	AND cp.user_id = u.id
	AND cp.complete_time > cp.start_time
	AND u.gender = ?
	AND u.phantom = 0
	AND u.staff = 0
	GROUP BY u.id
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 10";
	$query = $this->db->query($sql, array($gender));
	return $query->result();
}

function getTutorLearderboard() {
	$sql = "SELECT u.first_name  AS firstname,
	u.last_name   AS lastname,
	u.profile_pic AS avatar,
	u.house_id    AS house_id,
	h.name        AS house,
	Count(cp.id)  AS score
	FROM   user AS u,
	house AS h,
	challengeparticipant AS cp
	WHERE  u.house_id = h.id
	AND cp.user_id = u.id
	AND cp.complete_time > cp.start_time
	AND u.phantom = 0
	AND (u.staff = 1)
	GROUP BY u.id
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 10";
	$query = $this->db->query($sql);
	return $query->result();
}

function getHouseLeaderboard() {
	$sql="SELECT
	u.house_id    AS house_id,
	h.name        AS house_name,
	h.picture     AS picture,
	sum(c.points)  AS score,
	Count(DISTINCT u.id) as user_num,
	GROUP_CONCAT(DISTINCT u.profile_pic) as avatars
	FROM   user AS u,
	house AS h,
	challengeparticipant AS cp,
	challenge as c
	WHERE  u.house_id = h.id
	AND c.id = cp.challenge_id
	AND cp.user_id = u.id
	AND cp.complete_time > cp.start_time
	AND u.phantom = 0
	AND u.staff = 0
	GROUP BY h.id
	ORDER BY sum(c.points) DESC, sum(cp.complete_time-cp.start_time) ASC";
	$query = $this->db->query($sql);
	return $query->result();
}

function getHouseRankAndPoints($house_id) {
	$leaderboard = $this->getHouseLeaderboard();
	$rank = 0;
	foreach($leaderboard as $house) {
		$rank++;
		if($house->house_id == $house_id) {
			return array('rank'=>$rank, 
				'points'=>$house->score, 
				'house_id'=>$house->house_id, 
				'house_name'=>$house->house_name,
				'picture'=>$house->picture  );
		}
	}
}
function getMyHouseStats($house_id) {
	$sql ="SELECT
	u.first_name  AS firstname,
	u.last_name   AS lastname,
	u.profile_pic AS avatar,
	u.leader      AS is_leader,
	u.id 		  AS user_id,
	Count(cp.id)  AS score
	FROM   user AS u,
	challengeparticipant AS cp
	WHERE  u.house_id = ?
	AND cp.user_id = u.id
	AND cp.complete_time > '0000-00-00 00:00:00'
	AND u.phantom = 0
	AND u.staff = 0
	GROUP BY u.id
	ORDER BY count(cp.id)";
	$query = $this->db->query($sql, array($house_id));
	$stats = $query->result();
	$res = array();
	foreach($stats as $s) {
		$res[$s->user_id] = $s;
	}
	return $res;
}

}