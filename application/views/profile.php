<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="row-fluid linkfacebook">
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
				<select name="house" id="house">
					<option value="0">Select a House...</option>
					<?php for($i=1; $i<=10; $i++): ?>
						<?php if($house_id == $i): ?>
							<option value="<?php echo $i ?>" selected><?php echo $i ?></option>
						<?php else: ?>
							<option value="<?php echo $i ?>"><?php echo $i ?></option>
						<?php endif ?>
					<?php endfor ?>
				</select>
				<label for="">Gender</label>
				<select name="gender" id="gender" value="<?php echo $gender ?>">
					<?php if($gender=="FEMALE"):?>
						<option value="FEMALE" selected>Female</option>
						<option value="MALE">Male</option>
					<?php else: ?>
						<option value="FEMALE">Female</option>
						<option value="MALE" selected>Male</option>
					<?php endif ?>
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
