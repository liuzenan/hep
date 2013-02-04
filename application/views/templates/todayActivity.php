		<div class="row-fluid">
			<div class="span12 well today-activity">
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
								<!--<p><strong><?php echo $exp ?> EXP/<?php echo $nextlevelpoints ?> EXP Level <?php echo $level ?></strong> Average point: <?php echo ceil($avg_points->avg_points) ?></p>-->
								<!--
								<div class="progress">
									<?php if($bar_type==0){ ?>
									<div class="bar bar-danger" style="margin-left:<?php echo ceil(($exp-$currentLevelPoints)/($nextlevelpoints-$currentLevelPoints)*100) ?>%;width: <?php echo 100 - ceil(($exp-$currentLevelPoints)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>

									<?php }else if($bar_type==1){ ?>
									<?php if(ceil($avg_points->avg_points)>intval($exp)){ ?>
									<div class="bar" style="width: <?php echo ceil(($exp-$currentLevelPoints)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>
									<div class="bar bar-danger" style="width: <?php echo ceil(($avg_points->avg_points-$exp)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>
									<?php }else if(ceil($avg_points->avg_points)<intval($exp)){ ?>
									<div class="bar" style="width: <?php echo ceil(($avg_points->avg_points-$currentLevelPoints)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>
									<div class="bar bar-success" style="width: <?php echo ceil(($exp-$avg_points->avg_points)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>
									<?php }else{ ?>
									<div class="bar" style="width: <?php echo ceil(($exp-$currentLevelPoints)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>
									<?php } ?>
									<?php }else{ ?>
									<div class="bar bar-success" style="width: <?php echo ceil(($exp-$currentLevelPoints)/($nextlevelpoints-$currentLevelPoints)*100); ?>%;"></div>
									<?php } ?>
								</div>
								-->
						</div>		
						<div class="row-fluid">
							<div class="span3">
								<span class="activityIcon steps"></span>
								<p class="smalltext"><small>Took <?php echo $steps ?> steps</small></p>
								<div class="progress progress-danger">
									<div class="bar" style="width: <?php echo ceil($steps/40000*100); ?>%"></div>
								</div>
							</div>
							<div class="span3">
								<span class="activityIcon distance"></span>
								<p class="smalltext"><small>Travelled <?php echo $distance ?> km</small></p>
								<div class="progress progress-info">
									<div class="bar" style="width: <?php echo ceil($distance/5*100); ?>%"></div>
								</div>								
							</div>
							<div class="span3">
								<span class="activityIcon floors"></span>
								<p class="smalltext"><small>Climbed <?php echo $floors ?> floors</small></p>
								<div class="progress progress-success">
									<div class="bar" style="width: <?php echo ceil($floors/20*100); ?>%"></div>
								</div>								
							</div>
							<div class="span3">
								<span class="activityIcon calories"></span>
								<p class="smalltext"><small>Burned <?php echo $calories ?> calories</small></p>
								<div class="progress progress-warning">
									<div class="bar" style="width: <?php echo ceil($calories/2000*100); ?>%"></div>
								</div>
							</div>
						</div>
					</div>				
				</div>
			</div>
		</div>