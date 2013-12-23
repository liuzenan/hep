<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sha
 * Date: 22/9/13
 * Time: 9:11 PM
 * To change this template use File | Settings | File Templates.
 */

class My_Model extends CI_Model
{
    private $isTesting = false;

    private $date_today = "2013-03-10";
    private $date_yesterday = "2013-03-09";
    private $date_tomorrow = "2013-03-11";
    private $tomorrow_start = "2013-03-11 00:00:00";
    private $tomorrow_end = "2013-03-11 23:59:59";
    private $today_start = "2013-03-10 00:00:00";
    private $today_end = "2013-03-10 23:59:59";

    function __construct()
    {
        parent::__construct();
    }

    public function getDateToday()
    {
        return $this->isTesting ? $this->date_today : date("Y-m-d");

    }

    public function getDateYesterday()
    {
        return $this->isTesting ? $this->date_yesterday : date("Y-m-d", time() - 60 * 60 * 24);
    }

    public function getDateTomorrow()
    {
        return $this->isTesting ? $this->date_tomorrow : date("Y-m-d", time() + 60 * 60 * 24);
    }

}