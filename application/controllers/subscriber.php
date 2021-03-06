<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber extends CI_Controller
{


    public function index()
    {
        $this->activities();
        $this->sleep();

    }

    public function carryOverLastFridayChallenge()
    {
        ignore_user_abort(1);
        set_time_limit(0);
        $this->Challenge_model->carryOverLastWeekTimeBasedChallenges(date("Y-m-d"));
    }

    public function carryOverToTomorrow()
    {
        ignore_user_abort(1);
        set_time_limit(0);
        $this->Challenge_model->carryOverToTomorrow();
    }

    public function carryOverToToday()
    {
        ignore_user_abort(1);
        set_time_limit(0);
        $this->Challenge_model->carryOverToToday();
    }

    public function update()
    {
        $sql1 = "SELECT DISTINCT id
				FROM   user
				WHERE  fitbit_id IS NOT NULL";
        $query1 = $this->db->query($sql1);
        foreach ($query1->result() as $row1) {
            $uid = $row1->id;

            $date = date("Y-m-d", time() - 24 * 60 * 60);
            // echo $uid.'-'.$date.'<br>';
            $this->getActivities($uid, $date);
            $this->getSleep($uid, $date);
            $this->updateProgress($uid, $date);
        }
        // echo "finish";
    }

    public function refresh()
    {
        if (!$this->session->userdata('user_id')) {
            $msg = array(
                "success" => false,
                "login" => false
            );
        } else {
            $user_id = $this->session->userdata('user_id');
            $date = date("Y-m-d", time());
            $ysd = date("Y-m-d", time() - 24 * 60 * 60);
            $this->getActivities($user_id, $date);
            $this->getSleep($user_id, $date);
            $this->getActivities($user_id, $ysd);
            $this->getSleep($user_id, $ysd);
            $this->updateProgress($user_id, $date);
            $this->updateProgress($user_id, $ysd);
            $msg = array(
                "success" => true,
            );
        }
        echo json_encode($msg);
    }

    public function activities()
    {
        try {
            $notifications = $this->getNotification();
            if ($notifications) {
                foreach ($notifications as $data) {
                    $type = $data['collectionType'];
                    if (!strcmp($data['collectionType'], "activities")) {
                        $user_id = $data['user_id'];
                        $date = $data['date'];
                        $this->getActivities($user_id, $date);
                        $this->updateProgress($user_id, $date);
                    } else {

                    }
                }
            }
        } catch (Exception $e) {

        }
    }

    public function updateProgress($user_id, $date)
    {
        $this->Challenge_model->updateActivityProgress($user_id, $date);
    }

    public function updateTodayAllProgress()
    {
        $this->updateAllProgress(date("Y-m-d"));
    }

    public function updateAllProgress($date)
    {
        $query = $this->db->query("SELECT id FROM user");
        ob_end_flush();
        foreach ($query->result() as $value) {
            $user_id = $value->id;
            $this->getFullDayActivities($user_id, $date);
            $this->getSleep($user_id, $date);
            $this->updateProgress($user_id, $date);
            ob_start();
            echo "update progress for user " . $user_id . " " . $date . "\n";
            ob_flush();
            flush();
            ob_end_flush();
        }
    }

    public function refreshUserPastRecord($uid, $date)
    {
        ob_start();
        $this->getActivities($uid, $date);
        $this->getSleep($uid, $date);
        $this->updateProgress($uid, $date);
        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

        echo "update progress for user " . $uid . " " . $date . "\n";
        ob_flush();
        flush();
        ob_end_flush();
    }

    public function getUserAllActivitySince($uid, $date)
    {
        ignore_user_abort(1);
        set_time_limit(0);
        $end_date = date('Y-m-d');
        ob_end_flush();
        while (strtotime($date) <= strtotime($end_date)) {
            $this->refreshUserPastRecord($uid, $date);
        }
    }

    public function updateAllProgressSince($date)
    {
        ignore_user_abort(1);
        set_time_limit(0);
        $end_date = date('Y-m-d');
        while (strtotime($date) <= strtotime($end_date)) {
            $this->updateAllProgress($date);
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        echo "finish\n";
    }

    public function sleep()
    {
        try {
            $notifications = $this->getNotification();
            if ($notifications) {
                foreach ($notifications as $data) {
                    $type = $data['collectionType'];
                    if (!strcmp($data['collectionType'], "sleep")) {
                        $user_id = $data['user_id'];
                        $date = $data['date'];
                        $this->getSleep($user_id, $date);
                        $this->updateProgress($user_id, $date);
                    } else {

                    }
                }
            }
        } catch (Exception $e) {

        }
    }

    public function validateFitbitRecord()
    {
        $ysd = date("Y-m-d", time() - 24 * 60 * 60);
        $dates_sql = "SELECT DISTINCT date from activity where date = " . $ysd . " ORDER BY date DESC";
        $dquery = $this->db->query($dates_sql);
        foreach ($dquery->result() as $date_row) {
            $uids_sql = "
			select temp.* from
			(select sum(ia.floors) as ifloors, a.floors, sum(ia.steps) as isteps, 
			a.steps, a.user_id from intradayactivity as ia, activity as a 
			where ia.user_id=a.user_id AND DATE(ia.activity_time)=a.date AND a.date=?  group by a.user_id) as temp
			where temp.ifloors<temp.floors OR temp.isteps<temp.steps";
            $query = $this->db->query($uids_sql, array($date_row->date));
            foreach ($query->result() as $row) {
                // echo "refresh ". $row->user_id;
                $this->getActivities($row->user_id, $date_row->date);
                $this->updateProgress($row->user_id, $date_row->date);
            }
        }
    }

    public function getAllUserIntradayActivityFromDate($date)
    {
        ignore_user_abort(1);
        set_time_limit(0);
        // Start date

        // End date
        $end_date = date('Y-m-d');

        while (strtotime($date) <= strtotime($end_date)) {
            $sql1 = "SELECT DISTINCT id
					FROM   user
					WHERE  fitbit_id IS NOT NULL
					ORDER BY id";
            $query1 = $this->db->query($sql1);
            ob_end_flush();
            foreach ($query1->result() as $row1) {
                $uid = $row1->id;
                ob_start();
                echo "syncing activity data for user " . $uid . " " . $date . "\n";
                ob_flush();
                flush();
                ob_end_flush();
                try {
                    $keypair = $this->getUserKeyPair($uid);
                    if ($keypair) {
                        try {

                            $this->load->model('Activity_model', 'activities');
                            $this->activities->insert_intraday_activity($uid, $date, $keypair);
                        } catch (Exception $e) {

                        }
                    }
                    ob_start();
                    echo "synced activity data for user " . $uid . " \n";
                    ob_flush();
                    flush();
                } catch (Exception $e) {
                    ob_start();
                    echo "error for user " . $uid . " \n";
                    ob_flush();
                    flush();
                }
                ob_end_flush();
            }
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
        echo "finish\n";
    }

    public function getAllUserIntradayActivity($date)
    {
        ignore_user_abort(1);
        set_time_limit(0);
        $sql1 = "SELECT DISTINCT id
				FROM   user
				WHERE  fitbit_id IS NOT NULL
				ORDER BY id";
        $query1 = $this->db->query($sql1);
        ob_end_flush();
        foreach ($query1->result() as $row1) {
            $uid = $row1->id;
            ob_start();
            echo "syncing activity data for user " . $uid . " \n";
            ob_flush();
            flush();
            ob_end_flush();
            try {
                $keypair = $this->getUserKeyPair($uid);
                if ($keypair) {
                    try {

                        $this->load->model('Activity_model', 'activities');
                        $this->activities->insert_intraday_activity($uid, $date, $keypair);
                    } catch (Exception $e) {

                    }
                }
                ob_start();
                echo "synced activity data for user " . $uid . " \n";
                ob_flush();
                flush();
            } catch (Exception $e) {
                ob_start();
                echo "error for user " . $uid . " \n";
                ob_flush();
                flush();
            }
            ob_end_flush();
        }

        echo "finish\n";
    }

    // public function synchronizeActivityData() {
    // 	$dates_sql = "SELECT DISTINCT date from activity where date >= '2013-02-13' ORDER BY date DESC";
    // 	$dquery = $this->db->query($dates_sql);
    // 	foreach($dquery->result() as $date_row) {
    // 		$date = $date_row->date;
    // 		$uids_sql = "
    // 		SELECT temp.* from
    // 		(select sum(ia.floors) as ifloors, a.floors, sum(ia.steps) as isteps,
    // 		a.steps, a.user_id from intradayactivity as ia, activity as a
    // 		WHERE ia.user_id=a.user_id AND DATE(ia.activity_time)=a.date AND a.date=?  group by a.user_id) as temp
    // 		WHERE temp.ifloors<temp.floors OR temp.isteps<temp.steps";
    // 		$query = $this->db->query($uids_sql, array($date));
    // 		foreach($query->result() as $row) {
    // 			$delta_floor = $row->floors - $row->ifloors;
    // 			$delta_steps = $row->steps - $row->isteps;
    // 			$user_id = $row->user_id;
    // 			echo "increase ".$user_id. " floor ". $delta_floor . " step ". $delta_steps ." ". $date."<br>";

    // 			if($delta_floor > 0) {
    // 				$this->synchronizeFloor($user_id, $date, $delta_floor);
    // 			}
    // 			if($delta_steps > 0) {
    // 				$this->synchronizeSteps($user_id, $date, $delta_steps);
    // 			}
    // 			$this->updateProgress($user_id, $date);

    // 		}
    // 	}
    // }

    public function synchronizeFloor($user_id, $date, $delta)
    {
        $sql1 = "select MAX(activity_time) as time, floors from intradayactivity where user_id = ? and DATE(activity_time)=? and floors>0";
        $query1 = $this->db->query($sql1, array($user_id, $date));
        $row1 = $query1->row();
        $time = $row1->time;
        if (empty($row1)) {
            $sql1 = "select MAX(activity_time) as time, floors from intradayactivity where user_id = ? and DATE(activity_time)=?";
            $query1 = $this->db->query($sql1, array($user_id, $date));
            $row1 = $query1->row();
        }

        $floors = $row1->floors;
        $sql2 = "update intradayactivity set floors = floors + ? where user_id = ? and activity_time=?";
        $this->db->query($sql2, array($delta, $user_id, $time));
        echo "increase floor value for " . $user_id . " at " . $time . " by " . ($floors + $delta);

    }

    public function synchronizeSteps($user_id, $date, $delta)
    {
        $sql1 = "select MAX(activity_time) as time, steps from intradayactivity where user_id = ? and DATE(activity_time)=? and steps>0";
        $query1 = $this->db->query($sql1, array($user_id, $date));
        $row1 = $query1->row();
        $time = $row1->time;

        if (empty($time)) {
            $sql2 = "select MAX(activity_time) as time from intradayactivity where user_id = ? and DATE(activity_time)=?";
            $query2 = $this->db->query($sql2, array($user_id, $date));
            $row2 = $query2->row();
            $time = $row2->time;

        }
        $sql2 = "update intradayactivity set steps=steps+? where user_id = ? and activity_time=?";
        $this->db->query($sql2, array($delta, $user_id, $time));
        echo "increase step value for " . $user_id . " at " . $time . " by " . ($delta);


    }

    public function test()
    {
        ob_end_flush();
        for ($x = 1; $x <= 100; $x = $x + 1) {
            ob_start();
            echo $x . "\n";
            ob_flush();
            flush();
            ob_end_flush();
            usleep(100000);
        }

    }

    public function getActivities($user_id, $date)
    {

        $keypair = $this->getUserKeyPair($user_id);
        if ($keypair) {
            try {

                $this->load->model('Activity_model', 'activities');
                $this->activities->insert_intraday_activity($user_id, $date, $keypair);
                $this->activities->sync_activity($date, '1d', $user_id, $keypair);
            } catch (Exception $e) {

            }
        }
    }

    public function getFullDayActivities($user_id, $date)
    {

        $keypair = $this->getUserKeyPair($user_id);
        if ($keypair) {
            try {

                $this->load->model('Activity_model', 'activities');
                //$this->activities->insert_intraday_activity($user_id, $date, $keypair);
                $this->activities->sync_activity($date, '1d', $user_id, $keypair);
            } catch (Exception $e) {

            }
        }
    }

    public function updateAllUsers($date)
    {
        $query = $this->db->query("SELECT id from user");
        foreach ($query->result() as $value) {
            $user_id = $value->id;
            $keypair = $this->getUserKeyPair($user_id);
            if ($keypair) {
                try {
                    $this->load->model('Activity_model', 'activities');
                    $this->activities->insert_intraday_activity($user_id, $date, $keypair);
                    $this->activities->sync_activity($date, '1d', $user_id, $keypair);
                    $this->getSleep($user_id, $date);
                    $this->updateProgress($user_id, $date);
                    echo "updated for user" . $user_id . "\n";
                    flush();
                } catch (Exception $e) {
                    echo "error for user" . $user_id . "\n";
                    flush();
                }
            }

        }
    }

    public function getSleep($user_id, $date)
    {
        $keypair = $this->getUserKeyPair($user_id);
        if ($keypair) {
            $basedate = $date;
            $period = '1d';
            $this->fitbitphp->setOAuthDetails($keypair['token'], $keypair['secret']);

            $startTime = $this->fitbitphp->getTimeSeries('startTime', $basedate, $period);
            $timeInBed = $this->fitbitphp->getTimeSeries('timeInBed', $basedate, $period);
            $minutesAsleep = $this->fitbitphp->getTimeSeries('minutesAsleep', $basedate, $period);
            $minutesAwake = $this->fitbitphp->getTimeSeries('minutesAwake', $basedate, $period);
            $awakeningsCount = $this->fitbitphp->getTimeSeries('awakeningsCount', $basedate, $period);
            $minutesToFallAsleep = $this->fitbitphp->getTimeSeries('minutesToFallAsleep', $basedate, $period);
            $minutesAfterWakeup = $this->fitbitphp->getTimeSeries('minutesAfterWakeup', $basedate, $period);
            $efficiency = $this->fitbitphp->getTimeSeries('efficiency', $basedate, $period);

            $sleepData = array();
            foreach ($startTime as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['startTime'] = $value->value;
            }

            foreach ($minutesAwake as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['minutesAwake'] = $value->value;
            }
            foreach ($timeInBed as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['timeInBed'] = $value->value;
            }
            foreach ($minutesAsleep as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['minutesAsleep'] = $value->value;
            }

            foreach ($awakeningsCount as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['awakeningsCount'] = $value->value;
            }
            foreach ($minutesToFallAsleep as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['minutesToFallAsleep'] = $value->value;
            }

            foreach ($minutesAfterWakeup as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['minutesAfterWakeup'] = $value->value;
            }

            foreach ($efficiency as $value) {
                $currentDate = $value->dateTime;
                $sleepData[$currentDate]['efficiency'] = $value->value;
            }
            foreach ($sleepData as $key => $value) {
                $sql = "INSERT INTO sleep(user_id, `date`, total_time, time_asleep, start_time, awaken_count, min_awake, min_to_asleep, min_after_wakeup, efficiency)
				VALUES (" . $user_id . ", '" . $key . "', " . $value['timeInBed'] . ", " . $value['minutesAsleep'] . ", '" . $value['startTime'] . "', " . $value['awakeningsCount'] . ", " . $value['minutesAwake'] . ", " . $value['minutesToFallAsleep'] . ", " . $value['minutesAfterWakeup'] . ", " . $value['efficiency'] . ")
				ON DUPLICATE KEY UPDATE total_time=" . $value['timeInBed'] . ", time_asleep= " . $value['minutesAsleep'] . ", start_time= '" . $value['startTime'] . "', awaken_count= " . $value['awakeningsCount'] . ", min_awake= " . $value['minutesAwake'] . ", min_to_asleep= " . $value['minutesToFallAsleep'] . ", min_after_wakeup= " . $value['minutesAfterWakeup'] . ", efficiency= " . $value['efficiency'];
                $this->db->query($sql);
            }
        }

    }

    private function getUserKeyPair($userId)
    {
        if ($userId) {
            try {
                $sql = "SELECT oauth_token, oauth_secret FROM user
				WHERE id=" . $userId;
                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $keypair['token'] = $row->oauth_token;
                    $keypair['secret'] = $row->oauth_secret;

                    return $keypair;
                }

            } catch (Exception $e) {

            }
        }
    }

    private function getNotification()
    {
        try {

            $xml = simplexml_load_file($_FILES['updates']['tmp_name']);
            if ($xml) {
                $headers = apache_request_headers();
                $signature = $headers['X-Fitbit-Signature'];
                //$expected = hash_hmac('sha1', $_FILES['updates']['tmp_name'], '45e414dd49784ec3872a8ebbb74dcbb9&');
                if (!$signature) {
                    $sql = "INSERT INTO updates(`update`)
					VALUES ('no signature, IP: " . $this->input->ip_address() . "')";
                    $this->db->query($sql);
                } else {
                    $notifications = array();
                    foreach ($xml as $updatedResource) {
                        if ($updatedResource->subscriptionId) {
                            $user = explode("-", ((string)$updatedResource->subscriptionId), 2);
                            $data['user_id'] = $user[0];
                            $data['fitbit_id'] = (string)$updatedResource->ownerId;
                            $data['owner_type'] = (string)$updatedResource->ownerType;
                            $data['date'] = (string)$updatedResource->date;
                            $data['collectionType'] = (string)$updatedResource->collectionType;
                            array_push($notifications, $data);
                            $sql = "INSERT INTO updates(`update`,user_id,type)
							VALUES ('User data synced, IP: " . $this->input->ip_address() . "-" . $data['date'] . "', " . $data['user_id'] . ", '" . $data['collectionType'] . "')";
                            $this->db->query($sql);


                        }
                    }
                }
            } else {
                $sql = "INSERT INTO Updates(`update`)
				VALUES ('empty contents, IP: " . $this->input->ip_address() . "')";
                $this->db->query($sql);
            }


        } catch (Exception $e) {
            $sql = "INSERT INTO Updates(`update`)
			VALUES ('Error, IP: " . $this->input->ip_address() . "')";
            $this->db->query($sql);
        }
        header('HTTP/1.0 204 No Content');
        header('Content-Length: 0', true);
        header('Content-Type: text/html', true);
        flush();
        return $notifications;
    }
}