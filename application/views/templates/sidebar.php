	<div class="span2">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php if($active==2) echo "active" ?>"><a href="<?php echo base_url() . 'achievements'?>">Achievements</a></li>
			<li class="<?php if($active==1) echo "active" ?>"><a href="<?php echo base_url() . 'challenges'?>">Challenges</a></li>
			<li class="<?php if($active==3) echo "active" ?>"><a href="<?php echo base_url() . 'leaderboard'?>">Leaderboard</a></li>
			<li class="<?php if($active==5) echo "active" ?>"><a href="<?php echo base_url() . 'stats'?>">Personal Stats</a></li>
			<li class="<?php if($active==8) echo "active" ?>"><a href="<?php echo base_url() . 'forum'?>">Forums</a></li>
				<ul>
					<li class="<?php if($active==8) echo "active" ?>"><a href="<?php echo base_url() . 'forum/thread/3'?>">Challenges</a></li>
					<li class="<?php if($active==9) echo "active" ?>"><a href="<?php echo base_url() . 'forum/thread/1'?>">General Discussions</a></li>

				</ul>
			<hr>
			<?php if($isLeader==1) {?>
			<li class="<?php if($active==6) echo "active" ?>"><a href="<?php echo base_url() . 'manage/studentList'?>">Student List</a></li>
			<?php } ?>
			<?php if($isAdmin==1) {?>
			<li class="<?php if($active==7) echo "active" ?>"><a href="<?php echo base_url() . 'manage'?>">Manage users</a></li>
			<?php } ?>
		</ul>
	</div>