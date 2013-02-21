<div class="row-fluid" id="allChallenges">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<?php if (!($isLeader|| $isAdmin || $isTutor)): ?>
		<?php $this->load->view("challengeDisabled"); ?>
		<?php else: ?>
		<div class="alert">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>Game Rules</strong><br>
		  <ul style="margin-bottom:0px;">
		    <li>You can only join one challenge per day in each of the four categories (steps, climb, sleep, time-based).</li>
		    <li>If you quit a challenge, you will lose your progress for the current challenge completely.</li>
		</ul>
		</div>
		<div class="row-fluid control-bottons">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="all") echo "active" ?>"><a href="<?php echo base_url() . "challenges/all" ?>">All</a></li>
					<li class="<?php if($tab=="completed") echo "active" ?>"><a href="<?php echo base_url() . "challenges/completed" ?>">Completed</a></li>
				</ul>
		</div>
		<div id="challenges" class="<?php echo $tab ?>">
			<?php $this->load->view('templates/challenge'); ?>
		</div>
		<?php endif ?>
	</div>
</div>