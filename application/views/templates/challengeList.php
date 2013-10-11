<div class="row-fluid current-challenges">
	<div class="span12">
		<p class="section-title">Current Challenges <a href="javascript:void(0);" class="expandbtn pull-right"><i class="icon-chevron-down icon-large"></i></a><a href="javascript:void(0);" class="refresh-challenge-button pull-right enabled"><i class="icon-refresh icon-large"></i></a></p>
		<div class="row-fluid challenge-stats">
			<table class="table challenge-stats">
				<tbody>
					<td class="stats-label middle-col">	
						<strong>Challenge Completed:</strong><br>	
						<strong><span><?php echo $me_completed ?></span></strong>&nbsp;<i class="icon-trophy"></i>						
					</td>
					<td class="stats-label middle-col">
						<strong>Cohort Average:</strong><br>
						<strong><span><?php echo $avg_completed ?>&nbsp;</span></strong><i class="icon-group"></i>
					</td>
					<td class="stats-label middle-col">	
						<strong>Points Contributed:</strong><br>
						<strong><span><?php echo $my_points ?>&nbsp;</span></strong><i class="icon-dashboard"></i>					
					</td>
					<td class="stats-label">	
						<strong>Corhort Average:</strong><br>
						<strong><span><?php echo $cohor_points ?>&nbsp;</span></strong><i class="icon-group"></i>						
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
						<h4>No Challenge Selected</h4>
					</div>
				</div>				
						<?php endif ?>
					</div>
				<?php } ?>
			</div>
			<div class="span4 today">
				
					<div class="challenge-title">Today (<?php echo date("d M");?>)</div>

				<?php for ($i=0; $i < 4; $i++) { ?>
					<div class="challenge-type-<?php echo $i ?>">
			<?php if ((intval(date("N"))==6 || intval(date("N"))==7) && $i==0): ?>
				<div class="challengeItem box empty">
					<div class="challengeContainer">
						<h4>Rest on Weekends &#9786;</h4>
					</div>
				</div>
			<?php else: ?>
			<?php if (!empty($me_challenges) && !empty($me_challenges[$i])): ?>
			<a href="#challengeModal" role="button" data-challenge-id="<?php echo $me_challenges[$i]->id ?>" data-toggle="modal">
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $me_challenges[$i]->description ?>"><?php echo $me_challenges[$i]->title ?></div>
						<h4><?php echo $me_challenges[$i]->points ?> points &middot; <i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($me_challenges[$i]->start_time)) ?>-<?php echo date('H:i', strtotime($me_challenges[$i]->end_time)) ?></h4>	
						<?php if ($me_challenges[$i]->progress==1): ?>
							<h4>Completed</h4>
						<?php else: ?>
							<div class="progress progress-warning progress-striped">
								<div class="bar" style="width:<?php echo floor($me_challenges[$i]->progress*100) ?>%"></div>
							</div>				
						<?php endif ?>
					</div>
				</div>				
			</a>					
			<?php else: ?>
			<a href="#challengeModal" class="challenge-empty" data-challenge-id="-1" role="button" data-toggle="modal">
				<div class="challengeItem box empty">
					<div class="challengeContainer">
						<h4>Click to Select</h4>
					</div>
				</div>
			</a>		
			<?php endif ?>
			<?php endif ?>			

					</div>
				<?php } ?>
			</div>
			<div class="span4 tomorrow">
			
					<div class="challenge-title">Tomorrow</div>
      			<?php for ($i=0; $i < 4; $i++) { ?>
					<div class="challenge-type-<?php echo $i ?>">
			<?php if ((intval(date("N"))==5 || intval(date("N"))==6) && $i==0): ?>
				<div class="challengeItem box empty">
					<div class="challengeContainer">
						<h4>Rest on Weekends &#9786;</h4>
					</div>
				</div>
			<?php else: ?>

						<?php if (!empty($me_challenges_tomorrow) && !empty($me_challenges_tomorrow[$i])): ?>
			<a href="#challengeModal" role="button" data-challenge-id="<?php echo $me_challenges_tomorrow[$i]->id ?>" data-toggle="modal">
				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle challengeTitleTooltip"  data-original-title="<?php echo $me_challenges_tomorrow[$i]->description ?>"><?php echo $me_challenges_tomorrow[$i]->title ?></div>
						<h4><?php echo $me_challenges_tomorrow[$i]->points ?> points &middot; <i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($me_challenges_tomorrow[$i]->start_time)) ?>-<?php echo date('H:i', strtotime($me_challenges_tomorrow[$i]->end_time)) ?></h4>							
					</div>
				</div>				
			</a>			
						<?php else: ?>
			<a href="#challengeModal" class="challenge-empty" data-challenge-id="-1" role="button" data-toggle="modal">
				<div class="challengeItem box empty">
					<div class="challengeContainer">
						<h4>Click to Select</h4>
					</div>
				</div>
			</a>
						<?php endif ?>
						<?php endif ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>	
</div>
