<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Checksubscribe extends CI_Controller
{

    public function index()
    {
        echo "<pre>";
        $query = $this->db->query("SELECT * FROM user");
        $user_set = array();
        $error_set = array();
        if ($query->num_rows() > 0) {
            # code...
            foreach ($query->result() as $row) {
                $user_token = $row->oauth_token;
                $user_secret = $row->oauth_secret;
                $user_id = $row->id;
                $this->fitbitphp->setOAuthDetails($user_token, $user_secret);
                try {
                    $xml = $this->fitbitphp->getSubscriptions();
                    if (count($xml->apiSubscriptions->apiSubscription) != 2) {
                        var_dump($xml);
                    } else {

                    }
                    foreach ($xml->apiSubscriptions->apiSubscription as $value) {
                        $subscriber = $value->subscriberId;
                        $collectionType = $value->collectionType;
                        $subscriptionId = $value->subscriptionId;
                        if (strcmp($subscriptionId, $user_id . "-" . $collectionType) != 0) {
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
        echo "<br/>==========================<br/>";
        var_dump($user_set);
        echo "</pre>";

    }

    public function checkuser($user_id= - 1) {
        if ($user_id > 0) {
            $query = $this->db->query("SELECT * FROM user WHERE id=" . $user_id);
            if ($query->num_rows()) {
                $result = $query->row();
                $user_token = $result->oauth_token;
                $user_secret = $result->oauth_secret;
                $this->fitbitphp->setOAuthDetails($user_token, $user_secret);
                try {
                    $xml = $this->fitbitphp->getSubscriptions();
                    foreach ($xml->apiSubscriptions->apiSubscription as $value) {
                        var_dump((string)$value);
                        echo '<br/>-----<br/>';
                        $collectionType = (string)$value->collectionType;
                        $subscriptionId = (string)$value->subscriptionId;
                        //$this->fitbitphp->deleteSubscription($subscriptionId, "/" . $collectionType, $collectionType);

                    }
                } catch (Exception $e) {
                    $xml = $this->fitbitphp->getSubscriptions();
                    var_dump($xml->apiSubscriptions);
                }

            }
        }
    }
    public function subscribealluser()
    {
        $query = $this->db->query("SELECT * FROM user");
        $user_set = array();
        if ($query->num_rows() > 0) {
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
                        echo "<p>error for user" . $user_id . "</p>";
                    }

                }

            }
        }

        echo "success";
    }

    public function unsubscribealluser()
    {
        $query = $this->db->query("SELECT * FROM user");
        $user_set = array();
        if ($query->num_rows() > 0) {
            # code...
            foreach ($query->result() as $result) {
                $user_token = $result->oauth_token;
                $user_secret = $result->oauth_secret;
                $this->fitbitphp->setOAuthDetails($user_token, $user_secret);
                try {
                    $xml = $this->fitbitphp->getSubscriptions();
                    if (count($xml->apiSubscriptions->apiSubscription) == 0) {
                        echo '<p>No subscription for: ' . $result->id . '</p>';
                    } 
                    foreach ($xml->apiSubscriptions->apiSubscription as $value) {
                        var_dump((string)$value->collectionType);
                        $collectionType = (string)$value->collectionType;
                        $subscriptionId = (string)$value->subscriptionId;
                        $this->fitbitphp->deleteSubscription($subscriptionId, "/" . $collectionType, $collectionType);
                        echo '<p>removing ' . $subscriptionId . ' ' . $collectionType.'</p>';

                    }
                } catch (Exception $e) {
                    echo '<p>caught: ' . $result->id . ' - ' . $e->getMessage();
                    // $xml = $this->fitbitphp->getSubscriptions();
                    // var_dump($xml->apiSubscriptions);
                }

            }
        }

        echo "<p>end</p>";
    }

    public function subscribeuser($user_id = -1)
    {
        if ($user_id > 0) {
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
                    echo "<p>error for user" . $user_id . "</p>";
                }
            }
        }
    }

    public function unsubscribeuser($user_id = -1)
    {
        if ($user_id > 0) {
            $query = $this->db->query("SELECT * FROM user WHERE id=" . $user_id);
            if ($query->num_rows()) {
                $result = $query->row();
                $user_token = $result->oauth_token;
                $user_secret = $result->oauth_secret;
                $this->fitbitphp->setOAuthDetails($user_token, $user_secret);
                try {
                    $xml = $this->fitbitphp->getSubscriptions();
                    foreach ($xml->apiSubscriptions->apiSubscription as $value) {
                        var_dump((string)$value->collectionType);
                        $collectionType = (string)$value->collectionType;
                        $subscriptionId = (string)$value->subscriptionId;
                        $this->fitbitphp->deleteSubscription($subscriptionId, "/" . $collectionType, $collectionType);

                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

            }
        }
    }

    public function resubscribeuser($user_id = -1)
    {
        if ($user_id > 0) {
            # code...
            $query = $this->db->query("SELECT * FROM user WHERE id=" . $user_id);
            if ($query->num_rows()) {
                # code...
                $result = $query->row();
                $user_token = (string)$result->oauth_token;
                $user_secret = (string)$result->oauth_secret;
                $this->fitbitphp->setOAuthDetails($user_token, $user_secret);

                try {
                    $subscriptionActivityId = $user_id . "-activities";
                    $subscriptionSleepId = $user_id . "-sleep";
                    $this->fitbitphp->addSubscription($subscriptionActivityId, "/activities", "activities");
                    $this->fitbitphp->addSubscription($subscriptionSleepId, "/sleep", "sleep");
                } catch (Exception $e) {
                    echo "<p>error for user" . $user_id . "</p>";
                    try {
                        $xml = $this->fitbitphp->getSubscriptions();
                        foreach ($xml->apiSubscriptions->apiSubscription as $value) {
                            $collectionType = (string)$value->collectionType;
                            $subscriptionId = (string)$value->subscriptionId;
                            try {
                                $this->fitbitphp->deleteSubscription($subscriptionId, "/" . $collectionType, $collectionType);
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