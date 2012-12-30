<li class="media" data-event-id="<?php echo $id ?>">
	<a href="#" class="pull-left"><img class="media-object" src="http://placehold.it/64x64"></a>
	<div class="media-body">
		<button class="btn pull-right joinbtn">Join</button>
		<h4 class="media-heading"><?php echo $title ?></h4>
		<p><?php echo $description ?></p>
		<p>
			<span><small><?php echo "Location: " . $location . " &middot; Minimum Level: " . $min_level . " &middot; Date: " . $date; ?></small></span>
		<span class="muted pull-right"><small><?php echo $num_participant . " participants" ?></small></span>
		</p>

	</div>
</li>