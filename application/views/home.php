<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid myactivity">
				<?php $this->load->view('templates/challengeList'); ?>
				<?php $this->load->view('templates/todayActivity'); ?>
				<?php $this->load->view('templates/achievementList'); ?>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="challengeToday" class="modal hide fade tomorrow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <h3 id="myModalLabel">Change/Drop Challenges</h3>
  </div>
  <div class="modal-body">
    <table class="table">
    	<thead>
    		<td>Title</td>
    		<td>Description</td>
    		<td>Points</td>
    		<td>Join/Drop</td>
    	</thead>
    	<tbody>
    		<?php if (!empty($all_challenge)): ?>
    			<?php foreach ($all_challenge as $challenge): ?>
		     		<tr id="challengeId<?php echo $challenge->id; ?>">
		    			<td><?php echo $challenge->title ?></td>
		    			<td><?php echo $challenge->description ?></td>
		    			<td><?php echo $challenge->points ?></td>
		    			<td>			
		    				<button class="btn btn-link joinChallengeNow" data-challenge-id="<?php echo $challenge->id ?>" data-user-id="<?php echo $challenge->user_id ?>" <?php if($challenge->disabled_today) echo "disabled" ?>>Join</button>
							<button class="btn btn-link quitChallenge" data-cp-id="<?php echo $challenge->cp_id_today ?>" <?php if(!$challenge->joined_today) echo "disabled" ?>>Drop</button>
						</td>
		    		</tr>   				
    			<?php endforeach ?>
    		<?php endif ?>
    	</tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Done</button>
  </div>
</div>

<!-- Modal Tomorrow -->
<div id="challengeTmr" class="modal hide fade tomorrow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <h3 id="myModalLabel">Change/Drop Challenges</h3>
  </div>
  <div class="modal-body">
    <table class="table">
    	<thead>
    		<td>Title</td>
    		<td>Description</td>
    		<td>Points</td>
    		<td>Join/Drop</td>
    	</thead>
    	<tbody>
    		<?php if (!empty($all_challenge)): ?>
    			<?php foreach ($all_challenge as $challenge): ?>
		     		<tr id="challengeId<?php echo $challenge->id; ?>">
		    			<td><?php echo $challenge->title ?></td>
		    			<td><?php echo $challenge->description ?></td>
		    			<td><?php echo $challenge->points ?></td>
		    			<td>			
		    				<button class="btn btn-link joinChallengeTomorrow" data-challenge-id="<?php echo $challenge->id ?>" data-user-id="<?php echo $challenge->user_id ?>" <?php if($challenge->disabled_tomorrow) echo "disabled" ?>>Join</button>
							<button class="btn btn-link quitChallenge" data-cp-id="<?php echo $challenge->cp_id_tomorrow ?>" <?php if(!$challenge->joined_tomorrow) echo "disabled" ?>>Drop</button>
						</td>
		    		</tr>   				
    			<?php endforeach ?>
    		<?php endif ?>
    	</tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Done</button>
  </div>
</div>