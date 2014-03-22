<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Fitbit App</title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>/css/bootstrap.min.css" rel="stylesheet" media="screen">
     <link rel="stylesheet" href="<?php echo base_url(); ?>/css/style.css">
    <script src="<?php echo base_url(); ?>/js/jquery-1.9.1.min.js"></script>
  </head>
  <body>
  	<div class="container">
		<div class="row-fluid">
			<div class="span4 offset4 well signup">
				<form action="" id="userinfo">
					<fieldset>
						<legend>More Information</legend>
						<label for="firstname">First Name</label>
						<input id="firstname" type="text" name="firstname">
						<label for="lastname">Last Name</label>
						<input id="lastname" type="text" name="lastname">
						<label for="email">Email</label>
						<input id="email" type="email" name="email">
						<label for="">House</label>
						<select name="house" id="house">
							<option value="0">Select a House...</option>
							<?php foreach ($houses as $id => $name) { ?>
								<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
							<?php } ?>
						</select>
						<label for="registrationcode">Registration Code</label>
						<input id="registrationcode" type="text" name="registrationcode" value="<?php echo $defaultCode; ?>">
						<button id="submitbtn" class="btn btn-large btn-block">Next</button>
            <p><a href='mailto:hep-support@googlegroups.com'>Contact us</a> if you encountered problems.</p>
					</fieldset>
				</form>
			</div>
		</div>
  	</div>
    <script src="<?php echo base_url(); ?>/js/bootstrap.min.js"></script>
    <script>
    	jQuery(document).ready(function($) {
    		$("#submitbtn").attr('disabled','disabled');

    		function checkValidity() {
		        if($("#firstname").val() != '' && $("#lastname").val() != '' && $("#email").val()!='' && $("#registrationcode").val()!='' && $("#house").val() != '0') {
		           $('#submitbtn').removeAttr('disabled');
		        } else {
		        	$("#submitbtn").attr('disabled','disabled');
		        }
		     }
    		$('#userinfo').change(checkValidity);
    		$('#userinfo').keyup(checkValidity);

    		$("#submitbtn").click(function(event){
    			event.preventDefault();
    			$.post("<?php echo base_url() . 'signup/submit' ?>", $("#userinfo").serialize(), function(data){
    				var msg = JSON.parse(data); 
    				if (msg.success) {
    					window.location.replace("<?php echo base_url() . 'signup/linkFB' ?>");
    				} else {
    					alert(msg.message);
    				}
    			});
    		});
    	});
    	
    </script>
  </body>
</html>