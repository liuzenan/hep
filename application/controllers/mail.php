<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model("Mail_model");
    }

    public function dailyReport()
    {
        $data = array('message' => 'Debug-StartDailyReport-'.$this->input->ip_address());
        $this->db->insert('log', $data);

        //$this->updateBadge();
        $this->checkSubscribe();
        $uids = $this->User_model->loadDailyReportUsers();
        foreach ($uids as $uid) {
            $this->Mail_model->sendDailyReport($uid->id);
        }
        $data = array('message' => 'Debug-EndDailyReport');
        $this->db->insert('log', $data);

    }

    public function syncReminder($secret='') {
        if ($secret != ACCESS_SECRET) {
            echo 'Unauthorised access';
            return;
        } 

        $today = date('Y-m-d', time());
        $ago = date('Y-m-d', strtotime(NO_SYNC_REMINDER, time()));
        
        $activityQuery = "SELECT u.id as uid, u.first_name as name, u.email as email 
        FROM user u
        JOIN activity a on a.user_id = u.id
        WHERE `date` BETWEEN '$ago' AND '$today'
        AND u.phantom = 0
        AND u.staff = 0
        AND u.email IS NOT NULL
        AND a.user_id NOT IN (
            SELECT i.user_id FROM invalidperiod AS i
            WHERE (start_date < $ago AND end_date > $today)
        )
        GROUP BY a.user_id
        HAVING SUM(a.steps) = 0";

        $activityData = $this->db->query($activityQuery)->result();

        $sleepQuery = "SELECT u.id as uid, u.first_name as name, u.email as email 
        FROM user u
        JOIN sleep s on s.user_id = u.id
        WHERE `date` BETWEEN '$ago' AND '$today'
        AND u.phantom = 0
        AND u.staff = 0
        AND u.email IS NOT NULL
        AND s.user_id NOT IN (
            SELECT i.user_id FROM invalidperiod AS i
            WHERE (start_date < $ago AND end_date > $today)
        )
        GROUP BY s.user_id
        HAVING SUM(s.total_time) = 0";

        $sleepData = $this->db->query($activityQuery)->result();

        $candidates = array();

        $activity = 1;
        $sleep = 2;
        foreach ($activityData as $row) {
            $candidates[$row->uid]['type'] = 'activities';
            $candidates[$row->uid]['name'] = $row->name;
            $candidates[$row->uid]['email'] = $row->email;
        }
        foreach ($sleepData as $row) {
            if (isset($candidates[$row->uid]['type'])) {
                $candidates[$row->uid]['type'] = 'activities and sleep';
            } else {
                $candidates[$row->uid]['type'] = 'sleep';
            }

            $candidates[$row->uid]['name'] = $row->name;
            $candidates[$row->uid]['email'] = $row->email;
        }
        foreach ($candidates as $uid => $candidate) {
            $this->Mail_model->sendReminder($candidate['name'], $candidate['email'], $candidate['type']);
            echo '<br/>Sent to '. $uid . ' ' . $candidate['name'] . ' - ' . $candidate['email'];
            $logEntry = array('message' => 'SyncReminder-'.$uid.'-'.$candidate['email'],
                    'content' =>$candidate['type']);
            $this->db->insert('log', $logEntry);
        }

    }

    public function invite($code = NULL) {
        if ($code) {
            $query = $this->db->get_where('registration', array('code' => $code));
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $this->Mail_model->sendInvitation($row->name, $row->email, $row->code);
            } else {
                echo 'code not found';
            }
        } else {
            // invite all codes that have not been used
            $query = $this->db->get_where('registration', array('supercode' => 0, 'used' => 0));
            foreach ($query->result() as $row) {
                $this->Mail_model->sendInvitation($row->name, $row->email, $row->code);
            }
        }
        echo 'success';
    }

    private function checkSubscribe()
    {
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

        foreach ($user_set as $uid) {
            $this->subscribeuser($uid);
        }

        foreach ($error_set as $uid) {
            $this->subscribeuser($uid);
        }
    }

    private function subscribeuser($user_id = -1)
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

    public function testMail($uid)
    {
        $this->Mail_model->sendDailyReport($uid);
    }

    public function updateBadge()
    {
        $this->Badge_model->scanBadge();
    }

    public function sendNewBadgeEarnedNotification($user_id, $badge_id)
    {
        echo $this->Mail_model->sendBadgeEarnedMessage($user_id, $badge_id);
    }

    public function sendDailyReport($user_id)
    {
        echo $this->Mail_model->sendDailyReport($user_id);

    }

    public function unsubBadgeNotification($user_id)
    {
        $this->User_model->unsubBadgeNotification($user_id);
        echo "You have been sucessfully unsubscribed from badge notification";
    }

    public function unsubChallengeNotification($user_id)
    {
        $this->User_model->unsubChallengeNotification($user_id);
        echo "You have been sucessfully unsubscribed from challenge notification";
    }

    public function unsubDailyNotification($user_id)
    {
        $this->User_model->unsubDailyNotification($user_id);
        echo "You have been sucessfully unsubscribed from daily notification";
    }


}