<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Events</strong></p>
		<ul class="nav nav-pills">
			<li class="<?php if($currentEventTab=="recent") echo "active" ?>"><a href="#" id="recentEvents">Most Recent</a></li>
			<li class="<?php if($currentEventTab=="popular") echo "active" ?>"><a href="#" id="popularEvents">Most Popular</a></li>
		</ul>
		<ul class="media-list challenge">
			<?php 
				if($events) {
					foreach($events as $event){
						$this->load->view('templates/eventItem', $event);
					}
				}
			?>
		</ul>
	</div>
</div>