<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
			<div class="span4">
				<?php $n=0; ?>
				<?php foreach ($house as $row): ?>
				<?php $n++ ?>
				<div class="leaderboard-person clearfix">
					<div class="leaderboard-rank"><span class="badge badge-success"><?php echo $n ?></span></div>
					<div class="leaderboard-content">
						<a href="#"><strong><?php echo $row->house_name ?></strong></a>
						<p>Total Challenges Completed: <?php echo $row->score ?></p>
						<p>Total Member: <?php echo $row->user_num ?></p>
						<small>Average<?php echo $row->score/$row->user_num ?> Challenges</small>
					</div>
					<div class="leaderboard-profile-pic">
						<?php $avatars = explode(",", $row->avatars); foreach ($avatars as $avatar): ?>
						<img src="<?php echo $avatar ?>" width="75" height="75"/>
					<?php endforeach ?>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
</div>
</div>