<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span4">
				<p class="section-title">Overall</p>
				<?php $n=0; ?>
				<?php foreach ($leader as $row): ?>
				<?php $n++ ?>
					<div class="leaderboard-person clearfix">
						<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
						<div class="leaderboard-profile-pic"><img src="<?php echo $row->avatar ?>" width="75" height="75"/></div>
						<div class="leaderboard-content">
							<a href="#"><strong><?php echo $row->firstname . ' ' . $row->lastname ?></strong></a>
							<p>House <?php echo $row->house ?></p>
							<small><?php echo $row->score ?> Challenges</small>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="span4">
				<p class="section-title">Female</p>
				<?php $n=0; ?>
				<?php foreach ($female as $row): ?>
				<?php $n++ ?>
					<div class="leaderboard-person clearfix">
						<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
						<div class="leaderboard-profile-pic"><img src="<?php echo $row->avatar ?>" width="75" height="75"/></div>
						<div class="leaderboard-content">
							<a href="#"><strong><?php echo $row->firstname . ' ' . $row->lastname ?></strong></a>
							<p>House <?php echo $row->house ?></p>
							<small><?php echo $row->score ?> Challenges</small>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="span4">
				<p class="section-title">Male</p>
				<?php $n=0; ?>
				<?php foreach ($male as $row): ?>
				<?php $n++ ?>
					<div class="leaderboard-person clearfix">
						<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
						<div class="leaderboard-profile-pic"><img src="<?php echo $row->avatar ?>" width="75" height="75"/></div>
						<div class="leaderboard-content">
							<a href="#"><strong><?php echo $row->firstname . ' ' . $row->lastname ?></strong></a>
							<p>House <?php echo $row->house ?></p>
							<small><?php echo $row->score ?> Challenges</small>
						</div>
					</div>
				<?php endforeach ?>
			</div>
		</div>
	</div>
</div>