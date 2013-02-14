<div class="row-fluid current-challenges">
	<div class="span12">
		<p class="section-title">Current Challenges <a href="javascript:void(0);" class="expandbtn pull-right"><i class="icon-chevron-down icon-large"></i></a></p>
		<div class="row-fluid challenge-stats">
			<table class="table challenge-stats">
				<tbody>
					<td class="stats-label">	
						<strong>Total Completed:</strong>							
					</td>
					<td class="stats-label middle-col">
						<strong><span><?php echo $me_completed ?></span></strong>&nbsp;<i class="icon-trophy icon-2x"></i>
					</td>
					<td class="stats-label">
						<strong>Cohort Average:</strong>
					</td>
					<td class="stats-label">
						<strong><span><?php echo $avg_completed ?>&nbsp;</span></strong><i class="icon-group icon-2x"></i>
					</td>
					
				</tbody>
			</table>
		</div>
		<div class="current-challenge-list row-fluid expandable">
			<div class="notstarted well">
				<h3>Hold your horses...</h3>
				<p>The game has not started yet... &#9786;</p>
				<strong><h5>Count down: <span id="count-down-days"></span> <span id="count-down-hours"></span> <span id="count-down-minutes"></span><span id="count-down-seconds"></span></h5></strong>
			</div>
		</div>
	</div>	
</div>
