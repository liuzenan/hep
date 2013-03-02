<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staticpages extends CI_Controller {
	public function index(){
		$this->faq();
	}

	public function faq() {
		$this->load->view('faq');
	}
}