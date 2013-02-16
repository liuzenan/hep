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

	function loadUserChallenge($user_id, $date) {
		$sql = "SELECT * 
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id = ?
		WHERE DATE(challengeparticipant.start_time) = ?";
		$query = $this->db->query($sql, array($user_id, $date));
		return $query->result();
	}

	function loadJoinedCategory($user_id, $date) {
		$sql = "SELECT challenge.category, challenge.id as challenge_id, challengeparticipant.id as cp_id
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id = ?
		WHERE DATE(challengeparticipant.start_time) = ? ";
		$query = $this->db->query($sql, array($user_id, $date));
		return $query->result();
	}
	function loadChallenge($challenge_id) {
		$query = $this->db->get_where(Challenge_model::table_challenge, array('id' => $challenge_id));
		if($query->num_rows()>0) {
			return $query->row();
		}
	}
	function joinChallenge($user_id, $challenge_id, $category, $start_time, $end_time) {
		$data = array(
			'user_id'=>$user_id,
			'challenge_id'=>$challenge_id,
			'category'=>$category,
			'start_time'=>$start_time,
			'end_time'=>$end_time
			);
		$this->db->insert(Challenge_model::table_challenge_participant,$data);

		return $this->db->insert_id();
	}

	function checkChallengeQuota() {

	}
	
	public function carryOverChallenges() {
		$now = date("Y-m-d G:i:s",time());	
		$ystd = date("Y-m-d G:i:s",time() - 24*60*60);
		$sql = "SELECT DISTINCT c1.user_id
		FROM   challengeparticipant AS c1
		WHERE  c1.user_id IN (SELECT DISTINCT user_id
			FROM   challengeparticipant
			WHERE  start_time <= ?
			AND end_time >= ? )
AND c1.user_id NOT 
IN (SELECT DISTINCT user_id
	FROM   challengeparticipant
	WHERE  start_time <= ?
	AND end_time >= ?)";
$uids = $this->db->query($sql, array($ystd, $ystd, $now, $now))->result();
foreach($uids as $uid) {
	$sql2="SELECT distinct challenge_id
	FROM   challengeparticipant
	WHERE  start_time <= ?
	AND end_time >= ?
	AND user_id=?";
	$cids = $this->db->query($sql2, array($ystd, $ystd, $uid->user_id))->result();
	foreach($cids as $cid) {
		$this->joinChallenge($uid->user_id, $cid->challenge_id, date("Y-m-d",time())." 00:00:00", date("Y-m-d",time())." 23:59:59");
	}
}

}


function loadChallengeParticipation($id) {
	$data = array('id'=>$id);
	return $this->db->get_where(Challenge_model::table_challenge_participant, $data)->row();
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
	return $this->getHouseChallenges($house_id, date("Y-m-d G:i:s",time()), date("Y-m-d G:i:s",time()));
}

