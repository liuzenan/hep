<div class="row-fluid" id="allChallenges">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid control-bottons">
			<div class="pull-right">
				<a href="#" class="btn">All Challenges</a>
				<a href="#" class="btn">Completed Challenges</a>
			</div>
		</div>
		<div id="challenges">
			<?php $this->load->view('templates/challenge'); ?>
		</div>
	</div>
</div>