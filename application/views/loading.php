<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
<!-- Modal -->
    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-header">
        <h3 id="myModalLabel">Please wait...</h3>
      </div>
      <div class="modal-body">
        <p>Please wait while the application is getting your information from Fitbit.com. This may take a few minutes...</p>
        <p>You will fill in your house and basic contact information on the next step.</p>
        <p>Please do not close this window...</p>
        <p>If this page does not refresh after a long time, you may click <a href="<?php echo base_url() . 'home' ?>">here</a> to try again.</p>
      </div>
    </div>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
          $("#myModal").modal({
            keyboard:false
          });
          $.ajax({
            url:"<?php echo base_url() . 'login/firstRun' ?>",
            dataType:"json"
          }).done(function(msg){
            if(msg.success){
              window.location.replace("<?php echo base_url() . 'signup' ?>");
            }else{
              $("#myModalLabel").html("Pulling data failed, please try again...");
            }
          });
        });
    </script>
  </body>
</html>