<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Challenge_model extends My_Model
{

    const table_challenge = 'challenge';
    const table_challenge_participant = 'challengeparticipant';
    const col_cp_uid = 'user_id';
    const col_cp_cid = 'challenge_id';
    const col_cp_id = 'id';
    const col_c_id = 'id';

    function __construct()
    {
        parent::__construct();
    }

    function loadAvailableChallanges($user_id, $today, $tomorrow)
    {

        $challenges = $this->getAllChallenges($user_id);
        $joinedToday = $this->loadJoinedCategory($user_id, $today);
        $joinedTomorrow = $this->loadJoinedCategory($user_id, $tomorrow);
        $today = array(0 => 0, 1 => 0, 2 => 0, 3 => 0);
        $tomorrow = array(0 => 0, 1 => 0, 2 => 0, 3 => 0);

        $todayJoined = array();
        $cpIds = array();
        foreach ($joinedToday as $a) {
            $today[$a->category]++;
            $todayJoined[] = $a->challenge_id;
            $cpIds[$a->category] = $a->cp_id;
        }
        $tomorrowJoined = array();
        $cpIds2 = array();

        foreach ($joinedTomorrow as $b) {
            $tomorrow[$b->category]++;
            $tomorrowJoined[] = $b->challenge_id;
            $cpIds2[$b->category] = $b->cp_id;

        }

        foreach ($challenges as $c) {
            $c->user_id = $user_id;
            $c->disabled_today = ($today[$c->category] > 0);
            $c->disabled_tomorrow = ($tomorrow[$c->category] > 0);
            $c->joined_today = in_array($c->id, $todayJoined);
            $c->joined_tomorrow = in_array($c->id, $tomorrowJoined);
            $c->cp_id_today = empty($cpIds[$c->category]) ? -1 : $cpIds[$c->category];
            $c->cp_id_tomorrow = empty($cpIds2[$c->category]) ? -1 : $cpIds2[$c->category];
            $c->complete_count = $this->getCompleteCount($user_id, $c->id);
            $c->incomplete_count = $this->getIncompleteCount($user_id, $c->id);

        }


        return $challenges;

    }

    function getCompleteCount($id, $cid)
    {
        $sql = "select count(id) as count from challengeparticipant where user_id = ? and challenge_id= ? and progress >=1 and end_time<=NOW()";
        $query = $this->db->query($sql, array($id, $cid));
        return $query->row()->count;
    }

    function getIncompleteCount($id, $cid)
    {
        $sql = "select count(id) as count from challengeparticipant where user_id = ? and challenge_id= ? and progress <=1 and end_time<=NOW()";
        $query = $this->db->query($sql, array($id, $cid));
        return $query->row()->count;
    }

    function loadUserChallenge($user_id, $date)
    {
        $sql = "SELECT *
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id = ?
		WHERE DATE(challengeparticipant.start_time) = ?";
        $query = $this->db->query($sql, array($user_id, $date));
        return $query->result();
    }

    function loadJoinedCategory($user_id, $date)
    {
        $sql = "SELECT challenge.category, challenge.id as challenge_id, challengeparticipant.id as cp_id
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id = ?
		WHERE DATE(challengeparticipant.start_time) = ? ";
        $query = $this->db->query($sql, array($user_id, $date));
        return $query->result();
    }

    function loadChallenge($challenge_id)
    {
        $query = $this->db->get_where(Challenge_model::table_challenge, array('id' => $challenge_id));
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    function joinChallenge($user_id, $challenge_id, $category, $start_time, $end_time)
    {
        $data = array(
            'user_id' => $user_id,
            'challenge_id' => $challenge_id,
            'category' => $category,
            'start_time' => $start_time,
            'end_time' => $end_time
        );
        $this->db->insert(Challenge_model::table_challenge_participant, $data);

        return $this->db->insert_id();
    }

    function preAllocateChallenge($user_id, $day1, $day2)
    {
        $sql1 = 'select * from challengeparticipant where (DATE(start_time)=? or DATE(start_time)=?) and user_id=?';
        $exists = $this->db->query($sql1, array($day1, $day2, $user_id))->result();
        if (empty($exists)) {
            $this->joinChallenge($user_id, 1, 1, $day1 . " 00:00:00", $day1 . " 23:59:59");
            $this->joinChallenge($user_id, 7, 2, $day1 . " 00:00:00", $day1 . " 23:59:59");
            $this->joinChallenge($user_id, 13, 3, $day1 . " 00:00:00", $day1 . " 23:59:59");
            $this->joinChallenge($user_id, 22, 0, $day1 . " 00:00:00", $day1 . " 23:59:59");
            $this->joinChallenge($user_id, 1, 1, $day2 . " 00:00:00", $day2 . " 23:59:59");
            $this->joinChallenge($user_id, 7, 2, $day2 . " 00:00:00", $day2 . " 23:59:59");
            $this->joinChallenge($user_id, 13, 3, $day2 . " 00:00:00", $day2 . " 23:59:59");
            $this->joinChallenge($user_id, 22, 0, $day2 . " 00:00:00", $day2 . " 23:59:59");
            $sql = "INSERT IGNORE INTO `postsubscription` (`thread_id`, `user_id`) VALUES (?, ?)";
            $this->db->query($sql, array(1, $user_id));
            $this->db->query($sql, array(7, $user_id));
            $this->db->query($sql, array(13, $user_id));
            $this->db->query($sql, array(22, $user_id));

        }
    }


    public function carryOverToToday()
    {
        $now = date("Y-m-d", time() + 24 * 60 * 60);
        $ystd = date("Y-m-d", time());
        return $this->carryOverChallenges($ystd, $now);
    }

    public function carryOverToTomorrow()
    {
        $now = date("Y-m-d", time() + 24 * 60 * 60 * 2);
        $ystd = date("Y-m-d", time() + 24 * 60 * 60);
        return $this->carryOverChallenges($ystd, $now);
    }

    public function carryOverChallenges($ystd, $now)
    {

        $sql = "SELECT DISTINCT c1.user_id
		FROM   challengeparticipant AS c1
		WHERE  c1.user_id IN (SELECT DISTINCT user_id
			FROM   challengeparticipant
			WHERE  DATE(start_time) = ?)
			AND c1.user_id NOT 
			IN (SELECT DISTINCT user_id
			FROM   challengeparticipant
			WHERE  DATE(start_time) = ?)";
        $uids = $this->db->query($sql, array($ystd, $now))->result();
        $nextLevel = array(1 => 2, 2 => 3, 7 => 8, 8 => 9, 13 => 14, 14 => 15);
        $today = intval(date("N", strtotime($ystd)));
        foreach ($uids as $u) {
            $uid = $u->user_id;
            $sql2 = "SELECT distinct challenge_id, category
			FROM   challengeparticipant
			WHERE  DATE(start_time) = ?
			AND user_id=?";
            $cids = $this->db->query($sql2, array($ystd, $uid))->result();

            foreach ($cids as $c) {
                $cid = $c->challenge_id;
                $category = $c->category;
                $new = $this->loadChallenge($cid);
                $count = $this->Challenge_model->getParticipationCount($uid, $new->id);
                if ($count >= ($new->quota)) {
                    $cid = $nextLevel[$cid];
                    $new = $this->loadChallenge($cid);
                }
                if ($new->start_time != "00:00:00") {
                    $start = $now . " " . $new->start_time;
                    $end = $now . " " . $new->end_time;
                } else {
                    $start = $now . " 00:00:00";
                    $end = $now . " 23:59:59";
                }

                if ($category == 0 && ($today == 5 || $today == 6)) {
                    # code...
                } else {
                    $this->joinChallenge($uid, $new->id, $new->category, $start, $end);
                }

            }
        }

    }

    function carryOverLastWeekTimeBasedChallenges($now)
    {
        $today = intval(date("N", strtotime($now)));
        $nextLevel = array(1 => 2, 2 => 3, 7 => 8, 8 => 9, 13 => 14, 14 => 15);
        $sql = "SELECT DISTINCT c1.user_id
					FROM   challengeparticipant AS c1
					WHERE  c1.user_id IN (SELECT DISTINCT user_id
						FROM   challengeparticipant
						WHERE  DATE(start_time) = ?)
						AND c1.category = 0
						AND c1.user_id NOT 
						IN (SELECT DISTINCT user_id
						FROM   challengeparticipant
						WHERE  DATE(start_time) = ?
						AND category = 0)";
        $sql2 = "SELECT distinct challenge_id, category
						FROM   challengeparticipant
						WHERE  DATE(start_time) = ?
						AND category = 0
						AND user_id=?";
        //var_dump($today);
        if ($today == 1) {
            # code...
            $last_friday = date("Y-m-d", strtotime($now) - 24 * 60 * 60 * 3);
            $tmr = date("Y-m-d", strtotime($now) + 24 * 60 * 60);
            $uids = $this->db->query($sql, array($last_friday, $now))->result();
            foreach ($uids as $u) {
                $uid = $u->user_id;
                echo "current user: " . $uid;
                $cids = $this->db->query($sql2, array($last_friday, $uid))->result();
                foreach ($cids as $c) {
                    # code...
                    $cid = $c->challenge_id;
                    $category = $c->category;
                    $new = $this->loadChallenge($cid);
                    $count = $this->Challenge_model->getParticipationCount($uid, $new->id);
                    if ($count >= ($new->quota)) {
                        $cid = $nextLevel[$cid];
                        $new = $this->loadChallenge($cid);
                    }
                    if ($new->start_time != "00:00:00") {
                        $start = $now . " " . $new->start_time;
                        $end = $now . " " . $new->end_time;
                    } else {
                        $start = $now . " 00:00:00";
                        $end = $now . " 23:59:59";
                    }

                    $this->joinChallenge($uid, $new->id, $new->category, $start, $end);

                    if ($new->start_time != "00:00:00") {
                        $start = $tmr . " " . $new->start_time;
                        $end = $tmr . " " . $new->end_time;
                    } else {
                        $start = $tmr . " 00:00:00";
                        $end = $tmr . " 23:59:59";
                    }

                    $this->joinChallenge($uid, $new->id, $new->category, $start, $end);

                }
            }


            $uids = $this->db->query($sql, array($last_friday, $tmr))->result();
            foreach ($uids as $u) {
                $uid = $u->user_id;
                echo "current user: " . $uid;
                $cids = $this->db->query($sql2, array($last_friday, $uid))->result();
                foreach ($cids as $c) {
                    # code...
                    $cid = $c->challenge_id;
                    $category = $c->category;
                    $new = $this->loadChallenge($cid);
                    $count = $this->Challenge_model->getParticipationCount($uid, $new->id);
                    if ($count >= ($new->quota)) {
                        $cid = $nextLevel[$cid];
                        $new = $this->loadChallenge($cid);
                    }
                    if ($new->start_time != "00:00:00") {
                        $start = $tmr . " " . $new->start_time;
                        $end = $tmr . " " . $new->end_time;
                    } else {
                        $start = $tmr . " 00:00:00";
                        $end = $tmr . " 23:59:59";
                    }
                    //var_dump($tmr);
                    $this->joinChallenge($uid, $new->id, $new->category, $start, $end);
                    echo "updated tomorrow for user: " . $uid;
                }
            }

        } else if ($today == 7) {
            $last_friday = date("Y-m-d", strtotime($now) - 24 * 60 * 60 * 2);
            $tmr = date("Y-m-d", strtotime($now) + 24 * 60 * 60);
            $uids = $this->db->query($sql, array($last_friday, $tmr))->result();
            foreach ($uids as $u) {

                $uid = $u->user_id;
                echo "current user: " . $uid;
                $cids = $this->db->query($sql2, array($last_friday, $uid))->result();
                foreach ($cids as $c) {
                    # code...
                    $cid = $c->challenge_id;
                    $category = $c->category;
                    $new = $this->loadChallenge($cid);
                    $count = $this->Challenge_model->getParticipationCount($uid, $new->id);
                    if ($count >= ($new->quota)) {
                        $cid = $nextLevel[$cid];
                        $new = $this->loadChallenge($cid);
                    }
                    if ($new->start_time != "00:00:00") {
                        $start = $tmr . " " . $new->start_time;
                        $end = $tmr . " " . $new->end_time;
                    } else {
                        $start = $tmr . " 00:00:00";
                        $end = $tmr . " 23:59:59";
                    }

                    $this->joinChallenge($uid, $new->id, $new->category, $start, $end);
                }
            }
        }
    }


    function loadChallengeParticipation($id)
    {
        $data = array('id' => $id);
        return $this->db->get_where(Challenge_model::table_challenge_participant, $data)->row();
    }

    function loadChallengeHistory($id)
    {
        $sql = "SELECT *, DATE(cp.start_time) as date
				FROM   challengeparticipant AS cp,
       				  	challenge AS c
				WHERE  c.id = cp.challenge_id
   					AND cp.user_id = ?
   					AND Date(cp.start_time) <= Date(Now())
					ORDER  BY Date(cp.start_time) DESC";

        $query = $this->db->query($sql, array($id));
        $res = array();
        foreach ($query->result() as $row) {
            $res[$row->date][] = $row;
        }
        return $res;
    }

    function quitChallenge($id)
    {
        return $this->db->delete(Challenge_model::table_challenge_participant,
            array(Challenge_model::col_cp_id => $id));
    }

    function completeChallenge($id, $complete_time)
    {
        $data = array('complete_time', $complete_time);
        $this->db->where('id', $id);
        $this->db->update(Challenge_model::table_challenge_participant,
            $data);
    }


    function getHouseCurrentChallenges($house_id)
    {
        return $this->getHouseChallenges($house_id, date("Y-m-d", time()));
    }

    function getHouseChallenges($house_id, $date)
    {
        $sql = "SELECT *
		FROM challenge 
		INNER JOIN challengeparticipant 
		ON challenge.id=challengeparticipant.challenge_id 
		AND challengeparticipant.user_id IN (SELECT id FROM user WHERE house_id = ? AND phantom = 0) 
		WHERE DATE(challengeparticipant.start_time) = ?
		AND challengeparticipant.inactive=0";

        $query = $this->db->query($sql, array($house_id, $date));
        $challenges = $query->result();
        $res = array();
        foreach ($challenges as $c) {
            if (!isset($res[$c->user_id])) {
                $res[$c->user_id] = array();
            }
            $res[$c->user_id][] = $c;
        }

        return $res;
    }

    function getHouseTomorrowChallenges($house_id)
    {
        return $this->getHouseChallenges($house_id, date("Y-m-d", time() + 24 * 60 * 60));
    }

    function getHouseCompletedChallenges($house_id)
    {
        $sql = "SELECT challenge.* , count(challengeparticipant.id) as times
		FROM challenge
		INNER JOIN challengeparticipant
		ON challenge.id=challengeparticipant.challenge_id
		AND challengeparticipant.user_id IN (SELECT id FROM user WHERE house_id = ? AND phantom = 0) 
		WHERE challengeparticipant.progress >= 1
		AND challengeparticipant.inactive=0
		GROUP BY challengeparticipant.challenge_id";

        $query = $this->db->query($sql, array($house_id));
        return $query->result();
    }


    function getIndividualChallenges($user_id, $date)
    {

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

    function logMessage($message)
    {
        $data = array(
            'message' => $message
        );

        $this->db->insert('log', $data);

    }


    function updateActivityProgress($user_id, $date = NULL)
    {
        $log = "updateActivityProgress-" . $user_id . "-" . $date;
        $this->logMessage($log);
        $ci =& get_instance();
        $ci->load->model('Activity_model');

        if (is_null($date)) {
            $data = $this->getIndividualChallenges($user_id, date("Y-m-d ", time()));
        } else {
            $data = $this->getIndividualChallenges($user_id, $date);
        }

        foreach ($data as $c) {
            if ($c->progress >= 1) {
                continue;
            }
            $status = $this->Activity_model->getActivityStats($user_id, $c->start_time, $c->end_time);
            //var_dump($status);
            //var_dump($c);
            if ($c->steps_value != 0 && $c->floor_value != 0) {
                $progress = 0.5 * ($status->steps / $c->steps_value) +
                    0.5 * ($status->floors / $c->floor_value);

                $progress = number_format($progress, 2);
                $this->updateProgress($c->id, $progress, $c->start_time, $c->end_time, $c->steps_value, "steps", $c->thread_id);

            } else if ($c->steps_value != 0) {
                $progress = number_format($status->steps / $c->steps_value, 2);
                $this->updateProgress($c->id, $progress, $c->start_time, $c->end_time, $c->steps_value, "steps", $c->thread_id);
            } else if ($c->floor_value != 0) {
                $progress = number_format($status->floors / $c->floor_value, 2);

                $this->updateProgress($c->id, $progress, $c->start_time, $c->end_time, $c->floor_value, "floors", $c->thread_id);

            } else if ($c->sleep_value != 0) {
                $value = $this->Activity_model->getSleepData($user_id, $date);

                //time asleep * 110%
                $progress = number_format((($value->total_time / 60) / $c->sleep_value) * 1.1, 2);
                $this->updateProgress($c->id, $progress, $date . " 07:00:00", $date . " 07:00:00", 0, "sleep", $c->thread_id);

            } else if ($c->sleep_time != "00:00:00") {
                $value = $this->Activity_model->getSleepStartTime($user_id, $c->sleep_time, $date);
                if (empty($value)) {
                    $progress = 0;
                    $this->updateProgress($c->id, $progress, $date . " 00:00:00", $date . " 00:00:00", 0, "sleep", $c->thread_id);

                } else {
                    $progress = 1;
                    $this->updateProgress($c->id, $progress, $date . " " . $value->start_time, $date . " " . $value->start_time, 0, "sleep", $c->thread_id);

                }
            }
        }
    }

    function updateProgress($cp_id, $progress, $start_time, $end_time, $thresh_hold, $type, $thread_id)
    {

        if ($progress >= 1.0) {
            $cp = $this->loadChallengeParticipation($cp_id);
            $ci =& get_instance();
            $ci->load->model('User_model');
            $ci->load->model('Forum_model');
            $ci->load->model('Activity_model');
            $ci->load->model('Mail_model');
            if ($type == "sleep") {

                $data = array('progress' => 1, 'complete_time' => $start_time);
            } else {

                $time = $this->Activity_model->getChallengeCompletionTime($cp->user_id, $start_time, $end_time, $thresh_hold, $type);

                $data = array('progress' => 1, 'complete_time' => $time);
            }
            if (empty($data)) {
                $data = array('progress' => 1, 'complete_time' => date("Y-m-d H:i:s"));
            }

            // $challenge = $this->loadChallenge($cp->challenge_id);
            // $user = $this->User_model->loadUser($cp->user_id);
            // $message = $user->first_name." ".$user->last_name. " completed this challenge at ". substr($data['complete_time'],0,-3).".";
            // $this->Forum_model->createPost($cp->user_id, $thread_id, $message, false);
            // $this->Mail_model->sendChallengeCompletionMessage($user, $challenge->title, $data['complete_time']);

        } else {
            $data = array('progress' => $progress);

        }

        $this->db->where('id', $cp_id);
        $this->db->update(Challenge_model::table_challenge_participant, $data);
        // echo "updated" . $cp_id . "\n";
        // flush();
    }

    function getIndividualCompletedChallenges($user_id)
    {
        $sql = "SELECT challenge.* , count(challengeparticipant.challenge_id) as times
		FROM challenge
		INNER JOIN challengeparticipant
		ON challenge.id=challengeparticipant.challenge_id
		AND challengeparticipant.user_id= ?
		AND challengeparticipant.inactive=0
		WHERE challengeparticipant.progress >= 1

		GROUP BY challengeparticipant.challenge_id";

        $query = $this->db->query($sql, array($user_id));
        return $query->result();
    }

    function getIndividualChallengeCount($user_id)
    {
        $sql = "SELECT Count(cp.id) AS count
		FROM   challengeparticipant AS cp
		WHERE  cp.user_id = ?
		AND cp.inactive=0
		AND cp.progress>=1";
        return $this->db->query($sql, array($user_id))->row()->count;
    }

    function getAverageChallengeCount()
    {
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
			AND staff = 0) AS temp ON cp.user_id = temp.id 
		WHERE  cp.progress>=1
		AND cp.inactive=0";
        $total = $this->db->query($sql2)->row()->total;

        return $total / $count;
    }

    function getAllChallenges($user_id)
    {
        $query = $this->db->get(Challenge_model::table_challenge);
        $count_sql = "SELECT challenge_id,
		Count(id) AS count
		FROM   challengeparticipant
		WHERE  user_id = ?
		AND inactive = 0
		AND progress >= 1
		GROUP  BY challenge_id";
        $counts = $this->db->query($count_sql, array($user_id))->result();
        $participations = array();
        foreach ($counts as $c) {
            $participations[$c->challenge_id] = $c->count;
        }

        foreach ($query->result() as $m) {

            if (empty($participations[$m->id]) || $participations[$m->id] < $m->quota) {
                $m->quota_exceeded = 0;
            } else {
                $m->quota_exceeded = 1;
            }


        }
        return $query->result();
    }

    function getParticipationCount($uid, $challenge_id)
    {
        $sql = "SELECT
		Count(id) AS count
		FROM   challengeparticipant
		WHERE  user_id = ?
		AND challenge_id = ?
		AND inactive = 0
		AND progress >= 1
		GROUP BY challenge_id";
        $query = $this->db->query($sql, array($uid, $challenge_id));

        if ($query->num_rows() > 0) {
            return $query->row()->count;
        } else {
            return 0;
        }

    }

    function getLearderboard()
    {
        $sql = "SELECT u.first_name  AS firstname,
	u.fitbit_id AS fitbit_id,
	u.username AS username,
	u.fb AS fb,
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
	AND cp.progress >= 1
	AND cp.inactive = 0
	AND u.phantom = 0
	AND u.staff = 0
	GROUP BY u.id
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 20";
        $query = $this->db->query($sql);
        return $query->result();
    }


    function getLearderboardByGender($gender)
    {
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
	AND cp.progress >= 1
	AND cp.inactive = 0
	AND u.gender = ?
	AND u.phantom = 0
	AND u.staff = 0
	GROUP BY u.id
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 10";
        $query = $this->db->query($sql, array($gender));
        return $query->result();
    }

    function getTutorLearderboard()
    {
        $sql = "SELECT u.first_name  AS firstname,
	u.last_name   AS lastname,
	u.fitbit_id AS fitbit_id,
	u.username AS username,
	u.fb AS fb,
	u.profile_pic AS avatar,
	u.house_id    AS house_id,
	h.name        AS house,
	Count(cp.id)  AS score
	FROM   user AS u,
	house AS h,
	challengeparticipant AS cp
	WHERE  u.house_id = h.id
	AND cp.user_id = u.id
	AND cp.progress >= 1
 	AND cp.inactive = 0
	AND u.phantom = 0
	AND u.hide_progress = 0
	AND (u.staff = 1)
	GROUP BY u.id
	ORDER BY count(cp.id) DESC, sum(cp.complete_time-cp.start_time) ASC LIMIT 0, 10";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function getPersonalAverage($user_id)
    {
        $point_sql = "select sum(c.points) as total_points from challengeparticipant as cp, challenge as c where cp.progress>=1 and cp.inactive=0 and cp.challenge_id = c.id and cp.user_id=?";
        $point_query = $this->db->query($point_sql, array($user_id));

        $date_sql = "select count(distinct DATE(cp.start_time)) as active_date from challengeparticipant as cp where cp.inactive=0 and cp.user_id=? and cp.start_time<?";
        $date_query = $this->db->query($date_sql, array($user_id, date("Y-m-d H:i:s")));

        $point = $point_query->row()->total_points;
        $dates = $date_query->row()->active_date;
        if ($dates == 0) {
            return 0;
        } else {
            return $point / $dates;
        }
    }

    function getHouseScore($house_id)
    {
        $sql = "select distinct id from user where house_id=?";
        $query = $this->db->query($sql, array($house_id));

        $total = 0;
        foreach ($query->result() as $row) {
            $user_id = $row->id;
            $total += $this->getPersonalAverage($user_id);
        }
        return $total;
    }

    function getHouseLeaderboard()
    {

//	$this->db->query('SET GLOBAL group_concat_max_len=15000');
        $house_sql = "SELECT
	u.house_id    AS house_id,
	h.name        AS house_name,
	h.picture     AS picture,
	Count(u.id) as user_num,
	GROUP_CONCAT(u.profile_pic) as avatars,
	GROUP_CONCAT(u.first_name) as names,
	GROUP_CONCAT(u.fitbit_id) AS fitbit_ids,
	GROUP_CONCAT(u.username) AS usernames,
	GROUP_CONCAT(u.fb) AS fbs
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
	AND cp.progress >= 1 
	AND cp.inactive = 0
	AND u.phantom = 0
	AND u.staff = 0
	AND u.house_id > 0
	GROUP BY u.house_id
	ORDER BY sum(c.points) DESC, sum(cp.complete_time-cp.start_time) ASC";


        $ranks = $this->db->query($rank_sql)->result();

        $res = array();

        $res1 = array();
        $res2 = array();

        foreach ($ranks as $r) {
            $house_id = $r->house_id;

            $res1[$r->house_id] = $r->score;
        }
        foreach ($houses as $h) {
            if (!empty($res1[$h->house_id])) {
                $h->score = $res1[$h->house_id];
                $h->sum_person_days = $this->getPersonDays($house_id);
                $h->average = number_format((float)$h->score / $h->sum_person_days, 2);
                $h->score = number_format($h->score, 0);

                //echo $h->score." ".$h->user_num." ".$h->average." ".$h->score/$h->user_num.'<br>';
                $res[$h->average] = $h;
                //$res1[$h->house_id] = $h;
            }

        }
        krsort($res);
        return $res;
    }

    function getPersonDays($house_id)
    {
        $sql = "select sum(dates) as total_dates from (select count(distinct DATE(start_time)) as dates, user_id from challengeparticipant where user_id IN (select id from user where house_id = ? and phantom=0) and inactive=0 and start_time < ? group by user_id) as temp";
        $query = $this->db->query($sql, array($house_id, date("Y-m-d H:i:s")));
        return $query->row()->total_dates;

    }

    function getHouseRankAndPoints($house_id)
    {
        $leaderboard = $this->getHouseLeaderboard();
        $rank = 0;
        foreach ($leaderboard as $house) {
            $rank++;
            if ($house->house_id == $house_id) {

                return array('rank' => $rank,
                    'points' => $house->score,
                    'house_id' => $house->house_id,
                    'house_name' => $house->house_name,
                    'picture' => $house->picture);
            }
        }
    }

    function getMyHouseStats($house_id)
    {
        $sql = "SELECT
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
	GROUP BY u.id
	ORDER BY count(cp.id)";
        $query = $this->db->query($sql, array($house_id));
        $stats = $query->result();
        $res = array();
        foreach ($stats as $s) {
            $res[$s->user_id] = $s;
            $res[$s->user_id]->total_points = $this->getTotalPoints($s->user_id);
        }
        return $res;
    }

    function getTotalPoints($user_id)
    {
        $sql = "SELECT Sum(points) AS total
			FROM   challengeparticipant AS cp
       LEFT JOIN challenge AS c
              ON cp.challenge_id = c.id
		WHERE  cp.progress>=1
		AND cp.inactive = 0
   		AND cp.user_id = ?";
        $query = $this->db->query($sql, array($user_id));
        if ($query->num_rows() > 0) {
            $total = $query->row()->total;
        }
        return empty($total) ? 0 : $total;
    }

    function getAveragePoints()
    {
        $sql = "SELECT Avg(temp.total) as points
		FROM   (SELECT Sum(points) AS total,
               cp.user_id
        FROM   challengeparticipant AS cp
               LEFT JOIN challenge AS c
                      ON cp.challenge_id = c.id
        WHERE  cp.progress>=1
           AND cp.inactive = 0
           AND cp.user_id IN (SELECT id
                              FROM   user
                              WHERE  phantom = 0)
        GROUP  BY cp.user_id) AS temp";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row()->points;
        } else {
            return 0;
        }
    }

}