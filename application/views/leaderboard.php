<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<li class="<?php if($currentTab=="student") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/student"; ?>">Student</a></li>
			<li class="<?php if($currentTab=="house") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/house"; ?>">House</a></li>
			<li class="<?php if($currentTab=="staff") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/staff"; ?>">Tutor</a></li>
		</ul>
		<?php $this->load->view('templates/' . $currentTab) ?>
	</div>
</div>