function getHouseChallenges($house_id, $start_time, $end_time) {
	$sql = "SELECT * 
	FROM challenge 
	INNER JOIN challengeparticipant 
	ON challenge.id=challengeparticipant.challenge_id 
	AND challengeparticipant.user_id IN (SELECT id FROM user WHERE house_id = ?) 
	WHERE challengeparticipant.start_time < ? AND challengeparticipant.end_time > ? 
	";

	$query = $this->db->query($sql, array($house_id, $start_time, $end_time));
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
function getHouseTomorrowChallenges($house_id) {
	return $this->getHouseChallenges($house_id, date("Y-m-d G:i:s",time()+24*60*60),date("Y-m-d G:i:s",time()+24*60*60));
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

function getIndividualCurrentChallenges($user_id) {
	return $this->getIndividualChallenges($user_id,date("Y-m-d",time()));
}

function getIndividualChallenges($user_id, $date) {

	$sql = "SELECT *
	FROM challenge
	INNER JOIN challengeparticipant
	ON challenge.id=challengeparticipant.challenge_id
	AND challengeparticipant.user_id= ?
	WHERE Date(challengeparticipant.start_time) = ?
	GROUP BY challengeparticipant.challenge_id";

	$query = $this->db->query($sql, array($user_id, $date));
	return $query->result();
}

function logMessage($message) {
	$data = array(
		'message' => $message 
		);

	$this->db->insert('log', $data); 

}


function updateActivityProgress($user_id, $date=NULL) {
	$log = "updateActivityProgress-".$user_id."-".$date;
	$this->logMessage($log);
	$ci =& get_instance();
	$ci->load->model('Activity_model');

	if(is_null($date)) {
		$data = $this->getIndividualCurrentChallenges($user_id);
	} else {
		$data = $this->getIndividualChallenges($user_id, $date);
	}
	foreach($data as $c) {
		if($c->progress >=1 ) {
			continue;
		}
		$status = $this->Activity_model->getActivityStats($user_id, $c->start_time, $c->end_time);
		if($c->steps_value != 0 && $c->floor_value !=0) {
			$progress = 0.5 * ($status->steps/$c->steps_value) +
			0.5 * ($status->floors/$c->floor_value);

			$progress = number_format($progress,2);
			$this->updateProgress($c->id, $progress,$c->start_time, $c->end_time, $c->steps_value, "steps", $c->thread_id);

		} else if($c->steps_value != 0) {
			$progress = number_format($status->steps/$c->steps_value,2);
			$this->updateProgress($c->id, $progress, $c->start_time, $c->end_time, $c->steps_value, "steps", $c->thread_id);
		} else if($c->floor_value != 0) {
			$progress = number_format($status->floors/$c->floor_value,2);
			$this->updateProgress($c->id, $progress,$c->start_time, $c->end_time, $c->floor_value, "floors", $c->thread_id);

		} else if ($c->sleep_value != 0) {
			$value = $this->Activity_model->getSleepData($user_id, $date);

			$progress = number_format($value->total_time/$c->sleep_value, 2);
			$this->updateProgress($c->id, $progress, $date." 07:00:00", $date." 07:00:00", 0, "sleep", $c->thread_id);
		} else if ($c->sleep_time != 0) {
			$value = $this->Activity_model->getSleepStartTime($user_id, $c->sleep_time);
			if(empty($value)) {
				$progress = 0;
				$this->updateProgress($c->id, $progress, $date." 00:00:00",$date." 00:00:00", 0, "sleep", $c->thread_id);

			} else {
				$progress = 1;
				$this->updateProgress($c->id, $progress, $date." ".$value->start_time,$date." ".$value->start_time, 0, "sleep", $c->thread_id);

			}
		}
	}
}

function updateProgress($cp_id, $progress, $start_time, $end_time, $thresh_hold, $type, $thread_id) {
	echo "updateProgress ".$progress."-".$start_time."-".$end_time."-".$thresh_hold."-".$type."-".$thread_id;
	if($progress >= 1.0) {
		$cp = $this->loadChallengeParticipation($cp_id);
		$ci =& get_instance();
		$ci->load->model('User_model');
		$ci->load->model('Forum_model');
		$ci->load->model('Activity_model');
		$ci->load->model('Mail_model');
		if($type == "sleep") {
			$data = array('progress'=>1, 'complete_time'=>$start_time);
		}else {
			$time = $this->Activity_model->getChallengeCompletionTime($cp->user_id, $start_time, $end_time, $thresh_hold, $type);
			$data = array('progress'=>1, 'complete_time'=>$time);
		}
		if(empty($data)) {
			$data = array('progress'=>1, 'complete_time'=>date("Y-m-d H:i:s"));
		}
		//post to forum
		$challenge = $this->loadChallenge($cp->challenge_id);
		$user = $this->User_model->loadUser($cp->user_id);
		$message = $user->first_name." ".$user->last_name. " has completed this challenge at ". $data['complete_time'].".";
		$this->Forum_model->createPost($cp->user_id, $thread_id, $message);
		$this->Mail_model->sendChallengeCompletionMessage($user, $challenge->title, $data['complete_time']);

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
		AND staff = 0) AS temp ON cp.user_id = temp.id WHERE  cp.complete_time > cp.start_time";
$total = $this->db->query($sql2)->row()->total;

return $total/$count;		
}

function getAllChallenges($user_id) {
	$query = $this->db->get(Challenge_model::table_challenge);
	$count_sql = "SELECT challenge_id,
	Count(id) AS count
	FROM   challengeparticipant
	WHERE  user_id = ?
	GROUP  BY challenge_id";
	$counts = $this->db->query($count_sql, array($user_id))->result();
	$participations = array();
	foreach($counts as $c) {
		$participations[$c->challenge_id]=$c->count;
	}

	foreach($query->result() as $m) {
		
		if(empty($participations[$m->id]) || $participations[$m->id]<$m->quota) {
			$m->quota_exceeded = 0;
		} else {
			$m->quota_exceeded = 1;
		}

		
	}
	return $query->result();
}

function getParticipationCount($uid, $challenge_id) {
	$sql = "SELECT
	Count(id) AS count
	FROM   challengeparticipant
	WHERE  user_id = ?
	AND challenge_id = ?
	GROUP  BY challenge_id";
	$query = $this->db->query($sql, array($uid, $challenge_id));
	$res = $query->result();
	return empty($res)
	 ? 0 
	 : $res->count;
	
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
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 20";
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

	$this->db->query('SET GLOBAL group_concat_max_len=15000');
	$house_sql="SELECT
	u.house_id    AS house_id,
	h.name        AS house_name,
	h.picture     AS picture,
	Count(u.id) as user_num,
	GROUP_CONCAT(u.profile_pic) as avatars,
	GROUP_CONCAT(u.first_name) as names
	FROM   user AS u,
	house AS h
	WHERE  u.house_id = h.id
	AND u.phantom = 0
	AND u.staff = 0
	AND u.house_id > 0
	GROUP BY h.id
	";
	$houses = $this->db->query($house_sql)->result();

	$rank_sql = "SELECT
	u.house_id    AS house_id,
	sum(c.points)  AS score
	FROM   user AS u,
	challengeparticipant AS cp,
	challenge as c
	WHERE 
	c.id = cp.challenge_id
	AND cp.user_id = u.id
	AND cp.complete_time > cp.start_time
	AND u.phantom = 0
	AND u.staff = 0
	AND u.house_id > 0
	GROUP BY u.house_id
	ORDER BY sum(c.points) DESC, sum(cp.complete_time-cp.start_time) ASC";

	$ranks = $this->db->query($rank_sql)->result();
	$res = array();

	$res1 = array(); $res2 = array();
	foreach($ranks as $r) {
		$res1[$r->house_id] = $r->score;
	}
	foreach($houses as $h) {
		$h->score = empty($res1[$h->house_id]) ? 0 : $res1[$h->house_id];
		$h->average = number_format($h->score/$h->user_num,2);
		$res2[$h->house_id] = $h;
		$res1[$h->house_id] = $h;
	}

	//var_dump($res2);
	//var_dump($res1);
/*
	for($i=0; $i<=10; $i++) {
		$house_id = array_shift($res1);
		$res[$i] = $res2[] 
	}
*/
	return $res1;
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