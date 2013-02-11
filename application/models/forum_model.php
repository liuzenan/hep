<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forum_model extends CI_Model{

	const step = 1;
	const floor = 2;
	const sleep = 3;
	const misc = 0;
	const table_thread = 'forumthread';
	const table_post = 'threadpost';
	const table_subscribe = 'postsubscription';

	function __construct(){
		parent::__construct();
	}


	function getChallengeForum($user_id) {
		$subs = $this->getSubscribedThread($user_id);


		$sql = "SELECT t.*, p.*
		FROM   forumthread AS t
		LEFT JOIN threadpost AS p
		ON t.id = p.thread_id
		WHERE  t.challenge_id > 0
		AND t.id IN (".implode(",", $subs).")
		AND t.tutor_only = 0
		AND t.archived = 0 ORDER BY p.comment_time ASC";
		$query = $this->db->query($sql);
		$uids = array();
		if ($query->num_rows()>0) {
			$res = array();
			foreach($query->result() as $row) {
				if(empty($res[$row->thread_id])) {
					$thread = array();
					$thread["challenge_id"] = $row->challenge_id;
					$thread["title"]= $row->message;
					$thread["thread_id"] = $row->id;
					$thread["subscribe"] = 1;
					$thread["tutor_only"] = 0;
					if(empty($thread["comments"])) {
						$thread["comments"] = array();
					}
					$res[$row->id] = $thread;
				}
				if(!empty($row->commenter_id) && ($row->deleted == 0)) {
					$thread = $res[$row->id];
					$comment = array();
					$comment["commenter_id"] = $row->commenter_id;
					$comment["comment"] = $row->comment;
					$comment["comment_time"] = $row->comment_time;
					$comment["comment_id"] = $row->cid;
					$thread["comments"][$row->cid] = $comment;
					$res[$row->id]["comments"] = $thread["comments"];
					$uids[] = $row->commenter_id;
				}
			}
			$res = array_reverse($res, true);
			$res['uids'] = $uids;
			return $res;
		} else {
			return FALSE;
		}

	}

	function getSubscribedThread($user_id) {
		$sql2 = "SELECT DISTINCT thread_id FROM postsubscription WHERE user_id = ?";
		$subscriptions = $this->db->query($sql2, array($user_id))->result();
		$subs = array();
		foreach($subscriptions as $s) {
			$subs[] = $s->thread_id;
		}
		return $subs;
	}

	function getGeneralForum($user_id) {
		$sql = "SELECT t.*, p.*
		FROM   forumthread AS t
		LEFT JOIN threadpost AS p
		ON t.id = p.thread_id
		WHERE  t.challenge_id = -1
		AND t.tutor_only = 0
		AND t.archived = 0 ORDER BY p.comment_time ASC";
		$query = $this->db->query($sql);


		$subs = $this->getSubscribedThread($user_id);

		$uids = array();
		if ($query->num_rows()>0) {
			$res = array();
			foreach($query->result() as $row) {
				if(empty($res[$row->thread_id])) {
					$thread = array();
					$thread["challenge_id"] = $row->challenge_id;
					$thread["title"]= $row->message;
					$thread["thread_id"] = $row->id;
					$thread["subscribe"] = in_array($row->thread_id, $subs);
					$thread["tutor_only"] = 0;
					if(empty($thread["comments"])) {
						$thread["comments"] = array();
					}
					$res[$row->id] = $thread;
				}
				if(!empty($row->commenter_id) && ($row->deleted == 0)) {
					$thread = $res[$row->id];
					$comment = array();
					$comment["commenter_id"] = $row->commenter_id;
					$comment["comment"] = $row->comment;
					$comment["comment_time"] = $row->comment_time;
					$comment["comment_id"] = $row->cid;
					$thread["comments"][$row->cid] = $comment;
					$res[$row->id]["comments"] = $thread["comments"];
					$uids[] = $row->commenter_id;
				}
			}
			$res = array_reverse($res, true);
			$res['uids'] = $uids;
			return $res;
		} else {
			return FALSE;
		}
	}

	function getTutorForum() {
		$sql = "SELECT t.*, p.*
		FROM   forumthread AS t
		LEFT JOIN threadpost AS p
		ON t.id = p.thread_id
		WHERE  t.challenge_id = 0
		AND t.tutor_only = 1
		AND t.archived = 0 ORDER BY p.comment_time ASC";
		$query = $this->db->query($sql);
		$uids = array();
		if ($query->num_rows()>0) {
			$res = array();
			foreach($query->result() as $row) {
				if(empty($res[$row->thread_id])) {
					$thread = array();
					$thread["challenge_id"] = $row->challenge_id;
					$thread["title"]= $row->message;
					$thread["thread_id"] = $row->id;
					if(empty($thread["comments"])) {
						$thread["comments"] = array();
					}

					$res[$row->id] = $thread;
				}
				if(!empty($row->commenter_id) && ($row->deleted == 0)) {
					$thread = $res[$row->id];
					$comment = array();
					$comment["commenter_id"] = $row->commenter_id;
					$comment["comment"] = $row->comment;
					$comment["comment_time"] = $row->comment_time;
					$comment["comment_id"] = $row->cid;
					$thread["comments"][$row->cid] = $comment;
					$res[$row->id]["comments"] = $thread["comments"];
					$uids[] = $row->commenter_id;
				}
			}
			$res = array_reverse($res, true);

			$res['uids'] = $uids;
			return $res;
		} else {
			return FALSE;
		}
	}


	function loadThread($thread_id) {
		$query = $this->db->get_where(Forum_model::table_thread, array('id' => $thread_id));
		return $query->row();
	}

	function createThread($creator_id, $message) {
		$data = array(
			'creator_id'=>$creator_id,
			'message'=>$message,
			);
		$this->db->insert(Forum_model::table_thread,$data);
		return $this->db->insert_id();

	}

	function createTutorThread($creator_id, $message) {
		$data = array(
			'creator_id'=>$creator_id,
			'message'=>$message,
			'tutor_only'=>1
			);
		$this->db->insert(Forum_model::table_thread,$data);
		return $this->db->insert_id();

	}

	function loadThreadSubscribers($thread_id) {
		$query = $this->db->get_where(Forum_model::table_subscribe , array('thread_id' => $thread_id));
		return $query->result();
	}

	function subscribe($user_id, $thread_id) {
		$data = array(
			'thread_id'=>$thread_id,
			'user_id'=>$user_id);
		$this->db->insert("postsubscription", $data);
	}

	function unsubscribe($user_id, $thread_id) {
		$data = array(
			'thread_id'=>$thread_id,
			'user_id'=>$user_id);
		$this->db->delete("postsubscription", $data);
	}

	function createThreadNotification($thread_id) {

		$query = $this->db->query("SELECT * FROM postsubscription WHERE thread_id = " . $thread_id);

		if ($query->num_rows()>0) {
						# code...
			$subscriptions = $query->result();
			foreach ($subscriptions as $value) {
				if ($value->user_id != $user_id) {
					
					$notification_id = $this->User_model->addNotification($value->user_id, $description, $url);
				}

			}
		}
	}

	function createPost($commenter_id, $thread_id, $message) {
		$data = array(
			'commenter_id'=>$commenter_id,
			'comment'=>$message,
			'thread_id'=>$thread_id
			);
		$this->db->insert(Forum_model::table_post,$data);
		$this->addNotification($thread_id, $commenter_id);
		return $this->db->insert_id();
	}

	function addNotification($thread_id, $user_id) {
		
					# code...
		$ci =& get_instance();
		$ci->load->model('User_model');

		$user = $this->User_model->loadUser($user_id);
		$thread = $this->loadThread($thread_id);
		
		if (isset($user) && isset($thread)) {
					# code...
			$username = $user->first_name . " " . $user->last_name;
			$title = $thread->message;
			$description = $username . " post a new message at the thread: " . $title;

			if($thread->tutor_only>0) {
				$url = base_url() . "forum/tutor";

			}else if($thread->challenge_id>0) {
				$url = base_url() . "forum/challenge";
			}else {
				$url = base_url() . "forum/general";
			}

			$subscribers = $this->loadThreadSubscribers($thread_id);
			if (count($subscribers)>0) {
				foreach ($subscribers as $value) {
					if ($value->user_id != $user_id) {	
						$notification_id = $this->User_model->addNotification($value->user_id, $description, $url);
					}
				}
			}
		}
		
	}

	function addSubscriptoin($thread_id, $user_id) {
		$data = array(
			'thread_id'=>$thread_id,
			'user_id'=>$user_id
			);
		$this->db->insert(Forum_model::postsubscription, $data);
		return $this->db->insert_id();
	}

	function archiveThread($thread_id) {
		$data = array('archived', 1);
		$this->db->where('id',$thread_id);
		$this->db->upate(Forum_model::table_thread,
			$data);
	}

	function deletePost($post_id) {
		$data = array('deleted', 1);
		$this->db->where('cid',$post_id);
		$this->db->upate(Forum_model::table_post,
			$data);
	}
	
}