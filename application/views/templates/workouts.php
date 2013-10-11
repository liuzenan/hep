<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Regular Workouts</strong></p>
		<ul class="nav nav-pills">
			<li class="<?php if($currentWorkoutTab=="recent") echo "active" ?>"><a href="#" id="recentWorkouts">Most Recent</a></li>
			<li class="<?php if($currentWorkoutTab=="popular") echo "active" ?>"><a href="#" id="popularWorkouts">Most Popular</a></li>
		</ul>
		<ul class="media-list challenge">
			<?php 
				if($workouts) {
					foreach($workouts as $workout){
						$this->load->view('templates/eventItem', $workout);
					}
				}
			?>
		</ul>
	</div>
</div>