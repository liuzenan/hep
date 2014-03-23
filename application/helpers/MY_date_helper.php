<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function my_date($datestr) {
	return date('d/m/Y', strtotime($datestr));
}