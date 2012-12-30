<div class="row-fluid" id="myChallenges">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="pull-right">
			<a href="<?php echo base_url() . "challenges" ?>" class="btn">All Challenges</a>
			<a href="<?php echo base_url() . "challenges/createChallenge" ?>" class="btn btn-success">Create Challenge</a>
		</div>
		<div id="events">
			<?php $this->load->view('templates/events'); ?>
		</div>
		<div id="workouts">
			<?php $this->load->view('templates/workouts'); ?>
		</div>
	</div>
</div>