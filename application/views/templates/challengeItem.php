<li class="challengeItem box">
	<div class="challengeContainer">
		<div class="challengeTitle"><?php echo $title ?></div>
		<h3><?php echo $points ?> points</h3>
		<?php if ($category!=3): ?>
			<h4><i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($start_time)) ?>-<?php echo date('H:i', strtotime($end_time)) ?></h4>	
		<?php endif ?>
		<p><?php echo $description ?></p>
		<div class="challengeControlBtns">
			<button class="btn joinChallengeNow" data-challenge-id="<?php echo $id ?>" data-user-id="<?php echo $user_id ?>" <?php if($disabled_today) echo "disabled" ?>>Join Today</button>
			<button class="btn joinChallengeTomorrow" data-challenge-id="<?php echo $id ?>" data-user-id="<?php echo $user_id ?>" <?php if($disabled_tomorrow) echo "disabled" ?>>Join Tomorrow</button>			
		</div>
	</div>
</li>
