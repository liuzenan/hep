<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getLevel')){
	function getLevel($points){

		$coeff = 0.1;
		$level = floor($coeff*sqrt($points));
		return $level;
	}	
}


if ( ! function_exists('getNextLevelPoints')){

	function getNextLevelPoints($level){

		$coeff = 0.1;
		$points = ceil(($level/$coeff)*($level/$coeff));

		return $points;

	}
}


if ( ! function_exists('getExpPoints')){

	function getExpPoints($steps, $floors, $active_score, $efficiency){


		$points = floor(($steps / 1000)+ $floors + ($active_score / 100)+($efficiency / 10));
		return $points;

	}
}
