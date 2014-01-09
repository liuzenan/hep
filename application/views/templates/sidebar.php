	<div class="span2">
		<ul class="nav nav-pills nav-stacked">
			<li class="<?php if($active=='home') echo "active" ?>"><a href="<?php echo base_url() . 'home'?>">Home</a></li>
			<li class="<?php if($active=='stats') echo "active" ?>"><a href="<?php echo base_url() . 'stats'?>">Statistics</a></li>
			<li class="<?php if($active=='badges') echo "active" ?>"><a href="<?php echo base_url() . 'badges'?>">All Badges</a></li>
			<li class="<?php if($active=='leaderboard') echo "active" ?>"><a href="<?php echo base_url() . 'leaderboard'?>">Leaderboard</a></li>
			<li class="<?php if($active=='house') echo "active" ?>"><a href="<?php echo base_url() . 'house'?>"><?php if(!empty($isTutor) && $isTutor==1) echo "Tutors"; else echo "My House"; ?></a></li>
			<li class="<?php if($active=='general_forum') echo "active" ?>"><a href="<?php echo base_url() . 'forum/general'?>">General Forum</a></li>
			<li class="<?php if($active=='rules') echo "active" ?>"><a href="<?php echo base_url() . 'staticpages/rules'?>">Game Rules</a></li>
			<li class="<?php if($active=='faq') echo "active" ?>"><a href="<?php echo base_url() . 'staticpages/faq'?>">FAQ</a></li>
			<li class="<?php if($active=='survey') echo "active" ?>"><a href="<?php echo base_url() . 'survey'?>">Survey</a></li>
			<hr>
			<?php if((!empty($isTutor) && $isTutor==1)  || (!empty($isAdmin) && $isAdmin==1)) {?>
			<li class="<?php if($active=='allhouse') echo "active" ?>"><a href="<?php echo base_url() . 'allhouse'?>">All Houses</a></li>
			<?php } ?>
			<?php if(!empty($isAdmin) && $isAdmin==1) {?>
			<li class="<?php if($active=='manage') echo "active" ?>"><a href="<?php echo base_url() . 'manage'?>">Manage Users</a></li>
			<li class="<?php if($active=='masquerade') echo "active" ?>"><a href="<?php echo base_url() . 'masquerade'?>">Masquerade</a></li>
			<li ><a href="<?php echo base_url() . 'masquerade/unmasquerade'?>">Unmasquerade</a></li>
			<li class="<?php if($active=='mail') echo "active" ?>"><a href="<?php echo base_url() . 'editmail'?>">Schedule Mail</a></li>
			<li class="<?php if($active=='sendmail') echo "active" ?>"><a href="<?php echo base_url() . 'sendmail'?>">Send Email</a></li>
			<li class="<?php if($active=='surveyresult') echo "active" ?>"><a href="<?php echo base_url() . 'surveyresult'?>">Survey Result</a></li>
			<?php } ?>
		</ul>
	</div>