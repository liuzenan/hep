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

    function getAllBadges($user_id)
    {
        # code...
        $badges = $this->db->query("SELECT * FROM badge");
        $result = array();
        foreach ($badges->result() as $badge) {
            $result[$badge->id] = array(
                'name' => $badge->name,
                'badge_pic' => $badge->badge_pic,
                'description' => $badge->description,
                'count' => 0);
        }
        $myBadge = $this->getBadges($user_id);
        foreach ($myBadge as $badge) {
            if (isset($result[$badge->id])) {
                $result[$badge->id]['count'] = $badge->count;
            }
        }
        return $result;
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

    function addBadge($user_id, $badge_id, $date)
    {
        $data = array(
            'user_id' => $user_id,
            'badge_id' => $badge_id,
            'date'  => $date
        );
        $query = $this->db->insert('userbadge', $data);
        return $this->db->insert_id();
    }

    // types include contributor, cohort, duel
    function scanBadge()
    {
        $data = array('message' => 'scan-badge');
        $this->db->insert('log', $data);

        $target = date("Y-m-d", time() - 60 * 60 * 24 * BADGE_DELAY_DAYS);
        $day = date( "w", time());
        $houses = $this->db->get('house')->result();
        // process contributor 
        if ($day == WEEKLY_BADGE_PROCESS_DAY) {
            $this_monday = date('Y-m-d', strtotime('last monday', strtotime('tomorrow')));
            $last_monday = date('Y-m-d', strtotime('- 7 days', strtotime($this_monday)));
            $last_sunday = date('Y-m-d', strtotime('- 1 day', strtotime($this_monday)));
            $badgeQuery = $this->db->get_where('badge', array('type' => 'contributor'));
            foreach($badgeQuery->result() as $badge) {
                $data = json_decode($badge->data);
                $table = $data->table;
                $column = $data->column;
                foreach($houses as $house) {
                    $house_id = $house->id;

                    $sql = "SELECT u.id as uid
                    FROM $table a
                    JOIN user u on a.user_id=u.id
                    WHERE u.phantom=0
                    AND u.house_id = $house_id
                    AND a.date BETWEEN '$last_monday' AND '$last_sunday'
                    GROUP BY a.user_id
                    ORDER BY SUM(a.$column) DESC
                    LIMIT 0, 1";

                    $query = $this->db->query($sql);
                    foreach ($query->result() as $row) {
                        $this->addBadge($row->uid, $badge->id, $target);
                    }
                }
            }
        }

        // process cohort
        $badgeQuery = $this->db->get_where('badge', array('type' => 'cohort'));
        foreach($badgeQuery->result() as $badge) {
            $data = json_decode($badge->data);
            $table = $data->table;
            $column = $data->column;
            $percent = $data->percent;

            $sql = "SELECT u.id as uid
            FROM $table a
            JOIN user u on a.user_id=u.id
            WHERE u.phantom = 0
            AND a.date = '$target'
            ORDER BY a.$column DESC";

            $query = $this->db->query($sql);
            $limit = round($query->num_rows() * $percent);
            foreach ($query->result() as $row) {
                $this->addBadge($row->uid, $badge->id, $target);
                $limit --;
                if ($limit <= 0) {
                    break;
                }
            }
            
        }

        // process duel 
        // TODO
        $data = array('message' => 'scan-badge');
        $this->db->insert('log', $data);
    }


}