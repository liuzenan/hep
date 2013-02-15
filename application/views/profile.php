		<div class="row-fluid">
			<div class="span4 offset4 well ">
				<form action="" id="userinfo">
					<fieldset>
						<legend>Profile Setting</legend>
						<label for="firstname">First Name</label>
						<input id="firstname" type="text" name="firstname" value="<?php echo $first_name ?>">
						<label for="lastname">Last Name</label>
						<input id="lastname" type="text" name="lastname" value="<?php echo $last_name ?>">
						<label for="email">Email</label>
						<input id="email" type="email" name="email" value="<?php echo $email ?>">
						<label for="">House</label>
						<select name="house" id="house" value="<?php echo $house_id ?>">
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
							<option value="-1">Tutor</option>
						</select>
						<label for="">Gender</label>
						<select name="gender" id="gender" value="<?php echo $gender ?>">
							<option value="FEMALE">Female</option>
							<option value="MALE">Male</option>
						</select>
						<label class="checkbox">
							<input type="checkbox" id="badge_email_unsub" name="badge_email_unsub"  value="<?php echo $badge_email_unsub ?>">Badge email notification
						</label>
						<label class="checkbox">
							<input type="checkbox" id="daily_email_unsub" name="daily_email_unsub" value="<?php echo $badge_email_unsub ?>"> Daily challenge notification
						</label>
						<label class="checkbox">
							<input type="checkbox" id="badge_email_unsub" name="badge_email_unsub"> Challenge completion notification
						</label>
						<button id="submitbtn" class="btn btn-large btn-block">Update</button>
					</fieldset>
				</form>
			</div>
		</div>

		<script src="/assets/js/bootstrap.min.js"></script>
		<script>
		jQuery(document).ready(function($) {
			$("#submitbtn").attr('disabled','disabled');
			$('input[type="text"], input[type="email"]').keyup(function() {
				if($("#firstname").val() != '' && $("#lastname").val() != '' && $("#email").val()!='' && $("#gender").val()!='') {
					$('#submitbtn').removeAttr('disabled');
				}
			});

			$("#submitbtn").click(function(event){
				event.preventDefault();
				$.post("<?php echo base_url() . 'signup/submit' ?>", $("#userinfo").serialize(), function(msg){
				//window.location.replace("<?php echo base_url() . 'signup/linkFB' ?>");
			});
			});
		});

		</script>
