<div class="row-fluid">
	<div class="span12 badgelist">
		<p class="section-title">My Badges</p>
		<div class="clearfix">
		<?php if(!empty($me_badges)) {?>
			<?php foreach($me_badges as $badge){ ?>
			<a href="#" class="my-badges" data-toggle="tooltip" title="<?php echo $badge->description ?>"><img src="<?php echo $badge->badge_pic ?>" alt=""></a>
		<?php }} else {?>
			<p class="mute">You currently don't have any HEP achievements yet... :(</p>
		<?php } ?>			
		</div>
	</div>		
</div>

