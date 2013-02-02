<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function index(){
		$this->session->sess_destroy();
		$this->fitbitphp->initSession(base_url() . "logout");
		$this->fitbitphp->resetSession();
		session_destroy();
		redirect(base_url());
	}
}