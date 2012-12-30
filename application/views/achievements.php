<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
      <ul class="nav nav-tabs">
         <li class="<?php if($currentTab=="daily") echo "active" ?>"><a href="<?php echo base_url() . "achievements/daily"; ?>">Daily Awards</a></li>
         <li class="<?php if($currentTab=="lifetime") echo "active" ?>"><a href="<?php echo base_url() . "achievements/lifetime"; ?>">Lifetime Achievements</a></li>
      </ul>
		<div class="row-fluid">
			<div class="span12">
				<ul class="media-list achievements">
					<?php foreach($achievements as $badge){
						$this->load->view("templates/achievementItem", $badge);
					} ?>
				</ul>
			</div>
		</div>
	</div>
</div>