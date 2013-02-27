<li class="challengeItem box">
	<div class="challengeContainer">
		<div class="challengeTitle"><?php echo $title ?></div>
		<h5><?php echo $points ?> points &middot; Limit: <?php if($quota<9999) echo $quota . " times"; else echo "No limit" ?></h5>
		<?php if ($category!=3): ?>
			<h4><i class="icon-time icon-large"></i>&nbsp;<?php echo date('H:i',strtotime($start_time)) ?>-<?php echo date('H:i', strtotime($end_time)) ?></h4>	
		<?php endif ?>
		<p><?php echo $description ?></p>
	</div>
</li>
