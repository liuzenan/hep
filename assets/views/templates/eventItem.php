<li class="media" data-event-id="<?php echo $id ?>">
	<a href="<?php echo base_url() . 'challenges/viewevent/' .$id ?>" class="pull-left"><img class="media-object" width="64" src="<?php echo $image ?>"></a> 
	<a href="<?php echo base_url() . 'challenges/viewevent/' .$id ?>">
	<div class="media-body">
		<h4 class="media-heading"><?php echo $title ?></h4>
		<p>
			<span><small><?php echo "Location: " . $location . " &middot; Minimum Level: " . $min_level . " &middot; Date: " . $date; ?></small></span>
		<span class="muted pull-right"><small><?php echo $num_participant . " participants" ?></small></span>
		</p>
	</div>
	</a>
</li>