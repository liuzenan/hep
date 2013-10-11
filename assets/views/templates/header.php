<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
    <title>HEP Platform</title>
    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/datepicker.css">
    <link rel="stylesheet" href="/assets/css/timepicker.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="/assets/js/highcharts.js"></script>
  </head>
  <body>
  	<div class="container">
      <div class="navbar">
        <div class="navbar-inner">
          <a class="brand" href="<?php echo base_url() . 'home'?>">HEP Platform</a>
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="icon-bell icon-large"></i>
                <?php if (!empty($notifications)): ?>
                  <span id="user-notification" class="badge badge-info"><?php echo count($notifications) ?></span>
                <?php endif ?>
              </a>
              <ul class="dropdown-menu">
              <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $key => $value): ?>
                  <li><a href="<?php echo base_url() . "notification/redirect/" . $value->id ?>"><?php echo $value->description ?></a></li>
                <?php endforeach ?>
              <?php endif ?>
              <?php if (empty($notifications)): ?>
                <li><a href="#">No new notifications</a></li>          
              <?php endif ?>
              </ul> 
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img class="avatar" src="<?php echo $avatar ?>" alt="">
                <?php echo $displayName ?>
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url() . "logout" ?>">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>