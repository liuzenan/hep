<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function index(){

	}

	public function redirect($notification_id){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
		$query = $this->db->query("SELECT * FROM notification WHERE id = " . $this->db->escape($notification_id));
			if ($query->num_rows()>0) {
				$notification = $query->row();
				$this->load->model('User_model','userModel');
				$removed = $this->userModel->removeNotification($notification_id);
				if (!empty($removed)) {
					redirect($notification->url);
				}
			}

		}
		redirect(base_url());
	}
}