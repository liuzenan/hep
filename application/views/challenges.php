<div class="row-fluid" id="allChallenges">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid control-bottons">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="completed") echo "active" ?>"><a href="<?php echo base_url() . "challenges/completed" ?>">Completed</a></li>
					<li class="<?php if($tab=="all") echo "active" ?>"><a href="<?php echo base_url() . "challenges/all" ?>">All</a></li>
					<li class="<?php if($tab=="history") echo "active" ?>"><a href="<?php echo base_url() . "challenges/history" ?>">History</a></li>
				</ul>
		</div>
		<div id="challenges" class="<?php echo $tab ?>">
			<?php if ($tab=="history"): ?>
				<?php $this->load->view('templates/historyChallenge'); ?>
			<?php else: ?>
				<?php $this->load->view('templates/challenge'); ?>
			<?php endif ?>
		</div>
	</div>
</div>