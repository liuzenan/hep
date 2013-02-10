	<div class="span2">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php if($active=='challenges') echo "active" ?>"><a href="<?php echo base_url() . 'challenges'?>">Challenges</a></li>
			<li class="<?php if($active=='leaderboard') echo "active" ?>"><a href="<?php echo base_url() . 'leaderboard'?>">Leaderboard</a></li>
			<li class="<?php if($active=='stats') echo "active" ?>"><a href="<?php echo base_url() . 'stats'?>">Personal Stats</a></li>
			<li class="<?php if($active=='house') echo "active" ?>"><a href="<?php echo base_url() . 'house'?>">My House</a></li>
			<li class="<?php if($active=='challenge_forum') echo "active" ?>"><a href="<?php echo base_url() . 'forum/challenge'?>">Challenge Forum</a></li>
			<li class="<?php if($active=='general_forum') echo "active" ?>"><a href="<?php echo base_url() . 'forum/general'?>">General Forum</a></li>
			<hr>
			<?php if($isLeader==1) {?>
			<li class="<?php if($active=='studentList') echo "active" ?>"><a href="<?php echo base_url() . 'manage/studentList'?>">Student List</a></li>
			<?php } ?>
			<?php if($isAdmin==1) {?>
			<li class="<?php if($active=='tutor_forum') echo "active" ?>"><a href="<?php echo base_url() . 'forum/tutor'?>">Tutor Forum</a></li>
			<li class="<?php if($active=='manage') echo "active" ?>"><a href="<?php echo base_url() . 'manage'?>">Manage Users</a></li>
			<?php } ?>
		</ul>
	</div>