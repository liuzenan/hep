<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Editmail extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		} else {
			$this->uid = $this->session->userdata('user_id');
			$this->filePath = "./messages";
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

	public function updateEmailMessage(){
		$emailMsg = $this->input->post("msg");
		$emailDate = $this->input->post("date");
		$emailMsg = htmlspecialchars($emailMsg);
		$this->load->helper('file');
		if (!write_file($this->filePath . '/' . $emailDate, $emailMsg)) {
			# code...
			$msg['success'] = false;
			
		} else {
			$msg['success'] = true;
			
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
		$data['active'] = 'mail';
		$data['displayName'] = $this->session->userdata('name');
		$data['avatar'] = $this->session->userdata('avatar');
		$data['isAdmin'] = $this->session->userdata('isadmin');
		$data['isLeader'] = $this->session->userdata('isleader');
		$data['isTutor'] = $this->session->userdata('isTutor');
		
		$data['notifications'] = $this->User_model->getNotifications($this->uid);
		$this->load->view('templates/header', $data);
		$this->load->view('mail', $data);
		$this->load->view('templates/footer');
	}

}