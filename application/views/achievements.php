<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
				<ul class="thumbnails achievements">
					<?php foreach($achievements as $badge){
						$this->load->view("templates/achievementItem", $badge);
					} ?>
				</ul>
		</div>
	</div>
</div>