<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="row-fluid">
	<div class="span4 offset2">
		<form action="" id="userinfo">
			<fieldset>
				<legend>Profile Setting</legend>
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
		<button class="btn btn-large btn-block btn-primary" id="facebookbtn">Link with Facebook</button>
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
