<div class="row-fluid">
	<div class="span12">
		<p><strong>My Challenges</strong></p>
		<?php if(!empty($me_challenges)) {?>
			<ul class="media-list">
			<?php foreach($me_challenges as $challenge){ ?>
			<li class="challengeItem media">
				<div class="pull-left"><img class="media-object" src="<?php echo $challenge->badge_pic;?>"></div>
				<div class="challengeControls pull-right">
					<h4 class="muted"><?php echo $challenge->points ?> points</h4>
					<div class="view-btn"><a href="#" class="btn btn-primary">View Challenge</a></div>
					<a href="#"><small>Quit challenge</small></a>
					<span>&nbsp; | &nbsp;</span>
					<a href="#"><small>View discussion</small></a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"><?php echo $challenge->title ?></h4>
					<p><?php echo $challenge->description ?></p>
					<small class="muted">You are participating in the challenge</small>
					<div class="progress">
						<div class="bar" style="width:80%"></div>
					</div>
				</div>
			</li>
			<?php } ?>
			</ul>
		<?php } else {?>
			<p class="mute">You currently don't have any challenges yet...</p>
		<?php } ?>
	</div>	
</div>
