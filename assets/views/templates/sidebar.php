	<div class="span2">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php if($active=='home') echo "active" ?>"><a href="<?php echo base_url() . 'home'?>">Home</a></li>
			<li class="<?php if($active=='stats') echo "active" ?>"><a href="<?php echo base_url() . 'stats'?>">Statistics</a></li>
			<li class="<?php if($active=='challenges') echo "active" ?>"><a href="<?php echo base_url() . 'challenges'?>">Challenges</a></li>
			<li class="<?php if($active=='leaderboard') echo "active" ?>"><a href="<?php echo base_url() . 'leaderboard'?>">Leaderboard</a></li>
			<li class="<?php if($active=='house') echo "active" ?>"><a href="<?php echo base_url() . 'house'?>"><?php if(!empty($isTutor) && $isTutor==1) echo "Tutors"; else echo "My House"; ?></a></li>
			
			<hr>
			<?php if((!empty($isTutor) && $isTutor==1)  || (!empty($isAdmin) && $isAdmin==1)) {?>
			<li class="<?php if($active=='allhouse') echo "active" ?>"><a href="<?php echo base_url() . 'allhouse'?>">All Houses</a></li>
			<?php } ?>
			<?php if(!empty($isAdmin) && $isAdmin==1) {?>
			<li class="<?php if($active=='manage') echo "active" ?>"><a href="<?php echo base_url() . 'manage'?>">Manage Users</a></li>
			<li class="<?php if($active=='masquerade') echo "active" ?>"><a href="<?php echo base_url() . 'masquerade'?>">Masquerade</a></li>
			<li ><a href="<?php echo base_url() . 'masquerade/unmasquerade'?>">Unmasquerade</a></li>

			<?php } ?>
		</ul>
	</div>