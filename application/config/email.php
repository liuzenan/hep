<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();
$config['mailpath'] = '/usr/sbin/sendmail';
$config['protocol'] = "sendmail";
$config['mailtype'] = 'html'; 
$config['charset'] = 'utf-8';
$config['newline'] = '\r\n';
$config['wordwrap'] = TRUE;