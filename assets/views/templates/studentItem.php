<li class="studentItem media well">
	<a href="#" class="pull-left"><img class="media-object" src="<?php echo $profile_pic ?>"></a>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $first_name . ' ' . $last_name ?></h4>
		<p><?php echo $email ?></p>
		<?php if ($admin==1): ?>
			<span class="label"><?php echo "Admin" ?></span>
		<?php endif ?>
		<?php if ($phantom==1): ?>
			<span class="label"><?php echo "Phantom User" ?></span>
		<?php endif ?>
		<?php if ($staff==1): ?>
			<span class="label"><?php echo "Tutor" ?></span>
		<?php endif ?>
		<?php if ($staff==0 && $house_id>0): ?>
			<span class="label"><?php echo "Student" ?></span>
			<span class="label"><?php echo "House: " . $house_id ?></span>
		<?php endif ?>
		<?php if ($leader==1): ?>
			<span class="label"><?php echo "House Leader" ?></span>
		<?php endif ?>	
	</div>
</li>