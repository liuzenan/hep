<div class="row-fluid">
	<div class="span12">
		<?php if (!empty($history)): ?>
			<?php foreach ($history as $key => $value): ?>
				<div class="row-fluid history-day">
					<div class="span2">
						<div class="history-time">
							<?php echo date("d M", strtotime($key)); ?>
						</div>
						<button type="button" class="btn btn-primary refresh-challenge-history" data-uid="<?php echo $value[0]->user_id ?>" data-date="<?php echo $key; ?>" data-loading-text="Refreshing...">Refresh</button>	
					</div>
					<div class="span7">
						<div class="row-fluid">
						<?php foreach ($value as $challenge): ?>
						<div class="span3">
							<div class="history-challenge-item" data-toggle="popover" data-placement="top" data-content="<?php echo $challenge->description ?>" data-original-title="<?php echo $challenge->title ?>" style="background-image:url(<?php echo $challenge->badge_pic?>);">
								<?php if ($challenge->progress>=1): ?>
									<h3 class="complete"><i class="icon-ok icon-2x"></i></h3>
								<?php else: ?>
									<h3 class="incomplete"><i class="icon-remove icon-2x"></i></h3>
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
