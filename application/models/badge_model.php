<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badge_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getBadge($badge_id) {
		$query = $this->db->get_where('badge', array('id' => $badge_id));
		return $query->row();
	}

	function getBadges($user_id) {
		$sql = "SELECT b.*, ub.*
		FROM badge AS b
		INNER JOIN userbadge AS ub
		ON b.id = ub.badge_id
		WHERE ub.user_id = ?" ;


		$query = $this->db->query($sql, array($user_id));
		return $query->result();
	}

	function getHouseBadges($house_id) {
		$sql = "SELECT b.*, ub.*
		FROM badge AS b
		INNER JOIN userbadge AS ub
		ON b.id = ub.badge_id
		WHERE ub.user_id IN (SELECT id FROM user WHERE house_id = ? AND phantom = 0)" ;


		$query = $this->db->query($sql, array($house_id));
		$data = $query->result();
		$res = array();
		foreach($data as $d) {
			if(!isset($res[$d->user_id])) {
				$res[$d->user_id] = array();
			}
			$res[$d->user_id][]=$d;
		}
		return $res;
	}

	function addBadge($user_id, $badge_id) {
		$data = array(
			'user_id'=>$user_id,
			'badge_id'=>$badge_id
			);
		$query = $this->db->insert('userbadge', $data);
		return $this->db->insert_id();
	}

}