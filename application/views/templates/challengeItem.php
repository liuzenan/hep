<li class="challengeItem box">
	<div class="challengeContainer" style="background-image:url(<?php echo $badge_pic;?>)">
		<div class="challengeTitle"><?php echo $title ?></div>
		<h3 class="muted"><?php echo $points ?> points</h3>
		<p><?php echo $description ?></p>
		<div class="challengeControlBtns">
			<button class="btn joinChallengeNow" data-challenge-id="<?php echo $id ?>" data-user-id="<?php echo $user_id ?>" <?php if($disabled_today) echo "disabled" ?>>Join Today</button>
			<button class="btn joinChallengeTomorrow" data-challenge-id="<?php echo $id ?>" data-user-id="<?php echo $user_id ?>" <?php if($disabled_tomorrow) echo "disabled" ?>>Join Tomorrow</button>			
		</div>
	</div>
</li>
