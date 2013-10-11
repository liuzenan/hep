<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
				<ul class="thumbnails achievements">
				<?php if($achievements) {?>

					<?php foreach($achievements as $badge){
						$this->load->view("templates/achievementItem", $badge);
					} ?>
				<?php } else {?>
				<p class="mute">You currently don't have any Fitbit achievements yet... :(</p>
				<?php } ?>

				</ul>
		</div>
	</div>
</div>