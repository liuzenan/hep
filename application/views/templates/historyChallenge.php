<div class="row-fluid">
	<div class="span12">
		<?php if (!empty($history)): ?>
			<?php foreach ($history as $key => $value): ?>
				<div class="row-fluid history-day">
					<div class="span2">
						<div class="history-time">
							<?php echo date("d M", strtotime($key)); ?>
						</div>
					</div>
					<div class="span7">
						<div class="row-fluid">
						<?php foreach ($value as $challenge): ?>
						<div class="span3">
							<div class="history-challenge-item" data-toggle="popover" data-placement="top" data-content="<?php echo $challenge->description ?>" data-original-title="<?php echo $challenge->title ?>" style="background-image:url(<?php echo $challenge->badge_pic?>);">
								<?php if ($challenge->progress<1): ?>
									<div class="progress progress-warning progress-striped">
										<div class="bar" style="width:<?php echo floor($challenge->progress*100) ?>%"></div>
									</div>		
								<?php else: ?>
									<h5>Completed</h5>
								<?php endif ?>
							</div>
						</div>
						<?php endforeach ?>										
							
						</div>
					</div>
				</div>
			<?php endforeach ?>
		<?php endif ?>
	</div>
</div>
