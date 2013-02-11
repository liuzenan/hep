<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<li class="<?php if($currentTab=="overall") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/overall"; ?>">Individual</a></li>
			<li class="<?php if($currentTab=="house") echo "active" ?>"><a href="<?php echo base_url() . "leaderboard/house"; ?>">House</a></li>
		</ul>
		<?php 
			if ($currentTab == "house" || $currentTab == "staff") {
				$this->load->view('templates/'.$currentTab);
			} else {
				$this->load->view('templates/overall');
			}
		?>
	</div>
</div>