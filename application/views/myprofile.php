<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<li class="<?php if($currentTab=="student") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/student"; ?>">News Feed</a></li>
			<li class="<?php if($currentTab=="house") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/house"; ?>">History</a></li>
			<li class="<?php if($currentTab=="staff") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/staff"; ?>">Following</a></li>
			<li class="<?php if($currentTab=="staff") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/staff"; ?>">Followers</a></li>
		</ul>
		<?php $this->load->view('templates/todayActivity'); ?>
		<?php $this->load->view('templates/newsFeed'); ?>
	</div>
</div>