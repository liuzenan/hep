		<div class="row-fluid">
			<div class="span12 well">
				<p><strong>Today's Activity</strong></p>
				<div class="row-fluid">
					<div class="span2">
						<div class="row-fluid">
							<img src="<?php echo $avatar ?>" alt="">
						</div>				
						<p><strong><?php echo $displayName ?></strong></p>
					</div>
					<div class="span10">
						<div class="row-fluid">
								<p><strong><?php echo $exp ?> EXP/1000 EXP Level 1</strong></p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($exp/1000*100); ?>%"></div>
								</div>
						</div>		
						<div class="row-fluid">
							<div class="span3">
								<span class="activityIcon steps"></span>
								<p><small>Took <?php echo $steps ?> steps</small></p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($steps/40000*100); ?>%"></div>
								</div>
							</div>
							<div class="span3">
								<span class="activityIcon distance"></span>
								<p><small>Travelled <?php echo $distance ?> km</small></p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($distance/5*100); ?>%"></div>
								</div>								
							</div>
							<div class="span3">
								<span class="activityIcon floors"></span>
								<p><small>Climbed <?php echo $floors ?> floors</small></p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($floors/20*100); ?>%"></div>
								</div>								
							</div>
							<div class="span3">
								<span class="activityIcon calories"></span>
								<p><small>Burned <?php echo $calories ?> calories</small></p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($calories/2000*100); ?>%"></div>
								</div>
							</div>
						</div>
					</div>				
				</div>
			</div>
		</div>