<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="/fitbit/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
  </head>
  <body>
  	<div class="container">
		<div class="row-fluid">
			<div class="span4 offset4 well">
				<form action="" id="userinfo">
					<fieldset>
						<legend>More Information</legend>
						<label for="firstname">First Name</label>
						<input type="text" name="firstname">
						<label for="lastname">Last Name</label>
						<input type="text" name="lastname">
						<label for="email">Email</label>
						<input type="email" name="email">
						<label for="">House</label>
						<select name="house" id="house">
							<option value="0">Select a House...</option>
							<option value="1">AAA</option>
							<option value="2">BBB</option>
							<option value="3">CCC</option>
							<option value="4">DDD</option>
							<option value="5">FFF</option>
							<option value="6">GGG</option>
							<option value="7">HHH</option>
							<option value="8">III</option>
							<option value="9">JJJ</option>
							<option value="10">KKK</option>
						</select>
						<button id="submitbtn" class="btn btn-large btn-block">Next</button>
					</fieldset>
				</form>
			</div>
		</div>
  	</div>
    <script src="/fitbit/assets/js/bootstrap.min.js"></script>
    <script>
    	jQuery(document).ready(function($) {

    		$("#submitbtn").click(function(event){
    			event.preventDefault();
    			$.post("<?php echo base_url() . 'signup/submit' ?>", $("#userinfo").serialize(), function(msg){
    				window.location.replace("<?php echo base_url() . 'signup/linkFB' ?>");
    			});
    		});
    	});
    	
    </script>
  </body>
</html>