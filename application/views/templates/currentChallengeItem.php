				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle"><?php echo $title ?></div>
						<h4><?php echo $points ?> points</h4>	
						<p><?php echo $description ?></p>
						<strong>Start time: <?php echo date('H:i',$start_time) ?>&nbsp;
						End time: <?php echo date('H:i',$end_time) ?></strong>
						<div class="progress progress-warning progress-striped">
							<div class="bar" style="width:<?php echo floor($progress*100) ?>%"></div>
						</div>			
						<div class="quitchallengebtn">
							<button class="btn quitChallenge" data-cp-id="<?php echo $id ?>">Quit challenge</button>
						</div>									
					</div>
				</div>
