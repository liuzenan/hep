<!DOCTYPE html>
<html lang="en">
  <head>
    <title>HEP Platform</title>
    <!-- Bootstrap -->
    <link href="/fitbit/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/fitbit/assets/css/style.css">
    <link rel="stylesheet" href="/fitbit/assets/css/datepicker.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="/fitbit/assets/js/highcharts.js"></script>
  </head>
  <body>
  	<div class="container">
      <div class="navbar">
        <div class="navbar-inner">
          <a class="brand" href="<?php echo base_url() . 'home'?>">HEP Platform</a>
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