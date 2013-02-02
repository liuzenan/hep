<div class="row-fluid">
	<div class="span12 challengelist well">
		<p><strong>My Challenges</strong></p>
		<?php if($challenges) {?>
			<ul class="media-list">
			<?php foreach($challenges as $challenge){ ?>
			<li class="media">
				<a href="<?php echo base_url() . 'challenges/viewevent/' .$challenge->id ?>" class="pull-left"><img class="media-object" width="64" src="<?php echo $challenge->event_image ?>"></a> 
				<a href="<?php echo base_url() . 'challenges/viewevent/' .$challenge->id ?>" class="btn pull-right">View</a>
				<div class="media-body">
					<p class="media-heading"><?php echo $challenge->title ?></p>
					<p><small><strong>Date: </strong><?php echo $challenge->date ?></small></p>
				</div>
			</li>
			
			<?php } ?>
			</ul>
		<?php } else {?>
			<p class="mute">You currently don't have any challenges yet...</p>
		<?php } ?>
	</div>	
</div>
