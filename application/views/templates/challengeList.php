<div class="row-fluid">
	<div class="span12 challengelist well">
		<p><strong>My Challenges</strong></p>
		<?php if($challenges) {?>
			<ul class="media-list">
			<?php foreach($challenges as $challenge){ ?>
			<li class="media">
				<a href="<?php echo base_url() . 'challenges/viewevent/' .$challenge->id ?>" class="pull-left"><img class="media-object" width="64" src="<?php echo $challenge->badge_pic ?>"></a> 
			<!--	<a href="<?php echo base_url() . 'challenges/viewevent/' .$challenge->id ?>" class="btn pull-right">View</a> -->
				<div class="media-body">
					<p class="media-heading"><?php echo $challenge->title ?></p>
					<p><small><strong>Start Time: </strong><?php echo $challenge->join_time ?></small><small><strong>Finish Time: </strong><?php echo $challenge->complete_time ?></small></p>
				</div>
			</li>
			
			<?php } ?>
			</ul>
		<?php } else {?>
			<p class="mute">You currently don't have any challenges yet...</p>
		<?php } ?>
	</div>	
</div>
