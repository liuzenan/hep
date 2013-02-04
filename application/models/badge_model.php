<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badge_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getBadges($user_id) {
		$sql = "SELECT 	name, description, badge_pic
				FROM badge
				INNER JOIN userbadge
				ON badge.id = userbadge.badge_id
				WHERE userbadge.user_id = " . $user_id;


		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result();
		}
	}

	
}