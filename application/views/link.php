<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="<?php echo base_url(); ?>/js/jquery-1.9.1.min.js"></script>
  </head>
  <body>
  	<div class="container">
		<div class="row-fluid">
			<div class="span4 offset4 well">
				<form action="" id="userinfo">
					<fieldset>
						<legend>Link with Fitbit Account?</legend>
						<p>This is your first time login with Facebook, would you like to link it with your fitbit account?</p>
						<label for="fitbitaccount">Fitbit ID</label>
						<input type="text" name="fitbitaccount">
						</select>
						<button id="submitbtn" class="btn btn-large btn-block">Link to Fitbit</button>
						<a href="">Skip</a>
					</fieldset>
				</form>
			</div>
		</div>
  	</div>
    <script src="<?php echo base_url(); ?>/js/bootstrap.min.js"></script>
    <script>
    	jQuery(document).ready(function($) {


    	});
    	
    </script>
  </body>
</html>