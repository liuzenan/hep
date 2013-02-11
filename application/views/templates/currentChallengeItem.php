<li class="challengeItem media well">
	<div class="pull-left"><img class="media-object" src="<?php echo $badge_pic;?>"></div>
	<div class="challengeControls pull-right">
	<h4 class="muted"><?php echo $points ?> points</h4>
	<div class="quitchallengebtn">
		<button class="btn quitChallenge" data-cp-id="<?php echo $id ?>">Quit challenge</button>
	</div>
	<br>
		<a href="#"><small>View discussion</small></a>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $title ?></h4>
		<p><?php echo $description ?></p>
		<small class="muted">You are participating in the challenge</small>
		<div class="progress">
			<div class="bar" style="width:<?php echo floor($progress*100) ?>%"></div>
		</div>
	</div>
</li>
