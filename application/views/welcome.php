<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="/fitbit/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/fitbit/assets/css/style.css">
  </head>
  <body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      // init the FB JS SDK
      FB.init({
        appId      : '502310249809910', // App ID from the App Dashboard
        channelUrl : '//54.251.40.149/fitbit/channel.php', // Channel File for x-domain communication
        status     : true, // check the login status upon init?
        cookie     : true, // set sessions cookies to allow your server to access the session?
        xfbml      : true  // parse XFBML tags on this page?
      });

      // Additional initialization code such as adding Event Listeners goes here
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        // connected

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
    $("#fblogin").click(function(event){
      event.preventDefault();
        FB.login(function(response) {
            if (response.authResponse) {
                // connected

            FB.api('/me',function(response){

              $.ajax({
                url:"<?php echo base_url() . 'signup/fbLogin' ?>",
                type:"POST",
                data:{
                  username: response.username,
                  email: response.email,
                  firstname: response.first_name,
                  lastname: response.last_name
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
<div class="wrapper">
    <div class="container">
      <h1 class="maintitle">HEP Platform</h1>
      <p class="loginbtn"><a href="<?php echo base_url() . 'login'?>" id="fitbitlogin" class="btn btn-large btn-primary">Login with Fitbit</a></p>
    </div>  
</div>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="/fitbit/assets/js/bootstrap.min.js"></script>
  </body>
</html>