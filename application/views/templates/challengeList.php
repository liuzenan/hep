<div class="row-fluid current-challenges">
	<div class="span12">
		<p class="section-title">Current Challenges <a href="javascript:void(0);" class="expandbtn pull-right"><i class="icon-chevron-down icon-large"></i></a></p>
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
						<strong>Cohort Average:</strong>
					</td>
					<td class="stats-label">
						<strong><span><?php echo $avg_completed ?>&nbsp;</span></strong><i class="icon-group icon-2x"></i>
					</td>
					
				</tbody>
			</table>
		</div>
		<div class="current-challenge-list row-fluid expandable">
			<div class="span4 yesterday">
				<div class="challenge-title">Yesterday</div>
				<?php for ($i=0; $i < 4; $i++) { ?>
					<div class="challenge-type-<?php echo $i ?>">
						<?php if (!empty($me_challenges_yesterday) && !empty($me_challenges_yesterday[$i])): ?>
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $me_challenges_yesterday[$i]->description ?>"><?php echo $me_challenges_yesterday[$i]->title ?></div>
						<h4>
							<?php if (strcmp($me_challenges_yesterday[$i]->complete_time, "0000-00-00 00:00:00")): ?>
								Completed
							<?php else: ?>
								Not Completed
							<?php endif ?>
						</h4>						
					</div>
				</div>							
						<?php else: ?>
				<div class="challengeItem box empty">
					<div class="challengeContainer">
					</div>
				</div>				
						<?php endif ?>
					</div>
				<?php } ?>
			</div>
			<div class="span4 today">
				<div class="challenge-title">Today</div>
				<?php for ($i=0; $i < 4; $i++) { ?>
					<div class="challenge-type-<?php echo $i ?>">
						<?php if (!empty($me_challenges) && !empty($me_challenges[$i])): ?>
			<a href="#challengeToday" role="button" data-challenge-id="<?php echo $me_challenges[$i]->id ?>" data-toggle="modal">
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $me_challenges[$i]->description ?>"><?php echo $me_challenges[$i]->title ?></div>
						<h4><?php echo $me_challenges[$i]->points ?> points &middot; <i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($me_challenges[$i]->start_time)) ?>-<?php echo date('H:i', strtotime($me_challenges[$i]->end_time)) ?></h4>	
						<div class="progress progress-warning progress-striped">
							<div class="bar" style="width:<?php echo floor($me_challenges[$i]->progress*100) ?>%"></div>
						</div>						
					</div>
				</div>				
			</a>					
						<?php else: ?>
			<a href="#challengeToday" class="challenge-empty" data-challenge-id="-1" role="button" data-toggle="modal">
				<div class="challengeItem box empty">
					<div class="challengeContainer">
					</div>
				</div>
			</a>		
						<?php endif ?>
					</div>
				<?php } ?>
			</div>
			<div class="span4 tomorrow">
				<div class="challenge-title">Today</div>
				<?php for ($i=0; $i < 4; $i++) { ?>
					<div class="challenge-type-<?php echo $i ?>">
						<?php if (!empty($me_challenges_tomorrow) && !empty($me_challenges_tomorrow[$i])): ?>
			<a href="#challengeTmr" role="button" data-challenge-id="<?php echo $me_challenges_tomorrow[$i]->id ?>" data-toggle="modal">
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $me_challenges_tomorrow[$i]->description ?>"><?php echo $me_challenges_tomorrow[$i]->title ?></div>
						<h4><?php echo $me_challenges_tomorrow[$i]->points ?> points &middot; <i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($me_challenges_tomorrow[$i]->start_time)) ?>-<?php echo date('H:i', strtotime($me_challenges_tomorrow[$i]->end_time)) ?></h4>							
					</div>
				</div>				
			</a>			
						<?php else: ?>
			<a href="#challengeTmr" class="challenge-empty" data-challenge-id="-1" role="button" data-toggle="modal">
				<div class="challengeItem box empty">
					<div class="challengeContainer">
					</div>
				</div>
			</a>
						<?php endif ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>	
</div>
