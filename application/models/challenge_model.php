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
        $today = array(0 => 0, 1 => 0, 2 => 0);
        $tomorrow = array(0 => 0, 1 => 0, 2 => 0);

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

    function logMessage($message)
    {
        $data = array(
            'message' => $message
        );

        $this->db->insert('log', $data);

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
        if ($count) {
            return $total / $count;
        } else {
            return 0;
        }
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

    function getLearderboardBySteps()
    {
        $sql = "SELECT u.first_name  AS firstname,
    u.fitbit_id AS fitbit_id,
    u.username AS username,
    u.fb AS fb,
    u.last_name   AS lastname,
    u.profile_pic AS avatar,
    u.house_id    AS house_id,
    h.name        AS house,
    AVG(a.steps)  AS score,
    COUNT(a.steps) AS valid
    FROM   user AS u,
    activity AS a,
    house AS h
    WHERE a.user_id = u.id
    AND a.steps > 0
    AND u.house_id = h.id
    AND u.phantom = 0
    AND u.staff = 0
    AND a.date > '".VALID_STATS_BASELINE."'
    GROUP BY u.id
    HAVING COUNT(*) >= ".$this->minimumValidDays()."
    ORDER BY AVG(a.steps) DESC LIMIT 0, 10";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function getLearderboardBySleep()
    {
        $sql = "SELECT u.first_name  AS firstname,
    u.fitbit_id AS fitbit_id,
    u.username AS username,
    u.fb AS fb,
    u.last_name   AS lastname,
    u.profile_pic AS avatar,
    u.house_id    AS house_id,
    h.name        AS house,
    AVG(s.total_time)  AS score,
    COUNT(s.total_time) AS valid
    FROM   user AS u,
    sleep AS s,
    house AS h
    WHERE s.user_id = u.id
    AND s.total_time > 0
    AND u.house_id = h.id
    AND u.phantom = 0
    AND u.staff = 0
    AND s.date > '".VALID_STATS_BASELINE."'
    GROUP BY u.id
    HAVING COUNT(*) >= ".$this->minimumValidDays()."
    ORDER BY AVG(s.total_time) DESC LIMIT 0, 10";
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

    function getTutorLearderboardbySteps()
    {
        $sql = "SELECT u.first_name  AS firstname,
    u.last_name   AS lastname,
    u.fitbit_id AS fitbit_id,
    u.username AS username,
    u.fb AS fb,
    u.profile_pic AS avatar,
    u.house_id    AS house_id,
    AVG(a.steps)  AS score,
    COUNT(a.steps) AS valid
    FROM   user AS u,
    activity AS a
    WHERE a.steps > 0
    AND a.user_id = u.id
    AND u.phantom = 0
    AND u.hide_progress = 0
    AND (u.staff = 1)
    AND a.date > '".VALID_STATS_BASELINE."'
    GROUP BY u.id
    HAVING COUNT(*) >= ".$this->minimumValidDays()."
    ORDER BY AVG(a.steps) DESC LIMIT 0, 10";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function getTutorLearderboardbySleep()
    {
        $sql = "SELECT u.first_name  AS firstname,
    u.last_name   AS lastname,
    u.fitbit_id AS fitbit_id,
    u.username AS username,
    u.fb AS fb,
    u.profile_pic AS avatar,
    u.house_id    AS house_id,
    AVG(s.total_time)  AS score,
    COUNT(s.total_time) AS valid
    FROM   user AS u,
    sleep AS s
    WHERE s.total_time > 0
    AND s.user_id = u.id
    AND u.phantom = 0
    AND u.hide_progress = 0
    AND (u.staff = 1)
    AND s.date > '".VALID_STATS_BASELINE."'
    GROUP BY u.id
    HAVING COUNT(*) >= ".$this->minimumValidDays()."
    ORDER BY AVG(s.total_time) DESC LIMIT 0, 10";
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

    function getHouseLeaderboard()
    {

//	$this->db->query('SET GLOBAL group_concat_max_len=15000');
        $house_sql = "SELECT
	u.house_id    AS house_id,
    h.score       AS score,
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
    ORDER BY h.score DESC
	";
        $houses = $this->db->query($house_sql)->result();

        return $houses;
    }

    function getWeeklyLeaderboardbySteps() {
        $start_of_week = date('Y-m-d', strtotime('last monday', strtotime(parent::getDateTomorrow())));
        $today = date('Y-m-d', strtotime(parent::getDateToday()));
        $day_of_week = date('w', strtotime(parent::getDateToday()));
        if ($day_of_week == 0) {
            $day_of_week = 7;
        }

        $sql = "SELECT 
        AVG(a.steps) * %d AS steps,
        h.name AS house,
        h.id AS house_id
        FROM activity AS a
        JOIN user u ON u.id=a.user_id
        JOIN house h ON u.house_id = h.id
        WHERE date BETWEEN '%s' AND '%s'
        AND a.user_id NOT IN (
            SELECT i.user_id FROM invalidperiod AS i
            WHERE NOT (start_date > %s OR end_date < %s)
        )
        AND u.house_id > 0
        AND u.phantom = 0
        AND u.staff = 0
        GROUP BY u.house_id
        ORDER BY AVG(a.steps) DESC;
        ";
        $house_sql = sprintf($sql, $day_of_week, $start_of_week, $today, $today, $start_of_week);
        $houses = $this->db->query($house_sql)->result();
        return $houses;
    }

    function getWeeklyLeaderboardbySleep() {
        $start_of_week = date('Y-m-d', strtotime('last monday', strtotime(parent::getDateTomorrow())));
        $today = date('Y-m-d', strtotime(parent::getDateToday()));
        $day_of_week = date('w', strtotime(parent::getDateToday()));
        if ($day_of_week == 0) {
            $day_of_week = 7;
        }

        $sql = "SELECT 
        AVG(s.total_time) / 60 * %d AS sleep,
        h.name AS house,
        h.id AS house_id
        FROM sleep AS s
        JOIN user u ON u.id=s.user_id
        JOIN house h ON u.house_id = h.id
        WHERE date BETWEEN '%s' AND '%s'
        AND s.user_id NOT IN (
            SELECT i.user_id FROM invalidperiod AS i
            WHERE NOT (start_date > %s OR end_date < %s)
        )
        AND u.house_id > 0
        AND u.phantom = 0
        AND u.staff = 0
        GROUP BY u.house_id
        ORDER BY AVG(s.total_time) DESC;
        ";
        $house_sql = sprintf($sql, $day_of_week, $start_of_week, $today, $today, $start_of_week);
        $houses = $this->db->query($house_sql)->result();
        return $houses;
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

    function getHouseSleepStats($house_id) {
        $sql = "SELECT u.id as id,
            AVG(s.total_time)  AS score,
            COUNT(s.total_time) AS valid
            FROM   user AS u,
            sleep AS s
            WHERE s.user_id = u.id
            AND s.total_time > 0
            AND u.house_id = ".$house_id."
            AND u.phantom = 0
            AND s.date > '".VALID_STATS_BASELINE."'
            GROUP BY u.id";

        $data = $this->db->query($sql)->result();

        return $data;
    }

    function getHouseStepsStats($house_id) {
        $sql = "SELECT u.id as id,
            AVG(a.steps)  AS score,
            COUNT(a.steps) AS valid
            FROM   user AS u,
            activity AS a
            WHERE a.user_id = u.id
            AND a.steps > 0
            AND u.house_id = ".$house_id."
            AND u.phantom = 0
            AND a.date > '".VALID_STATS_BASELINE."'
            GROUP BY u.id";

        $data = $this->db->query($sql)->result();

        return $data;
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

    private function minimumValidDays() {
         $now = time(); // or your date as well
         $base = strtotime(VALID_STATS_BASELINE);
         $datediff = $now - $base;
         return floor(VALID_STATS_PERCENTAGE * $datediff/(60*60*24));
    }

}