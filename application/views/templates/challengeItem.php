<li class="challengeItem box">
	<div class="challengeContainer">
		<div class="challengeTitle"><?php echo $title ?></div>
		<h3><?php echo $points ?> points</h3>
		<?php if ($category!=3): ?>
			<h4><i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($start_time)) ?>-<?php echo date('H:i', strtotime($end_time)) ?></h4>	
		<?php endif ?>
		<p><?php echo $description ?></p>
		<h4>Limit: <?php echo $quota ?> times</h4>
	</div>
</li>
