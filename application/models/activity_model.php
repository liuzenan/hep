<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends My_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function getActivitySummary()
    {
        $sql = "SELECT format(Sum(steps)/1000,0) as steps, format(Sum(distance),0) as distance FROM activity";
        $query = $this->db->query($sql);

        $res = $query->row();
        $sql2 = "SELECT format(Sum(time_asleep)/1000/60,2) as sleep_time FROM sleep";
        $query2 = $this->db->query($sql2);

        $res->sleep = $query2->row()->sleep_time;
        return $res;
    }

    function getActivityToday($user_id)
    {
        return $this->getActivityOnDate($user_id, parent::getDateToday());

    }
    function getActivityLastWeek($user_id) {
        $this_monday = date('Y-m-d', strtotime('last monday', strtotime('tomorrow')));
        $last_monday = date('Y-m-d', strtotime('- 7 days', strtotime($this_monday)));
        $last_sunday = date('Y-m-d', strtotime('- 1 day', strtotime($this_monday)));

        $sql = "SELECT SUM(steps) as steps FROM activity
        WHERE user_id = $user_id
        AND DATE BETWEEN '$last_monday' AND '$last_sunday' ";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row()->steps;
        } else {
            return 0;
        }
    }

    function getSleepLastWeek($user_id) {
        $this_monday = date('Y-m-d', strtotime('last monday', strtotime('tomorrow')));
        $last_monday = date('Y-m-d', strtotime('- 7 days', strtotime($this_monday)));
        $last_sunday = date('Y-m-d', strtotime('- 1 day', strtotime($this_monday)));

        $sql = "SELECT SUM(total_time) as sleep FROM sleep
        WHERE user_id = $user_id
        AND DATE BETWEEN '$last_monday' AND '$last_sunday' ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row()->sleep;
        } else {
            return 0;
        }
    }

    function getActivityYesterday($user_id)
    {
        return $this->getActivityOnDate($user_id, parent::getDateYesterday());
    }

    function getActivityOnDate($user_id, $date)
    {
        $query = $this->db->get_where('activity', array('user_id' => $user_id, 'date' => $date));
        $vars = $query->row();
        if (empty($vars)) {
            $vars = new StdClass();
            $vars->user_id = $user_id;
            $vars->date = $date;
            $vars->steps = 0;
            $vars->calories = 0;
            $vars->active_score = 0;
            $vars->distance = 0;
            $vars->elevation = 0;
            $vars->min_sedentary = 0;
            $vars->min_lightlyactive = 0;
            $vars->min_lightlyactive = 0;
            $vars->min_veryactive = 0;
            $vars->activity_calories = 0;
            $vars->last_update = $this->getLastUpdate($user_id);
        }
        return $vars;
    }

    function getLastUpdate($user_id)
    {
        $sql = "SELECT Max(last_update) as time
		FROM   activity	
		WHERE  user_id = ?";
        $lastupdate = $this->db->query($sql, array($user_id))->row()->time;
        if (empty($lastupdate)) {
            return "2013-02-14 00:00:00";
        } else {
            return $lastupdate;
        }
    }


    function getMaxActivityToday()
    {
        //get activities data
        $sql = "SELECT max(a.steps) AS max_steps,
		max(a.distance) AS max_distance, max(a.calories) as max_calories
		FROM activity AS a
		WHERE a.date=?";
        $query = $this->db->query($sql, array(parent::getDateToday()));
        return $query->row();

    }


    function getAverageActivityToday()
    {
        //get activities data
        $sql = "SELECT CEILING(avg(a.steps)) AS avg_steps,
		CEILING(avg(a.distance)) AS avg_distance, 
		CEILING(avg(a.calories)) as avg_calories
		FROM activity AS a
		WHERE a.date=? AND (a.steps>0 OR a.distance>0)";
        $query = $this->db->query($sql, array(parent::getDateToday()));
        return $query->row();
    }

    function getSleepData($user_id, $date)
    {
        $sql = "SELECT Sum(time_asleep) AS total_time,
		date
		FROM   sleep
		WHERE  user_id = ?
		AND date = ?
		GROUP BY date";
        $query = $this->db->query($sql, array($user_id, $date));
        $vars = $query->row();
        if (empty($vars)) {
            $vars = new StdClass();
            $vars->user_id = $user_id;
            $vars->date = $date;
            $vars->total_time = 0;
        }
        return $vars;
    }

    function getSleepStartTime($user_id, $start, $date)
    {
        $sql = "SELECT start_time FROM sleep WHERE start_time<=? AND start_time>='12:00:00' AND user_id=? AND date = ?";
        return $this->db->query($sql, array($start, $user_id, $date))->row();
    }

    function getActivityStats($user_id, $start, $end)
    {
        $sql = "SELECT Sum(steps)     AS steps,
		Sum(calories)  AS calories,
		Sum(floors)    AS floors,
		Sum(elevation) AS elevation
		FROM   intradayactivity
		WHERE  user_id = ?
		AND activity_time BETWEEN ? AND ?";
        $query = $this->db->query($sql, array($user_id, $start, $end));
        return $query->row();
    }


    function getAverageSleepToday()
    {
        //get activities data
        $sql = "SELECT avg(time_asleep) AS avg_time
		FROM sleep
		WHERE date=? AND time_asleep>0";
        $query = $this->db->query($sql, array(parent::getDateToday()));
        if ($query->num_rows() > 0) {
            return $query->row()->avg_time;
        } else {
            return 0;
        }
    }


    //-------------------------

    function getDailySleepData($user_id, $date)
    {
        $sql = "SELECT * FROM sleep
		WHERE user_id=" . $user_id . " AND date='" . $date . "'";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    function getLifetimeActivityData($user_id)
    {
        $sql = "SELECT sum(steps) AS total_steps,
		sum(calories) AS total_calories, sum(active_score) AS total_activescore, 
		sum(distance) AS total_distance, sum(elevation) AS total_elevation, 
		sum(activity_calories) AS total_activitycalories
		FROM activity
		WHERE user_id=" . $user_id . "
		GROUP BY user_id";

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    function getBestSleepData($user_id)
    {
        $sql = "select max(time_asleep) as time, date from sleep where user_id = ? group by date ORDER by time DESC";
        $query = $this->db->query($sql, array($user_id));
        return $query->row();
    }

    function getLifetimeSleepData($user_id)
    {
        $sql = "select sum(time_asleep) as time from sleep where user_id = ?";
        $query = $this->db->query($sql, array($user_id));
        $time = $query->row()->time;
        return empty($time) ? 0 : $time;

    }


    function get_activity($startDate, $endDate, $user_id)
    {
        $sql = "SELECT * FROM activity as a
		WHERE a.user_id = ?
		AND a.date
		BETWEEN '" . (string)$startDate . "' AND '" . (string)$endDate . "'
		ORDER BY a.date";

        $query = $this->db->query($sql, array($user_id));
        $resultSet['steps'] = array();
        $resultSet['distance'] = array();
        $resultSet['calories'] = array();
        $resultSet['activity_calories'] = array();
        $resultSet['elevation'] = array();
        $resultSet['sleep'] = array();
        $resultSet['sedentary'] = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $resultSet['steps'][$row->date] = $row->steps;
                $resultSet['calories'][$row->date] = $row->calories;
                $resultSet['distance'][$row->date] = $row->distance;
                $resultSet['activity_calories'][$row->date] = $row->activity_calories;
                $resultSet['elevation'][$row->date] = $row->elevation;
                $resultSet['sedentary'][$row->date] = number_format($row->min_sedentary / 60, 2);
            }
        }


        $sql2 = "SELECT * FROM sleep as a
		WHERE a.user_id = ?
		AND a.date
		BETWEEN '" . (string)$startDate . "' AND '" . (string)$endDate . "'
		ORDER BY a.date";
        $query2 = $this->db->query($sql2, array($user_id));

        if ($query2->num_rows() > 0) {
            foreach ($query2->result() as $row) {
                if (empty($resultSet['steps'][$row->date])) {
                    $resultSet['steps'][$row->date] = 0;
                    $resultSet['calories'][$row->date] = 0;
                    $resultSet['distance'][$row->date] = 0;
                    $resultSet['activity_calories'][$row->date] = 0;
                    $resultSet['elevation'][$row->date] = 0;
                }
                $resultSet['sleep'][$row->date] = number_format($row->time_asleep / 60, 2);
            }
        }


        for ($i = strtotime($startDate); $i <= strtotime($endDate); $i += 24 * 3600) {
            $date = date('Y-m-d', $i);
            if (empty($resultSet['steps'][$date])) {
                $resultSet['steps'][$date] = 0;
                $resultSet['distance'][$date] = 0;
                $resultSet['calories'][$date] = 0;
                $resultSet['activity_calories'][$date] = 0;
                $resultSet['elevation'][$date] = 0;
            }
            if (empty($resultSet['sleep'][$date])) {
                $resultSet['sleep'][$date] = 0;
            }
        }

        ksort($resultSet['steps']);

        ksort($resultSet['distance']);

        ksort($resultSet['calories']);

        ksort($resultSet['activity_calories']);

        ksort($resultSet['elevation']);

        ksort($resultSet['sleep']);


        return $resultSet;


    }


    function insert_intraday_activity($user_id, $date, $keypair)
    {
        // echo "insert intraday activity\n";
        // var_dump($user_id);
        // var_dump($date);
        try {
            if ($keypair) {
                $this->fitbitphp->setOAuthDetails($keypair['token'], $keypair['secret']);
                $calories = $this->fitbitphp->getIntradayTimeSeries("calories", $date);
                $steps = $this->fitbitphp->getIntradayTimeSeries("steps", $date);
                $floors = $this->fitbitphp->getIntradayTimeSeries("floors", $date);
                $elevation = $this->fitbitphp->getIntradayTimeSeries("elevation", $date);

                $intradayCalories = $calories->{'activities-calories-intraday'};
                $intradaySteps = $steps->{'activities-steps-intraday'};
                $intradayFloors = $floors->{'activities-floors-intraday'};
                $intradayElevation = $elevation->{'activities-elevation-intraday'};
                //var_dump($steps);
                $intradayActivityData = array();

                foreach ($intradaySteps->dataset->intradayData as $value) {
                    $currentTime = (string)$value->time;
                    if (intval($value->value) > 0) {
                        # code...
                        $intradayActivityData[$currentTime]['steps'] = $value->value;
                    }

                }

                foreach ($intradayCalories->dataset->intradayData as $value) {
                    $currentTime = (string)$value->time;
                    if (!empty($intradayActivityData[$currentTime])) {
                        # code...
                        $intradayActivityData[$currentTime]['calories'] = $value->value;
                        $intradayActivityData[$currentTime]['level'] = $value->level;
                    }
                }

                foreach ($intradayFloors->dataset->intradayData as $value) {
                    $currentTime = (string)$value->time;
                    if (!empty($intradayActivityData[$currentTime])) {
                        $intradayActivityData[$currentTime]['floors'] = $value->value;
                    }


                }

                foreach ($intradayElevation->dataset->intradayData as $value) {
                    $currentTime = (string)$value->time;
                    if (!empty($intradayActivityData[$currentTime])) {
                        $intradayActivityData[$currentTime]['elevation'] = $value->value;
                    }

                }
                // var_dump($intradayActivityData);
                foreach ($intradayActivityData as $key => $value) {
                    # code...

                    if (empty($value['steps']) || empty($value['calories']) || empty($value['level'])) {
                        # code...
                    } else {
                        $sql = "INSERT INTO intradayactivity(user_id, activity_time, steps, calories, calories_level, floors, elevation)
					VALUES (" . $user_id . ", '" . $date . " " . $key . "', " . $value['steps'] . ", " .
                            $value['calories'] . ", " . $value['level'] . ", "
                            . 0 . ", " . 0 . ") ON DUPLICATE KEY UPDATE
						steps = " . $value['steps'] . ", calories = " . $value['calories'] . ", calories_level= " . $value['level'] .
                            ", floors=" . $value['floors'] . ",elevation=" . $value['elevation'];

                        $this->db->query($sql);
                    }

                }
            } else {

            }
        } catch (Exception $e) {

        }
    }

    function getChallengeCompletionTime($user_id, $start, $end, $threshold, $type)
    {

        $sql = "SELECT y.activity_time,
	y.%s,
	y.user_id
	FROM   (SELECT t.activity_time,
		t.%s,
		t.user_id,
		(SELECT Sum(x.%s)
			FROM   intradayactivity AS x
			WHERE  x.user_id = ?
			AND x.activity_time <= ?
			AND x.activity_time >= ?
			AND x.activity_time <= t.activity_time
			AND x.user_id = t.user_id) AS running_total
FROM   intradayactivity AS t
WHERE  t.activity_time <= ?
AND t.activity_time >= ?
ORDER  BY t.activity_time) AS y
WHERE  y.running_total >= ?
ORDER  BY y.activity_time
LIMIT  1";
        $sql = sprintf($sql, $type, $type, $type);

        $result = $this->db->query($sql, array($user_id, $end, $start, $end, $start, $threshold));

        if ($result->num_rows() > 0) {
            return $result->row()->activity_time;
        } else {
            return $end;
        }
    }


    function sync_activity($basedate, $period, $user_id = NULL, $keypair = NULL)
    {

        try {

            if ($keypair) {
                $this->fitbitphp->setOAuthDetails($keypair['token'], $keypair['secret']);
            } else if ($this->session->userdata('oauth_token') && $this->session->userdata('oauth_secret')) {
                $this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
                $user_id = $this->session->userdata('user_id');
            } else {
                throw new Exception("no keypair");
            }


            $tracker_caloriesOut = $this->fitbitphp->getTimeSeries('tracker_caloriesOut', $basedate, $period);
            $tracker_steps = $this->fitbitphp->getTimeSeries('tracker_steps', $basedate, $period);
            $tracker_distance = $this->fitbitphp->getTimeSeries('tracker_distance', $basedate, $period);
            $tracker_floors = $this->fitbitphp->getTimeSeries('tracker_floors', $basedate, $period);
            $tracker_elevation = $this->fitbitphp->getTimeSeries('tracker_elevation', $basedate, $period);
            //$tracker_activeScore = $this->fitbitphp->getTimeSeries('tracker_activeScore', $basedate, $period);
            $tracker_activityCalories = $this->fitbitphp->getTimeSeries('tracker_activityCalories', $basedate, $period);
            $tracker_minutesSedentary = $this->fitbitphp->getTimeSeries('tracker_minutesSedentary', $basedate, $period);
            $tracker_minutesLightlyActive = $this->fitbitphp->getTimeSeries('tracker_minutesLightlyActive', $basedate, $period);
            $tracker_minutesFairlyActive = $this->fitbitphp->getTimeSeries('tracker_minutesFairlyActive', $basedate, $period);
            $tracker_minutesVeryActive = $this->fitbitphp->getTimeSeries('tracker_minutesVeryActive', $basedate, $period);

            $activitiesData = array();


            foreach ($tracker_caloriesOut as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_caloriesOut'] = $value->value;
            }

            foreach ($tracker_steps as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_steps'] = $value->value;
            }

            foreach ($tracker_distance as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_distance'] = $value->value;
            }

            foreach ($tracker_floors as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_floors'] = $value->value;
            }

            foreach ($tracker_elevation as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_elevation'] = $value->value;
            }

            // foreach ($tracker_activeScore as $value) {
            //     $currentDate = $value->dateTime;
            //     $activitiesData[$currentDate]['tracker_activeScore'] = $value->value;
            // }

            foreach ($tracker_activityCalories as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_activityCalories'] = $value->value;
            }

            foreach ($tracker_minutesSedentary as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_minutesSedentary'] = $value->value;
            }

            foreach ($tracker_minutesLightlyActive as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_minutesLightlyActive'] = $value->value;
            }

            foreach ($tracker_minutesFairlyActive as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_minutesFairlyActive'] = $value->value;
            }

            foreach ($tracker_minutesVeryActive as $value) {
                $currentDate = $value->dateTime;
                $activitiesData[$currentDate]['tracker_minutesVeryActive'] = $value->value;
            }

            //insert into database
            foreach ($activitiesData as $key => $value) {
                //var_dump($value);
                $sql = "INSERT INTO activity(user_id, date, steps, floors, calories, active_score, distance, elevation, min_sedentary,
					min_lightlyactive, min_fairlyactive, min_veryactive, activity_calories)
				VALUES (" . $user_id . ", '" . $key . "', "
                    . $value['tracker_steps'] . ", "
                    . $value['tracker_floors'] . ", "
                    . $value['tracker_caloriesOut'] . ", "
                    . -1 . ", "
                    . $value['tracker_distance'] . ", "
                    . $value['tracker_elevation'] . ", "
                    . $value['tracker_minutesSedentary'] . ", "
                    . $value['tracker_minutesLightlyActive'] . ", "
                    . $value['tracker_minutesFairlyActive'] . ", "
                    . $value['tracker_minutesVeryActive'] . ", "
                    . $value['tracker_activityCalories'] . ")
					ON DUPLICATE KEY UPDATE last_update=NOW(), steps= " . $value['tracker_steps'] . ", floors= " . $value['tracker_floors']
                    . ", calories= " . $value['tracker_caloriesOut'] . ", active_score= " . -1
                    . ", distance= " . $value['tracker_distance'] . ", elevation= " . $value['tracker_elevation']
                    . ", min_sedentary= " . $value['tracker_minutesSedentary'] . ", min_lightlyactive= "
                    . $value['tracker_minutesLightlyActive'] . ", min_fairlyactive= "
                    . $value['tracker_minutesFairlyActive'] . ", min_veryactive= "
                    . $value['tracker_minutesVeryActive'] . ", activity_calories= "
                    . $value['tracker_activityCalories'];
                $this->db->query($sql);
            }

        } catch (Exception $e) {

        }

    }

    function sync_activity_single_day($date, $user_id = NULL, $keypair = NULL)
    {

        try {

            if ($keypair) {
                $this->fitbitphp->setOAuthDetails($keypair['token'], $keypair['secret']);
            } else if ($this->session->userdata('oauth_token') && $this->session->userdata('oauth_secret')) {
                $this->fitbitphp->setOAuthDetails($this->session->userdata('oauth_token'), $this->session->userdata('oauth_secret'));
                $user_id = $this->session->userdata('user_id');
            } else {
                throw new Exception("no keypair");
            }

            $activityResponse = $this->fitbitphp->getActivities($date, $date);
            $summary = $activityResponse->summary;

            $tracker_caloriesOut = $summary->caloriesOut;
            $tracker_steps = $summary->steps;
            $tracker_floors = $summary->floors;
            foreach ($summary->distances->activityDistance as $distanceEntry) {
                if ($distanceEntry->activity == 'tracker') {
                    $tracker_distance = $distanceEntry->distance;
                    break;
                } else if ($distanceEntry->activity == 'tracker') {
                    $total_distance = $distanceEntry->distance;
                }
            }
            
            if (!isset($tracker_distance)) {
                if (isset($total_distance)) {
                    $tracker_distance = $total_distance;
                } else {
                    $tracker_distance = 0;
                }
            }
            $tracker_elevation = $summary->elevation;
            if ($tracker_elevation) {

            } else {
            	$tracker_elevation = 0;
            }
            if ($tracker_floors) {

            } else {
            	$tracker_floors = 0;
            }
            $tracker_activityCalories = $summary->activityCalories;
            $tracker_minutesSedentary = $summary->sedentaryMinutes;
            $tracker_minutesLightlyActive = $summary->lightlyActiveMinutes;
            $tracker_minutesFairlyActive = $summary->fairlyActiveMinutes;
            $tracker_minutesVeryActive = $summary->veryActiveMinutes;
            //insert into database
            
            $sql = "INSERT INTO activity(user_id, date, steps, floors, calories, active_score, distance, elevation, min_sedentary,
                min_lightlyactive, min_fairlyactive, min_veryactive, activity_calories)
            VALUES (" . $user_id . ", '" . $date . "', "
                . $tracker_steps . ", "
                . $tracker_floors . ", "
                . $tracker_caloriesOut . ", "
                . -1 . ", "
                . $tracker_distance . ", "
                . $tracker_elevation . ", "
                . $tracker_minutesSedentary . ", "
                . $tracker_minutesLightlyActive . ", "
                . $tracker_minutesFairlyActive . ", "
                . $tracker_minutesVeryActive . ", "
                . $tracker_activityCalories . ")
                ON DUPLICATE KEY UPDATE last_update=NOW(), steps= " . $tracker_steps . ", floors= " . $tracker_floors
                . ", calories= " . $tracker_caloriesOut . ", active_score= " . -1
                . ", distance= " . $tracker_distance . ", elevation= " . $tracker_elevation
                . ", min_sedentary= " . $tracker_minutesSedentary . ", min_lightlyactive= "
                . $tracker_minutesLightlyActive . ", min_fairlyactive= "
                . $tracker_minutesFairlyActive . ", min_veryactive= "
                . $tracker_minutesVeryActive . ", activity_calories= "
                . $tracker_activityCalories;
            $this->db->query($sql);

        } catch (Exception $e) {

        }

    }

}