<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<li class="<?php if($currentTab=="house") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/house"; ?>">House - Overall</a></li>
			<li class="<?php if($currentTab=="weekly") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/weekly"; ?>">House - This Week</a></li>
			<li class="<?php if($currentTab=="lastweek") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/lastweek"; ?>">House - Last Week</a></li>
			<li class="<?php if($currentTab=="overall") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/overall"; ?>">Individual</a></li>
			<li class="<?php if($currentTab=="staff") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/staff"; ?>">Tutor</a></li>
		</ul>
		<?php 
			if ($currentTab == "overall" || $currentTab == "staff") {
				$this->load->view('templates/'.$currentTab);
			} else if ($currentTab == 'weekly' || $currentTab == 'lastweek') {
				$this->load->view('templates/weekly');
			} else {
				$this->load->view('templates/house');
			}
		?>
	</div>
</div>