<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
     <link rel="stylesheet" href="/assets/css/style.css">
  </head>
  <body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      // init the FB JS SDK
      FB.init({
        appId      : '590330994347289', // App ID from the App Dashboard
        channelUrl : '//hep.d2.comp.nus.edu.sg/fitbit/channel.php', // Channel File for x-domain communication
        status     : true, // check the login status upon init?
        cookie     : true, // set sessions cookies to allow your server to access the session?
        xfbml      : true  // parse XFBML tags on this page?
      });

      // Additional initialization code such as adding Event Listeners goes here
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        // connected
        $("#facebookbtn").attr("disabled","disabled");
        $("#facebookbtn").html("Facebook Connected");
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
    <div class="row-fluid linkfacebook">
      <div class="span4 offset4">
        <button class="btn btn-large btn-block btn-primary" id="facebookbtn">Link with Facebook</button>
      </div>
      <?php if(empty($active) || $active!="profile"): ?>
        <div class="span4 skip"><p><a href="<?php echo base_url() . 'home' ?>">Skip</a></p></div>
      <?php endif ?>
    </div>
  </div>
    <script src="/assets/js/jquery-1.9.1.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
  </body>
</html>