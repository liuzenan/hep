<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller{

	public function index(){

		$this->load->helper('form');
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$this->load->view('signup');
		}

	}

	public function linkFB(){
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$this->load->view('linkFacebook');
		}
	}

	public function fbLogin(){
		if($this->session->userdata('user_id')){
			$username = $this->input->post('username');

			if($username){
				$data=array(
					'username'=>$username,
					'fb'=>1
					);
				$this->db->where('id',$this->session->userdata('user_id'));
				$this->db->update('user', $data);
				echo 'success';
			}else{
				echo 'user info not complete. username: ' . $username . ' email: '. $email . ' firstname: ' . $firstname .' lastname: ' . $lastname;
			}
		}else{
		}
	}

	public function updateProfilePic(){
		if($this->session->userdata('user_id')){
			$profile_pic = $this->input->post('profile_pic');

			if($profile_pic){
				$data=array(
					'profile_pic'=>$profile_pic
					);
				$this->db->where('id',$this->session->userdata('user_id'));
				$this->db->update('user', $data);
				echo 'success';
			}else{
				echo 'user info not complete. username: ' . $username . ' email: '. $email . ' firstname: ' . $firstname .' lastname: ' . $lastname;
			}
		}else{
		}
	}


	public function submit(){
		if($this->session->userdata('user_id')){
			$firstname = $this->input->post("firstname");
			$lastname = $this->input->post("lastname");
			$email = $this->input->post("email");
			$house = $this->input->post("house");
			
			if($firstname&&$lastname&&$email){
				$data=array();

				$data['first_name'] = $firstname;
				$data['last_name'] = $lastname;
				$data['email'] = $email;
				$data['admin'] = 0;
				$data['leader'] = 0;
				$data['phantom'] = 0;
				$data['staff'] = 0;


				if($house!="0"){
					$data['house_id'] = $house;
				}
				$this->db->where('id',$this->session->userdata('user_id'));
				$this->db->update('user', $data);
			}else{

			}
		}else{
		}
	}
}