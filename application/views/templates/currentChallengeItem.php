				<div class="challengeItem box">
					<div class="challengeContainer">
						<div class="challengeTitle"><?php echo $title ?></div>
						<h4><?php echo $points ?> points &middot; <i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($start_time)) ?>-<?php echo date('H:i', strtotime($end_time)) ?></h4>	
						<p><?php echo $description ?></p>
						<div class="progress progress-warning progress-striped">
							<div class="bar" style="width:<?php echo floor($progress*100) ?>%"></div>
						</div>			
						<div class="quitchallengebtn">
							<button class="btn quitChallenge" data-cp-id="<?php echo $id ?>">Quit Challenge</button>
						</div>									
					</div>
				</div>
