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
    <script src="/assets/js/jquery-1.9.1.min.js"></script>
    <script src="/assets/js/highcharts.js"></script>
  </head>
  <body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      // init the FB JS SDK
      FB.init({
        appId      : '502310249809910', // App ID from the App Dashboard
        channelUrl : '//hep.d2.comp.nus.edu.sg/fitbit/channel.php', // Channel File for x-domain communication
        status     : true, // check the login status upon init?
        cookie     : true, // set sessions cookies to allow your server to access the session?
        xfbml      : true  // parse XFBML tags on this page?
      });

      // Additional initialization code such as adding Event Listeners goes here
    FB.getLoginStatus(function(response) {
      $("#logoutbtn").click(function(event){
        event.preventDefault();
        FB.logout(function(response){
          window.location.replace("<?php echo base_url() . 'logout' ?>");
        });
      });
      if (response.status === 'connected') {
        console.log("connected");
      } else if (response.status === 'not_authorized') {
        // not_authorized
        login();
      } else {
        // not_logged_in
        login();
      }
     });

    };

    // Load the SDK's source Asynchronously
    // Note that the debug version is being actively developed and might 
    // contain some type checks that are overly strict. 
    // Please report such bugs using the bugs tool.
    (function(d, debug){
       var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement('script'); js.id = id; js.async = true;
       js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
       ref.parentNode.insertBefore(js, ref);
     }(document, /*debug*/ false));

  function login() {
    $("#facebookbtn").click(function(event){
      event.preventDefault();
        FB.login(function(response) {
            if (response.authResponse) {
                // connected
            $("#facebookbtn").attr("disabled","disabled");
            $("#facebookbtn").html("Redirecting...");
            FB.api('/me',function(response){

              $.ajax({
                url:"<?php echo base_url() . 'signup/fbLogin' ?>",
                type:"POST",
                data:{
                  username: response.username
                }
              }).done(function(msg){

                FB.api('/me?fields=picture.width(100).height(100)', function(response){

                  $.ajax({
                    url:"<?php echo base_url() . 'signup/updateProfilePic' ?>",
                    type:"POST",
                    data:{
                      profile_pic: response.picture.data.url
                    }

                  }).done(function(msg){
                    console.log('data saved: ' + msg);

                    window.location.replace("<?php echo base_url() . 'home' ?>");
                  });
                });

              });
            });

            } else {
                // cancelled
            }
        }, {scope: 'email'});
    });
  }

  </script>
  	<div class="container">
      <div class="navbar">
        <div class="navbar-inner">
          <a class="brand" href="<?php echo base_url() . 'home'?>">HEP Platform</a>
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="icon-bell icon-large" style="line-height: 20px;"></i>
                <?php if (!empty($notifications)): ?>
                  <span id="user-notification" class="badge badge-info"><?php echo count($notifications) ?></span>
                <?php endif ?>
              </a>
              <ul class="dropdown-menu">
              <?php if (!empty($notifications)): ?>
                <li><a class="clearNotification" href="#" >Clear all notifications</a></li>
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
                <li><a href="<?php echo base_url() . "profile" ?>">Setting</a></li>
                <li><a id="logoutbtn" href="javascript:void(0);">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>