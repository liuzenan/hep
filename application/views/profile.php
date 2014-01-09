<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="row-fluid">
	<div class="span4 offset2">
		<div id="savealertcontainer" class="row-fluid">

		</div>
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
						<input type="checkbox" id="daily_email_unsub" name="daily_email_unsub" checked> Daily email report
					<?php else: ?>
						<input type="checkbox" id="daily_email_unsub" name="daily_email_unsub" > Daily email report
					<?php endif ?>
				</label>
				<?php if ($isTutor): ?>
				<label class="checkbox">
					<?php if($hide_progress==0):?>	
						<input type="checkbox" id="hide_progress" name="hide_progress" checked> Show my progress to public
					<?php else: ?>
						<input type="checkbox" id="hide_progress" name="hide_progress"> Show my progress to public
					<?php endif ?>
				</label>
				<?php endif ?>
			</fieldset>
		</form>
		<button class="btn btn-large btn-block btn-primary" id="facebookbtn">Link with Facebook</button>
		<br/>
		<p id='status'></p>
	</div>
</div>
</div>
<script>
jQuery(document).ready(function($) {
	var lastTimestamp;
	var animation;
	function updateSuccess() {
		$('#status')
			.text('All settings updated')
			.removeClass('text-info')
			.addClass('text-success');

		animation = window.setTimeout(function() {
			$('#status').fadeOut();
		}, 1000);

	}

	function updateSaving() {
		window.clearTimeout(animation);

		$('#status')
			.stop()
			.text('Updating your settings...')
			.show()
			.css('opacity', 1)
			.removeClass('text-success')
			.addClass('text-info');
	}

	function updateFailure() {

	}
	$(".checkbox input").click(function(event){
		var now = new Date().getTime();
		lastTimestamp = now;
		updateSaving();
		$.post("<?php echo base_url() . 'profile/update' ?>", $("#userinfo").serialize(), function(msg){
				if (now === lastTimestamp) {
					updateSuccess();
				}
				console.log(msg);
			});
	});
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
				$("#savealertcontainer").html('<div class="alert alert-success save-alert show"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Your settings have been saved</div>');
			});
		} else {
			alert("Please fill all the infomation");
		}
	
	});
});

</script>
