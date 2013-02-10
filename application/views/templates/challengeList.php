<div class="row-fluid current-challenges">
	<div class="span12">
		<p class="section-title">Current Challenges</p>
		<div class="row-fluid challenge-stats">
			<table class="table challenge-stats">
				<thead class="stats-head">
					<td>Me</td>
					<td class="middle-col"></td>
					<td>Average</td>
					<td></td>
				</thead>
				<tbody>
					<td class="stats-label"><strong><span><?php echo $me_completed ?></span><br>Completed</strong></td>
					<td class="stats-count middle-col">
				<?php for ($i=0; $i < floor($me_completed); $i++) { ?>
					<i class="icon-star"></i>
				<?php } ?>
				<?php if (floor($me_completed)!=$me_completed): ?>
					<i class="icon-star-half"></i>
				<?php endif ?>										
					</td>
					<td class="stats-label">
						<strong><span><?php echo $avg_completed ?></span><br>Completed</strong>
					</td>
									<td class="stats-count">
				<?php for ($i=0; $i < floor($avg_completed); $i++) { ?>
					<i class="icon-star"></i>
				<?php } ?>
				<?php if (floor($avg_completed)!=$avg_completed): ?>
					<i class="icon-star-half"></i>
				<?php endif ?>											
									</td>
								</tbody>
							</table>
		</div>
		<?php if(!empty($me_challenges)) {?>
			<ul class="media-list current-challenge-list">
			<?php foreach($me_challenges as $challenge){ ?>
			<li class="challengeItem media well">
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
