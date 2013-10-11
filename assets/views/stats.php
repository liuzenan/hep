<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
      <ul class="nav nav-tabs">
         <li class="<?php if($currentTab=="statistics") echo "active" ?>"><a href="<?php echo base_url() . "stats"; ?>">Statistics</a></li>
         <li class="<?php if($currentTab=="history") echo "active" ?>"><a href="<?php echo base_url() . "stats/history"; ?>">History</a></li>
      </ul>
      <?php $this->load->view('templates/' . $currentTab) ?>
	</div>
</div>