<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<label>Add additional e-mail message:</label>
			<textarea id="mailMsg" class="span12" rows="20"><?php echo $emailmsg ?></textarea>
			<button id="updateMail" class="btn btn-primary pull-right">Update</button>
		</div>
	</div>
</div>