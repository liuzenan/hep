<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber extends CI_Controller {


	public function index(){
		$this->load->database();

		if(!isset($_FILES['updates'])){
			$updates=false;
		}else{
			$updates=$_FILES['updates'];
		}

		$signature = $_SERVER['HTTP_X_FITBIT_SIGNATURE'];
		$expected = hash_hmac('sha1', $updates, '45e414dd49784ec3872a8ebbb74dcbb9&');
		if(strcmp($signature, $expected)==0){

		}else{

		}

		$xml = simplexml_load_file($_FILES['updates']['tmp_name']);
		$data= array();
		foreach ($xml as $updatedResource){
			$data['user_id'] = (string) $updatedResource->subscriptionId;
			$data['date'] = (string) $updatedResource->date;
			$data['collectionType'] = (string) $updatedResource->collectionType;
		}

		$sql = "INSERT INTO Updates(message)
				VALUES (". $this->db->escape($data['date']) .")";
		$this->db->query($sql);		
		header('HTTP/1.0 204 No Content');
		header('Content-Length: 0',true);
		header('Content-Type: text/html',true);
		flush();
	}
}