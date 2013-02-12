<div class="row-fluid current-challenges">
	<div class="span12">
		<p class="section-title">Current Challenges</p>
		<div class="row-fluid challenge-stats">
			<table class="table challenge-stats">
				<tbody>
					<td class="stats-label"><strong><span><?php echo $me_completed ?></span><br>Completed by me</strong></td>
					<td class="stats-count middle-col">
				<?php for ($i=0; $i < floor($me_completed); $i++) { ?>
					<i class="icon-star"></i>
				<?php } ?>
				<?php if (floor($me_completed)!=$me_completed): ?>
					<i class="icon-star-half"></i>
				<?php endif ?>										
					</td>
					<td class="stats-label">
						<strong><span><?php echo $avg_completed ?></span><br>Completed by average</strong>
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
			<div class="current-challenge-list row-fluid">
				<div class="span4 yesterday">
					<div class="challenge-title">Yesterday</div>
			<?php foreach($me_challenges_yesterday as $challenge){ ?>
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle"><?php echo $challenge->title ?></div>
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
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle"><?php echo $challenge->title ?></div>
						<div class="progress">
							<div class="bar" style="width:<?php echo floor($challenge->progress*100) ?>%"></div>
						</div>						
					</div>
				</div>
			<?php } ?>
				</div>
				<div class="span4 tomorrow">
					<div class="challenge-title">Tomorrow</div>
			<?php foreach($me_challenges_tomorrow as $challenge){ ?>
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle"><?php echo $challenge->title ?></div>
						<h4><?php echo $challenge->points ?> points</h4>						
					</div>
				</div>
			<?php } ?>
				</div>
			</div>
		<?php } else {?>
			<p class="mute">You currently don't have any challenges yet...</p>
		<?php } ?>
	</div>	
</div>
