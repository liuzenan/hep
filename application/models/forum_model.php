<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	function getChallengeForum() {

		$sql = "SELECT f.title AS title,f.id AS thread_id, f.creator_id AS creator_id, 
				t.comment AS comment, t.time_created AS comment_time, 
				t.user_id AS commenter_id, t.id AS comment_id
					FROM forumthread AS f
					LEFT JOIN threadpost AS t
					ON t.thread_id = f.id
					WHERE f.topic_id = 3";
		$query = $this->db->query($sql);
		if ($query->num_rows()>0) {
			$res = array();
			//print_r($query->result());
			foreach($query->result() as $row) {
				if(empty($res[$row->thread_id])) {
					$thread = array();
					$thread["title"]= $row->title;
					$thread["thread_id"] = $row->thread_id;
					$thread["creator_id"] = $row->creator_id;
					if(empty($thread["comments"])) {
						$thread["comments"] = array();
					}
					$res[$row->thread_id] = $thread;
				}
				if(!empty($row->commenter_id)) {
					$thread = $res[$row->thread_id];
					$comment = array();
					$comment["commenter_id"] = $row->commenter_id;
					$comment["comment"] = $row->comment;
					$comment["comment_time"] = $row->comment_time;
					$comment["comment_id"] = $row->comment_id;
					$thread["comments"][$row->comment_id] = $comment;
					$res[$row->thread_id]["comments"] = $thread["comments"];
					//print_r($comment);
				}
			}
			return $res;
		} else {
			return FALSE;
		}

	}
	
}