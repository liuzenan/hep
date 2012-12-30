	<div class="span2">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php if($active==2) echo "active" ?>"><a href="<?php echo base_url() . 'achievements'?>">My Achievements</a></li>
			<li class="<?php if($active==1) echo "active" ?>"><a href="<?php echo base_url() . 'challenges'?>">Challenges</a></li>
			<li class="<?php if($active==3) echo "active" ?>"><a href="<?php echo base_url() . 'leaderboard'?>">Leaderboard</a></li>
			<li class="<?php if($active==5) echo "active" ?>"><a href="<?php echo base_url() . 'stats'?>">Personal Stats</a></li>
			<li class="<?php if($active==4) echo "active" ?>"><a href="<?php echo base_url() . 'benchmark'?>">Peer Benchmark</a></li>
		</ul>
	</div>