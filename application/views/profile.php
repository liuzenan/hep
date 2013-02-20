<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="row-fluid">
	<div class="span4 offset2">
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
				<select name="house" id="house">
					<option value="0">Select a House...</option>
					<option value="1" selected="<?php if($house_id==1) echo "selected" ?>">1</option>
					<option value="2" selected="<?php if($house_id==2) echo "selected" ?>">2</option>
					<option value="3" selected="<?php if($house_id==3) echo "selected" ?>">3</option>
					<option value="4" selected="<?php if($house_id==4) echo "selected" ?>">4</option>
					<option value="5" selected="<?php if($house_id==5) echo "selected" ?>">5</option>
					<option value="6" selected="<?php if($house_id==6) echo "selected" ?>">6</option>
					<option value="7" selected="<?php if($house_id==7) echo "selected" ?>">7</option>
					<option value="8" selected="<?php if($house_id==8) echo "selected" ?>">8</option>
					<option value="9" selected="<?php if($house_id==9) echo "selected" ?>">9</option>
					<option value="10" selected="<?php if($house_id==10) echo "selected" ?>">10</option>
				</select>
				<label for="">Gender</label>
				<select name="gender" id="gender" value="<?php echo $gender ?>">
					<option value="FEMALE">Female</option>
					<option value="MALE">Male</option>
				</select>
				<label class="checkbox">
					<?php if($badge_email_unsub==0):?>
						<input type="checkbox" id="badge_email_unsub" name="badge_email_unsub"  checked>Badge email notification
					<?php else: ?>
						<input type="checkbox" id="badge_email_unsub" name="badge_email_unsub"  >Badge email notification
					<?php endif ?>
				</label>
				<label class="checkbox">
					<?php if($daily_email_unsub==0):?>
						<input type="checkbox" id="daily_email_unsub" name="daily_email_unsub" checked> Daily challenge notification
					<?php else: ?>
						<input type="checkbox" id="daily_email_unsub" name="daily_email_unsub" > Daily challenge notification
					<?php endif ?>
				</label>
				<label class="checkbox">
					<?php if($challenge_email_unsub==0):?>	
						<input type="checkbox" id="challenge_email_unsub" name="challenge_email_unsub" checked> Challenge completion notification
					<?php else: ?>
						<input type="checkbox" id="challenge_email_unsub" name="challenge_email_unsub" > Challenge completion notification
					<?php endif ?>
				</label>
				<button id="submitbtn" class="btn btn-large btn-block">Update</button>
			</fieldset>
		</form>
	</div>
</div>
</div>

<script src="/assets/js/bootstrap.min.js"></script>
<script>
jQuery(document).ready(function($) {
	$("#submitbtn").click(function(event){
		event.preventDefault();
		if($("#firstname").val() != '' 
			&& $("#lastname").val() != '' 
			&& $("#email").val()!='' 
			&& $("#gender").val()!=''
			&& $("#house").val() != 0
			) {
			console.log("valid");
			console.log($("#userinfo").serialize());
			
			$.post("<?php echo base_url() . 'profile/update' ?>", $("#userinfo").serialize(), function(msg){
				console.log(msg);
			});
		} else {
			alert("Please fill all the infomation");
		}
	
	});
});

</script>
