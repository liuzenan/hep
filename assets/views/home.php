<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid myactivity">
				<?php $this->load->view('templates/todayActivity'); ?>
				<?php $this->load->view('templates/achievementList') ?>
				<?php $this->load->view('templates/challengeList') ?>
		</div>
	</div>
</div>