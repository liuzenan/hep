<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sendmail extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
			$this->load->model("Mail_model");
			$this->filePath = "./announcements";
			$this->load->helper('file');
		}
	}

	public function index() {

		if ( !file_exists($this->filePath) ) {
  			mkdir($this->filePath, 0777);
 		}

		if($this->session->userdata('isadmin')){
			$fileNames = get_filenames($this->filePath.'/');
			$data["emailmsg"]=array();
			$data["filenames"]=array();
			$todayDate = date("Y-m-d");
			$data["today"]=read_file($this->filePath . "/" . $todayDate);
			foreach ($fileNames as $fileName) {
				array_push($data["emailmsg"], read_file($this->filePath . "/" . $fileName));
				array_push($data["filenames"], $fileName);
			}
			
			$this->loadPage($data);
		} else {
			redirect(base_url() . "home");
		}
	}

	public function textWriteFile() {
		if (!write_file("./test.txt", "test")) {
			# code...
			echo "error";
		} else {
			echo "success";
		}
	}

	public function sendMailMessage(){
		$title = $this->input->post("title");
		$emailMsg = $this->input->post("msg");
		$emailDate = date("Y-m-d");
		$emailMsg = htmlspecialchars($emailMsg);
		$this->load->helper('file');
		if (!write_file($this->filePath . '/' . $title . '-' . $emailDate, $emailMsg)) {
			# code...
			$msg['success'] = false;
			
		} else {
			$msg['success'] = true;
			
		}

		$uids = $this->User_model->loadAllStudents();

		foreach($uids as $uid) {
			$this->Mail_model->sendAnnouncement($uid->id, $title, $emailMsg);
		}

		echo json_encode($msg);
	}

	public function sendReminder() {
		$title = $this->input->post("title");
		$emailMsg = $this->input->post("msg");
		$emailDate = date("Y-m-d");
		$emailMsg = htmlspecialchars($emailMsg);
		$this->load->helper('file');

		if (!write_file($this->filePath . '/' . $title . '-' . $emailDate, $emailMsg)) {
			# code...
			$msg['success'] = false;
			
		} else {
			$msg['success'] = true;
			
		}

		$uids = $this->User_model->loadStudentDidntCompleteSurvey();
		foreach($uids as $uid) {
			$this->Mail_model->sendAnnouncement($uid->id, $title, $emailMsg);
		}
		echo json_encode($msg);
	}

	public function deleteEmailMessage(){
		$emailDate = $this->input->post("date");
		$this->load->helper('file');
		if (!unlink($this->filePath . '/' . $emailDate)) {
			# code...
			$msg['success'] = false;
			
		} else {
			$msg['success'] = true;
			
		}
		echo json_encode($msg);
	}

	private function loadPage($data){
		$data['active'] = 'sendmail';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('sendmail', $data);
		$this->load->view('templates/footer');
	}

}