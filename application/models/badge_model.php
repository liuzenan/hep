<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Badge_model extends My_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getBadge($badge_id)
    {
        $query = $this->db->get_where('badge', array('id' => $badge_id));
        return $query->row();
    }

    function getAllBadges()
    {
        # code...
        $query = $this->db->query("SELECT * FROM badge");
        return $query->result();
    }

    function getBadges($user_id)
    {
        $sql = "SELECT b.*, ub.*, COUNT(*) as count
		FROM badge AS b
		INNER JOIN userbadge AS ub
		ON b.id = ub.badge_id
		WHERE ub.user_id = ?
    GROUP BY ub.badge_id, ub.user_id";


        $query = $this->db->query($sql, array($user_id));
        return $query->result();
    }

    function getBadgesByDate($user_id, $date)
    {
        $sql = "SELECT *
		FROM   badge b
       	INNER JOIN userbadge ub
               ON b.id = ub.badge_id
		WHERE  ub.user_id = ?
   		AND ub.date = ?";
        $query = $this->db->query($sql, array($user_id, $date));
        return $query->result();
    }

    function getHouseBadges($house_id)
    {
        $sql = "SELECT b.*, ub.*, COUNT(*) as count
		FROM badge AS b
		INNER JOIN userbadge AS ub
		ON b.id = ub.badge_id
		WHERE ub.user_id IN (SELECT id FROM user WHERE house_id = ? AND phantom = 0)
    GROUP BY ub.badge_id, ub.user_id";


        $query = $this->db->query($sql, array($house_id));
        $data = $query->result();
        $res = array();
        foreach ($data as $d) {
            if (!isset($res[$d->user_id])) {
                $res[$d->user_id] = array();
            }
            $res[$d->user_id][] = $d;
        }
        return $res;
    }

    function addBadge($user_id, $badge_id)
    {
        $data = array(
            'user_id' => $user_id,
            'badge_id' => $badge_id
        );
        $query = $this->db->insert('userbadge', $data);
        return $this->db->insert_id();
    }

    //1 beat personal best
    //2 above personal average
    function scanBadge()
    {
        $yest = date("Y-m-d", time() - 60 * 60 * 24);

        $sql = "INSERT IGNORE INTO userbadge
            (badge_id,
             user_id,
             date)
				SELECT a1.category,
       					a1.user_id,
       				DATE(a1.start_time)
					FROM   challengeparticipant AS a1
					WHERE  a1.challenge_id > (SELECT Max(a2.challenge_id)
                   FROM   challengeparticipant AS a2
                   WHERE  a1.user_id = a2.user_id
                   	   AND a1.category = a2.category
                   	   AND a2.progress >= 1
                      AND DATE(a2.start_time) < DATE(a1.start_time))
   						AND a1.progress >= 1
   						AND (a1.category = 1 OR a1.category = 2)
  						 AND DATE(a1.start_time) = ?";
        $query = $this->db->query($sql, array($yest));


        // challenge count
        $challenge_count_badge = array(3 => 10, 4 => 20, 5 => 50, 6 => 100, 7 => 200);
        foreach ($challenge_count_badge as $badge_id => $challenge_count) {
            $sql = sprintf("INSERT IGNORE INTO userbadge (badge_id,
   				user_id,
   				date)
   			SELECT %d,
   			cp.user_id,
   			DATE(now())
   			from challengeparticipant as cp where cp.progress>=1 
   			and cp.user_id NOT IN (select user_id from userbadge where badge_id=%d) 
   			group by cp.user_id having count(cp.id)>%d", $badge_id, $badge_id, $challenge_count);
            //echo $sql;
            $this->db->query($sql);
        }

    }


}