<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

	public function index(){
		$this->output->cache(200);
		if(!$this->session->userdata('user_id')){
			redirect(base_url() . "login");
		}else{
			$this->statistics();
		}
	}

	public function statistics(){
		$this->output->cache(200);
			$data['active'] = 5;
			$data['displayName'] = $this->session->userdata('name');
			$data['avatar'] = $this->session->userdata('avatar');
			$data['stats'] = $this->getStats();
			$data['currentTab'] = "statistics";

			$this->load->view("templates/header", $data);
			$this->load->view("stats", $data);
			$this->load->view("templates/footer");
	}

	public function history(){
		$this->output->cache(200);
			$data['active'] = 5;
			$data['displayName'] = $this->session->userdata('name');
			$data['avatar'] = $this->session->userdata('avatar');
			$data['chartTitle'] = 'Steps';
			date_default_timezone_set('UTC');
			$currentDate = date('Y-m-d');
			$weekBegin = date('Y-m-d', strtotime('this week', time()));

			$data['startDate'] = $weekBegin;
			$data['currentActivity'] = $this->getActivity('steps', $weekBegin, $currentDate);
			$data['currentTab'] = "history";

			$this->load->view("templates/header", $data);
			$this->load->view("stats", $data);
			$this->load->view("templates/footer");
	}

	private function getActivity($type, $startDate, $endDate){

		try {
			$this->load->model('Activities_model','activities');
			$data = $this->activities->get_activity($startDate, $endDate);
		} catch (Exception $e) {
		}
		
		if($data){
			switch ($type) {
				case 'steps':
					# code...
					$activity = $data['steps'];
					break;
				case 'floors':
					# code...
					$activity = $data['floors'];
					break;
				case 'calories':
					# code...
					$activity = $data['calories'];
					break;

				case 'distance':
					# code...
					$activity = $data['distance'];
					break;

				case 'activity_calories':
					# code...
					$activity = $data['activity_calories'];
					break;
				case 'elevation':
					# code...
					$activity = $data['elevation'];
					break;
				default:
					# code..
					break;
			}

			return $activity;
		}
	}

	private function getStats(){
		if($this->session->userdata('oauth_token')&&$this->session->userdata('oauth_secret')){
			$this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
			$xml = $this->fitbitphp->getActivityStats();
			//echo print_r($xml);
			$best = $xml->best->tracker;
			$lifetime = $xml->lifetime->tracker;

			$stats =array();
			$stats['best'] = array(
				'calories' => array(
						'date'=>(string) $best->caloriesOut->date,
						'value'=>(string) $best->caloriesOut->value . " Calories"
					),
				'distance' => array(
						'date'=>(string) $best->distance->date,
						'value'=>(string) $best->distance->value . " km"
					),
				'floors' => array(
						'date'=>(string) $best->floors->date,
						'value'=>(string) $best->floors->value . " levels"
					),
				'steps' => array(
						'date'=>(string) $best->steps->date,
						'value'=>(string) $best->steps->value . " steps"
					)
				);

			$stats['lifetime'] = array(
				'calories' => (string) $lifetime->caloriesOut . " Calories",
				'distance' => (string) $lifetime->distance . " km",
				//'floors' => (string) $lifetime->floors . " levels",
				'steps' => (string) $lifetime->steps . " steps"
				);
			return $stats;
		}else{
			echo "oauth error";
		}
	}
}