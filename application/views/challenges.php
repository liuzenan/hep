<div class="row-fluid" id="allChallenges">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid control-bottons">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="my") echo "active" ?>"><a href="<?php echo base_url() . "challenges" ?>">Current</a></li>
					<li class="<?php if($tab=="all") echo "active" ?>"><a href="<?php echo base_url() . "challenges/all" ?>">All</a></li>
					<li class="<?php if($tab=="completed") echo "active" ?>"><a href="<?php echo base_url() . "challenges/completed" ?>">Completed</a></li>
				</ul>
		</div>
		<div id="challenges">
			<?php $this->load->view('templates/challenge'); ?>
		</div>
	</div>
</div>