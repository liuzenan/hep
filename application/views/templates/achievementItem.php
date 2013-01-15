<li class="achievementItem media well <?php if($times>0) echo "active" ?>">
	<a href="#" class="pull-left"><img class="media-object" src="<?php if($times>0) echo $badge_pic; else echo base_url() . "assets/img/lock.jpg"; ?>"></a>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $name ?></h4>
		<p><?php echo $description ?></p>
	</div>
</li>