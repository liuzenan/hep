<li class="challengeItem media well">
	<div class="pull-left"><img class="media-object" src="<?php echo $badge_pic;?>"></div>
	<div class="challengeControls pull-right">
		<h4 class="muted"><?php echo $points ?> points</h4>

			<button class="btn joinChallengeNow" data-challenge-id="<?php echo $id ?>" data-user-id="<?php echo $user_id ?>" <?php if($disabled_today) echo "disabled" ?>>Join Today</button>
			<button class="btn joinChallengeTomorrow" data-challenge-id="<?php echo $id ?>" data-user-id="<?php echo $user_id ?>" <?php if($disabled_tomorrow) echo "disabled" ?>>Join Tomorrow</button>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $title ?></h4>
		<p><?php echo $description ?></p>
	</div>
</li>
