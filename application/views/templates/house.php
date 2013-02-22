<div class="row-fluid">
	<div class="span12">
				<?php $n=0; ?>
				<?php foreach ($house as $row): ?>
				<?php $n++ ?>
				<div class="row-fluid leaderboard-house">
					<div class="span1 leaderboard-house-rank"><?php echo $n ?></div>
					<div class="span3 leaderboard-house-content">
						<strong>House <?php echo $row->house_name ?></strong><br>
						<strong class="muted"><?php echo $row->score ?> points</strong><br>
						<small>Total Member: <?php echo $row->user_num ?></small><br>
						<small>Average Points: <?php echo $row->average ?> </small>
					</div>
					<div class="span8 leaderboard-house-profile-pic">
					<?php $fitbit_ids = explode(",", $row->fitbit_ids); $usernames = explode(",", $row->usernames); $fbs = explode(",", $row->fbs); $avatars = explode(",", $row->avatars);  $names=explode(",", $row->names); for($i=count($avatars)-1; $i>=0; $i--): ?>
						<a href="<?php if($fbs[$i]==1) echo "http://www.facebook.com/" . $usernames[$i]; else echo "http://www.fitbit.com/user/" . $fitbit_ids[$i]; ?>">
						<img class="challengeTitleTooltip" src="<?php echo $avatars[$i] ?>" width="10%" data-original-title="<?php echo $names[$i] ?>"/>
					<?php endfor ?>
					</div>
				</div>
		<?php endforeach ?>
	</div>
</div>