<div class="row-fluid">
	<div class="span12">
		<ul class="media-list achievements">
			<?php foreach($achievements as $badge){
				$this->load->view("templates/achievementItem", $badge);
			} ?>
		</ul>
	</div>
</div>