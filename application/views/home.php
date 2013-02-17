<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid myactivity">
    
        <?php $this->load->view('templates/challengeList');?>
				
				<?php $this->load->view('templates/todayActivity');?>
				<?php $this->load->view('templates/achievementList');?>
		</div>
	</div>
</div>
<script>
  var allChallenges = <?php echo json_encode($all); ?>
</script>
<!-- Modal -->
<div id="challengeModal" class="modal hide fade today" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel">Change/Drop Challenges</h3>
  </div>
  <div class="modal-body">
    <div class="challenge-wrapper row-fluid">
      
    </div>
  </div>
</div>