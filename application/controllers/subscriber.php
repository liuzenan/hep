<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber extends CI_Controller
{


    public function index()
    {
        $this->activities();
        $this->sleep();

    }

    public function update()
    {
        $data = array('message' => 'Debug-StartDailyUpdate-'.$this->input->ip_address());
        $this->db->insert('log', $data);

        $sql1 = "SELECT DISTINCT id
				FROM   user
				WHERE  fitbit_id IS NOT NULL";
        $query1 = $this->db->query($sql1);
        foreach ($query1->result() as $row1) {
            $uid = $row1->id;

            $date = date("Y-m-d", time() - 24 * 60 * 60);
            //echo $uid.'-'.$date.'<br>';
            $this->getActivities($uid, $date);
            $this->getSleep($uid, $date);
        }
        $data = array('message' => 'Debug-EndDailyUpdate');
        $this->db->insert('log', $data);
        echo "finish";
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
            $msg = array(
                "success" => true,
            );
        }
        echo json_encode($msg);
    }

    public function refresh_user($user_id=NULL)
    {
        if (!$user_id) {
            $msg = array(
                "success" => false,
                "login" => false
            );
        } else {
            $date = date("Y-m-d", time());
            $ysd = date("Y-m-d", time() - 24 * 60 * 60);
            try {
                $this->getActivities($user_id, $date, true);
                $this->getSleep($user_id, $date, true);
                $this->getActivities($user_id, $ysd, true);
                $this->getSleep($user_id, $ysd, true);
                $msg = array(
                    "success" => true,
                );
                
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        echo json_encode($msg);
    }

    public function weeklyTally($secret='') {
        if ($secret != ACCESS_SECRET) {
            echo 'Unauthorised access';
            return;
        } 
        $stepsLeaderboard = $this->Challenge_model->getWeeklyLeaderboardbySteps(true);
        $sleepLeaderboard = $this->Challenge_model->getWeeklyLeaderboardbysleep(true);

        $old_data = "SELECT * FROM house WHERE id > 0";
        $data = $this->db->query($old_data)->result();
        $houses = array();
        foreach($data as $row) {
            $houses[$row->id] = array('old' => array(
                'score' => $row->score, 'steps_multiplier' => $row->steps_multiplier, 'sleep_multiplier' => $row->sleep_multiplier
                ), 'new' => array());
        }

        $cap = WEEKLY_POINT_MAX;
        $max = $stepsLeaderboard[0]->steps;
        $steps_rank = 1;
        foreach($stepsLeaderboard as $row) {
            $current = &$houses[$row->house_id];
            $current['new']['steps_rank'] = $steps_rank;
            $current['new']['steps_score'] = MIN($cap * $row->steps/$max * $current['old']['steps_multiplier'], $cap);
            $current['new']['score'] = $current['old']['score'] + $current['new']['steps_score'];
            $current['new']['steps_multiplier'] = 2 - $row->steps/$max;
            $steps_rank ++;
        }

        $max = $sleepLeaderboard[0]->sleep;
        $sleep_rank = 1;
        foreach($sleepLeaderboard as $row) {
            $current = &$houses[$row->house_id];
            $current['new']['sleep_rank'] = $sleep_rank;
            $current['new']['sleep_score'] = MIN($cap * $row->sleep/$max * $current['old']['sleep_multiplier'], $cap);
            $current['new']['score'] = $current['new']['score'] + $current['new']['sleep_score'];
            $current['new']['sleep_multiplier'] = 2 - $row->sleep/$max;
            $sleep_rank ++;
        }

        $data = array();
        foreach ($houses as $id => $row) {
            $data[] = array(
                'id' => $id,
                'score' => empty($row['new']['score']) ? $row['old']['score'] : round($row['new']['score']),
                'steps_multiplier' => empty($row['new']['steps_multiplier']) ? $row['old']['steps_multiplier'] : $row['new']['steps_multiplier'],
                'sleep_multiplier' => empty($row['new']['sleep_multiplier']) ? $row['old']['sleep_multiplier'] : $row['new']['sleep_multiplier'],
                'last_steps_rank' => empty($row['new']['steps_rank']) ? $steps_rank : $row['new']['steps_rank'],
                'last_sleep_rank' => empty($row['new']['sleep_rank']) ? $sleep_rank : $row['new']['sleep_rank'],
                'last_steps_score' => empty($row['new']['steps_score']) ? 0 : $row['new']['steps_score'],
                'last_sleep_score' => empty($row['new']['sleep_score']) ? 0 : $row['new']['sleep_score']
            );
        }
        $this->db->update_batch('house', $data, 'id'); 
        echo 'Done';

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
                    } else {

                    }
                }
            }
        } catch (Exception $e) {

        }
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
                    } else {

                    }
                }
            }
        } catch (Exception $e) {

        }
    }

    public function validateFitbitRecord($action='update', $date=null)
    {
        if ($date) {
            $ysd = $date;
        } else {
            $ysd = date("Y-m-d", time() - 24 * 60 * 60 * BADGE_DELAY_DAYS);
        }
        $dates_sql = "SELECT DISTINCT date from activity where date = '" . $ysd . "' ORDER BY date DESC";
        $dquery = $this->db->query($dates_sql);
        foreach ($dquery->result() as $date_row) {
            echo '<p>start validation</p>';
            $uids_sql = "
			select temp.* from
			(select sum(ia.floors) as ifloors, a.floors, sum(ia.steps) as isteps, 
			a.steps, a.user_id from intradayactivity as ia, activity as a 
			where ia.user_id=a.user_id AND DATE(ia.activity_time)=a.date AND a.date=?  group by a.user_id) as temp
			where temp.ifloors<temp.floors OR temp.isteps<temp.steps";
            $query = $this->db->query($uids_sql, array($date_row->date));
            foreach ($query->result() as $row) {
                echo "refresh ". $row->user_id . ' intradaysteps: ' . $row->isteps . ' steps: ' . $row->steps . '<br/>';
                if ($action == 'update') {
                    $this->getActivities($row->user_id, $date_row->date);
                }
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

    public function getActivities($user_id, $date, $debug = false)
    {

        $keypair = $this->getUserKeyPair($user_id);
        if ($keypair) {
            try {

                $this->load->model('Activity_model', 'activities');
                $this->activities->insert_intraday_activity($user_id, $date, $keypair);
                $this->activities->sync_activity_single_day($date, $user_id, $keypair);
            } catch (Exception $e) {
                if ($debug) {
                    echo $e->getMessage();
                }
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
                $this->activities->sync_activity_single_day($date, $user_id, $keypair);
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
                    $this->activities->sync_activity_single_day($date, $user_id, $keypair);
                    $this->getSleep($user_id, $date);
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
        return $this->User_model->getUserKeyPair($userId);
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