<div class="row-fluid current-challenges">
	<div class="span12">
		<p class="section-title">Current Challenges</p>
		<div class="row-fluid challenge-stats">
			<table class="table challenge-stats">
				<tbody>
					<td class="stats-label">	
						<strong>Total Completed:</strong>							
					</td>
					<td class="stats-label middle-col">
						<strong><span><?php echo $me_completed ?></span></strong>&nbsp;<i class="icon-trophy icon-2x"></i>
					</td>
					<td class="stats-label">
						<strong>Average:</strong>
					</td>
					<td class="stats-label">
						<strong><span><?php echo $avg_completed ?>&nbsp;</span></strong><i class="icon-group icon-2x"></i>
					</td>
					
				</tbody>
			</table>
		</div>
		<?php if( !empty($me_challenges) || !empty($me_challenges_yesterday) || !empty($me_challenges_tomorrow) ) {?>
			<div class="current-challenge-list row-fluid">
				<div class="span4 yesterday">
					<div class="challenge-title">Yesterday</div>
			<?php foreach($me_challenges_yesterday as $challenge){ ?>
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $challenge->description ?>"><?php echo $challenge->title ?></div>
						<h4>
							<?php if (strcmp($challenge->complete_time, "0000-00-00 00:00:00")): ?>
								Completed
							<?php else: ?>
								Not Completed
							<?php endif ?>
						</h4>						
					</div>
				</div>
			<?php } ?>
				</div>
				<div class="span4 today">
					<div class="challenge-title">Today</div>
			<?php foreach($me_challenges as $challenge){ ?>
			<a href="#challengeToday" role="button" data-toggle="modal">
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $challenge->description ?>"><?php echo $challenge->title ?></div>
						<div class="progress progress-warning progress-striped">
							<div class="bar" style="width:<?php echo floor($challenge->progress*100) ?>%"></div>
						</div>						
					</div>
				</div>				
			</a>
			<?php } ?>
				</div>
				<div class="span4 tomorrow">
					<div class="challenge-title">Tomorrow</div>
			<?php foreach($me_challenges_tomorrow as $challenge){ ?>
			<a href="#challengeTmr" role="button" data-toggle="modal">
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $challenge->description ?>"><?php echo $challenge->title ?></div>
						<h4><?php echo $challenge->points ?> points</h4>						
					</div>
				</div>				
			</a>
			<?php } ?>
				</div>
			</div>
		<?php } else {?>
			<p class="mute">You currently don't have any challenges yet...</p>
		<?php } ?>
	</div>	
</div>
