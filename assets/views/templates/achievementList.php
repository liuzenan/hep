<div class="row-fluid">
	<div class="span12 badgelist well">
		<p><strong>My Badges</strong></p>
		<?php if(!empty($badges)) {?>
			<?php foreach($badges as $badge){ ?>
			<img src="<?php echo $badge->badge_pic ?>" alt="">
		<?php }} else {?>
			<p class="mute">You currently don't have any Fitbit achievements yet... :(</p>
		<?php } ?>
	</div>		
</div>

