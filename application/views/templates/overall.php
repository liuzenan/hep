<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
				<p class="section-title">Steps</p>
				<div class="clearfix">
				<?php $n=0; ?>
				<?php foreach ($stepsLeader as $row): ?>
				<?php $n++ ?>
					<div class="leaderboard-person clearfix leaderboardTooltip" data-toggle="tooltip" data-placement="bottom" title="base on data collected from <?php echo $row->valid ?> days">
						<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
						<a href="<?php if($row->fb==1) echo "http://www.facebook.com/" . $row->username; else echo "http://www.fitbit.com/user/" . $row->fitbit_id; ?>">
							<div class="leaderboard-profile-pic"><img src="<?php echo $row->avatar ?>" width="75" height="75"/></div>
						</a>
						<div class="leaderboard-content">
							<a href="#"><strong><?php echo $row->firstname . ' ' . $row->lastname ?></strong></a>
							<div>House <?php echo $row->house ?></div>
							<small >
								<?php echo round($row->score, 0) ?> steps daily
							</small>
						</div>
					</div>
				<?php endforeach ?>					
				</div>
		</div>
		<br/>
		<div class="row-fluid">
				<p class="section-title">Sleep</p>
				<div class="clearfix">
				<?php $n=0; ?>
				<?php foreach ($sleepLeader as $row): ?>
				<?php $n++ ?>
					<div class="leaderboard-person clearfix leaderboardTooltip" data-toggle="tooltip" data-placement="bottom" title="base on data collected from <?php echo $row->valid ?> days">
						<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
						<a href="<?php if($row->fb==1) echo "http://www.facebook.com/" . $row->username; else echo "http://www.fitbit.com/user/" . $row->fitbit_id; ?>">
							<div class="leaderboard-profile-pic"><img src="<?php echo $row->avatar ?>" width="75" height="75"/></div>
						</a>
						<div class="leaderboard-content">
							<a href="#"><strong><?php echo $row->firstname . ' ' . $row->lastname ?></strong></a>
							<div>House <?php echo $row->house ?></div>
							<small><?php echo round($row->score / 60, 1) ?> hours daily</small>
						</div>
					</div>
				<?php endforeach ?>					
				</div>
		</div>
	</div>
</div>