<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="/fitbit/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
     <link rel="stylesheet" href="/fitbit/assets/css/style.css">
    <script src="http://code.jquery.com/jquery-latest.js"></script>
  </head>
  <body>
  	<div class="container">
		<div class="row-fluid">
			<div class="span4 offset4 well signup">
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
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
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