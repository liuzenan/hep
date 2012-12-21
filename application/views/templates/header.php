<!DOCTYPE html>
<html lang="en">
  <head>
    <title>HEP Platform</title>
    <!-- Bootstrap -->
    <link href="/fitbit/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/fitbit/assets/css/style.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="/fitbit/assets/js/highcharts.js"></script>
  </head>
  <body>
    <!--
    <div class="navbar navbar-static-top">
      <div class="navbar-inner">
        <a class="brand" href="#">HEP Platform</a>
        <ul class="nav">
          <li class="<?php if($active==1) echo 'active'; ?>"><a href="<?php echo base_url() . 'index.php/home'?>">Home</a></li>
          <li class="<?php if($active==2) echo 'active'; ?>"><a href="<?php echo base_url() . 'index.php/activity'?>">Activity</a></li>
          <li class="<?php if($active==3) echo 'active'; ?>"><a href="<?php echo base_url() . 'index.php/achievements'?>">Achievements</a></li>
          <li class="<?php if($active==4) echo 'active'; ?>"><a href="<?php echo base_url() . 'index.php/leaderboard'?>">Leaderboard</a></li>
        </ul>
        <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo $displayName ?>
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="#" title="">Profile</a></li>
                <li><a href="#" title="">Settings</a></li>
                <li><a href="#" title="">Logout</a></li>
              </ul>
            </li>
        </ul>
    </div>
    </div>
-->
  	<div class="container">
      <div class="navbar">
        <div class="navbar-inner">
          <a class="brand" href="<?php echo base_url() . 'index.php/home'?>">HEP Platform</a>
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img class="avatar" src="<?php echo $avatar ?>" alt="">
                <?php echo $displayName ?>
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="#">My profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>