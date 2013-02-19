<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checksubscribe extends CI_Controller {

	public function index(){
		echo "<pre>";
		$query = $this->db->query("SELECT * FROM user");
		$user_set = array();
		$error_set = array();
		if ($query->num_rows()>0) {
			# code...
			foreach ($query->result() as $row) {
				$user_token = $row->oauth_token;
				$user_secret = $row->oauth_secret;
				$user_id = $row->id;
				$this->fitbitphp->setOAuthDetails($user_token, $user_secret);
				try {
					$xml = $this->fitbitphp->getSubscriptions();
					if (count($xml->apiSubscriptions->apiSubscription)!=2) {
						var_dump($xml);
					}
					foreach ($xml->apiSubscriptions->apiSubscription as $value) {
						$subscriber = $value->subscriberId;
						$collectionType = $value->collectionType;
						$subscriptionId = $value->subscriptionId;
						if (strcmp($subscriptionId, $user_id . "-" . $collectionType)!=0) {
							# code...
							array_push($error_set, $user_id);
						}
					}
				} catch (Exception $e) {
					array_push($user_set, $user_id);
				}
				
			}
		}
		var_dump($error_set);
		echo "==========================";
		var_dump($user_set);
		echo "</pre>";

	}

	public function subscribealluser(){
		$query = $this->db->query("SELECT * FROM user");
		$user_set = array();
		if ($query->num_rows()>0) {
			# code...
			foreach ($query->result() as $row) {
				$user_token = $row->oauth_token;
				$user_secret = $row->oauth_secret;
				$user_id = $row->id;
				$this->fitbitphp->setOAuthDetails($user_token, $user_secret);
				try {
					$xml = $this->fitbitphp->getSubscriptions();
				} catch (Exception $e) {
					array_push($user_set, $user_id);
					$subscriptionActivityId = $user_id . "-activities";
					$subscriptionSleepId = $user_id . "-sleep";
					try {
						$this->fitbitphp->addSubscription($subscriptionActivityId, "/activities", "activities");
						$this->fitbitphp->addSubscription($subscriptionSleepId, "/sleep", "sleep");
					} catch (Exception $e) {
						echo "<p>error for user" . $user_id ."</p>";
					}

				}
				
			}
		}

		echo "success";
	}

	public function subscribeuser($user_id=-1){
		if ($user_id>0) {
			# code...
			$query = $this->db->query("SELECT * FROM user WHERE id=" . $user_id);
			if ($query->num_rows()) {
				# code...
				$result = $query->row();
				$user_token = $result->oauth_token;
				$user_secret = $result->oauth_secret;
				$this->fitbitphp->setOAuthDetails($user_token, $user_secret);
					try {
						$subscriptionActivityId = $user_id . "-activities";
						$subscriptionSleepId = $user_id . "-sleep";
						$this->fitbitphp->addSubscription($subscriptionActivityId, "/activities", "activities");
						$this->fitbitphp->addSubscription($subscriptionSleepId, "/sleep", "sleep");
					} catch (Exception $e) {
						echo "<p>error for user" . $user_id ."</p>";
					}
			}
		}
	}

	public function resubscribeuser($user_id=-1){
		if ($user_id>0) {
			# code...
			$query = $this->db->query("SELECT * FROM user WHERE id=" . $user_id);
			if ($query->num_rows()) {
				# code...
				$result = $query->row();
				$user_token = $result->oauth_token;
				$user_secret = $result->oauth_secret;
				$this->fitbitphp->setOAuthDetails($user_token, $user_secret);

					try {
						$subscriptionActivityId = $user_id . "-activities";
						$subscriptionSleepId = $user_id . "-sleep";
						$this->fitbitphp->addSubscription($subscriptionActivityId, "/activities", "activities");
						$this->fitbitphp->addSubscription($subscriptionSleepId, "/sleep", "sleep");
					} catch (Exception $e) {
						echo "<p>error for user" . $user_id ."</p>";
						try {
							$xml = $this->fitbitphp->getSubscriptions();
							foreach ($xml->apiSubscriptions->apiSubscription as $value) {
								$collectionType = $value->collectionType;
								$subscriptionId = $value->subscriptionId;
								try {
									$this->fitbitphp->deleteSubscription($subscriptionId, "/" . $collectionType);
								} catch (Exception $e) {
									$xml = $this->fitbitphp->getSubscriptions();
									var_dump($xml->apiSubscriptions);
								}
								
							}
							$subscriptionActivityId = $user_id . "-activities";
							$subscriptionSleepId = $user_id . "-sleep";
							var_dump($subscriptionSleepId);
							var_dump($subscriptionActivityId);
							$this->fitbitphp->addSubscription($subscriptionActivityId, "/activities", "activities");
							$this->fitbitphp->addSubscription($subscriptionSleepId, "/sleep", "sleep");
							$xml = $this->fitbitphp->getSubscriptions();
							var_dump($xml);
						} catch (Exception $e) {
						}

					}



			}
		}


	}

}