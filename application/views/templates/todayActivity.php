		<div class="row-fluid">
			<div class="span12 well">
				<p><strong>Today's Activity</strong></p>
				<div class="row-fluid">
					<div class="span2">
						<img src="<?php echo $avatar ?>" alt="">
					</div>
					<div class="span10">
						<p><strong><?php echo $displayName ?></strong></p>
						<span class="label">Beginner</span>
						<div class="row-fluid">
							<div class="span5">
								<p><?php echo $steps ?> steps taken today</p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($steps/$stepsGoal*100); ?>%"></div>
								</div>
								<p><?php echo $floors ?> floors climbed today</p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($floors/$floorsGoal*100); ?>%"></div>
								</div>
							</div>
							<div class="span5">
								<p><?php echo $distance ?> km traveled today</p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($distance/$distanceGoal*100); ?>%"></div>
								</div>
								<p><?php echo $calories ?> calories burned today</p>
								<div class="progress progress-info progress-striped">
									<div class="bar" style="width: <?php echo floor($calories/$caloriesGoal*100); ?>%"></div>
								</div>
							</div>
							<div class="span2"></div>
						</div>
					</div>				
				</div>
			</div>
		</div>