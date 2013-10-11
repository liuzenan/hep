<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<p class="section-title">Tutors</p>
			<div class="clearfix">
				<?php $n=0; ?>
				<?php foreach ($staff as $row): ?>
				<?php $n++ ?>
					<div class="leaderboard-person clearfix">
						<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
						<a href="<?php if($row->fb==1) echo "http://www.facebook.com/" . $row->username; else echo "http://www.fitbit.com/user/" . $row->fitbit_id; ?>">
							<div class="leaderboard-profile-pic"><img src="<?php echo $row->avatar ?>" width="75" height="75"/></div>
						</a>
						<div class="leaderboard-content">
							<a href="#"><strong><?php echo $row->firstname . ' ' . $row->lastname ?></strong></a>
							<br>
							<small><?php echo $row->score ?> Challenges</small>
						</div>
					</div>
				<?php endforeach ?>				
			</div>
		</div>
	</div>
</div>