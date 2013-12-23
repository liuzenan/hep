<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['mailconfig'] = Array(
      'protocol' => 'smtp',
      'smtp_host' => 'ssl://smtp.gmail.com',
      'smtp_user' => 'your_user_name',
      'smtp_pass' => 'your_password',
      'smtp_port' => '465',
      'newline' => "\r\n",
      'crlf' => "\r\n",
      'mailtype' => 'html',
      'charset' => 'utf-8'
);