<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/css/style.css">
  </head>
  <body>
  <div id="fb-root"></div>
  <script>
    var base_url = <?php echo base_url(); ?>;
    window.fbAsyncInit = function() {
      // init the FB JS SDK
      FB.init({
        appId      : '590330994347289', // App ID from the App Dashboard
        channelUrl : '<?php echo substr(base_url(), strpos(base_url(), '//')); ?>fitbit/channel.php', // Channel File for x-domain communication
        status     : true, // check the login status upon init?
        cookie     : true, // set sessions cookies to allow your server to access the session?
        xfbml      : true  // parse XFBML tags on this page?
      });

      // Additional initialization code such as adding Event Listeners goes here
    FB.getLoginStatus(function(response) {
      if (response.status === 'connected') {
        // connected            
            FB.api('/me',function(response){

                  $.ajax({
                    type:"POST",
                    url:base_url + "login/facebookLogin",
                    dataType:'json',
                    data:{
                      username:response.username
                    }
                  }).done(function(data){
                    if (data.success) {
                        window.location.href = base_url + "home";
                    } else {
                      if (data.error=="noemail") {
                        alert("you need to sign up for your detailed information first");
                        window.location.href = base_url + "login";
                      } else if(data.error=="nouser") {
                        alert("You need to register with fitbit account first for facebook login");
                        $(".emailinput").addClass("show");
                        $("#facebooklogin").attr("disabled", "disabled");
                        link();
                      }
                    }
                  });
            });

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

  function link() {
    $("#linkWithFacebook").click(function(event){
      event.preventDefault();
      FB.login(function(response) {
            if (response.authResponse) {
               FB.api('/me',function(response){
                $.ajax({
                    type:"POST",
                    url:base_url + "login/linkWithFacebook",
                    dataType:'json',
                    data:{
                      username:response.username,
                      email:$("#email").val()
                    }
                }).done(function(data){
                  if (data.success) {
                    window.location.href = base_url + "home";
                  } else {
                    window.location.href = base_url + "login";
                  }
                });
               });
            }

          });
    });
  };
  function login() {
    $("#facebooklogin").click(function(event){
      event.preventDefault();
        FB.login(function(response) {
            if (response.authResponse) {
                // connected

            FB.api('/me',function(response){

                  $.ajax({
                    type:"POST",
                    url:base_url + "login/facebookLogin",
                    dataType:'json',
                    data:{
                      username:response.username
                    }
                  }).done(function(data){
                    if (data.success) {

                      FB.api('/me?fields=picture.width(100).height(100)', function(response){

                        $.ajax({
                          url:"<?php echo base_url() . 'signup/updateProfilePic' ?>",
                          type:"POST",
                          data:{
                            profile_pic: response.picture.data.url
                          }

                        }).done(function(msg){
                          console.log('data saved: ' + msg);
                          window.location.href = base_url + "home";
                        });
                      });
                    } else {
                      if (data.error=="noemail") {
                        alert("you need to sign up for your detailed information first");
                        window.location.href = base_url + "login";
                      } else if(data.error=="nouser") {
                        alert("You need to register with fitbit account first for facebook login");
                        $(".emailinput").addClass("show");
                        $("#facebooklogin").attr("disabled", "disabled");
                        link();
                      }
                    }
                  });
            });

            } else {
                // cancelled
            }
        });
    });
  }
  </script>

<div class="wrapper welcome">
    <div class="container">
      <h3 class="maintitle">Health Enhancement Programme</h3>
      
      <div class="logincontrol well">
        <p class="registerbtn">
          <a href="<?php echo base_url() . 'login'?>" id="fitbitlogin" class="btn btn-primary">Register</a>
        </p>
        <p class="muted text-center"> or </p>
        <p class="loginbtn">
          <a href="<?php echo base_url() . 'login'?>" id="fitbitlogin" class="btn btn-primary">Login with Fitbit</a>
          <a href="javascript:void(0);" id="facebooklogin" class="btn btn-primary">Login with Facebook</a>
        </p>
        <div class="emailinput">
          <label for="email"><small class="muted">Please enter your email registered with HEP</small></label>
          <input type="text" class="input-block-level" id="email" name="email"><br>
          <button id="linkWithFacebook" class="btn btn-block">Link to Account</button>
        </div> 
      </div>
    </div>  
    <div>
      <p class='text-center'>
        Total <?php echo $summary->steps ?>K steps, <?php echo $summary->distance ?> kilometers,
        <?php echo $summary->sleep ?>K hours of sleep recorded in the system.
      </p>
      <p class='text-center'>
        <a href='mailto:hep-support@googlegroups.com'>Contact Us</a>
      </p>
    </div>
</div>
    <script src="<?php echo base_url(); ?>/js/jquery-1.9.1.min.js"></script>
    <script src="<?php echo base_url(); ?>/js/bootstrap.min.js"></script>
  </body>
</html>