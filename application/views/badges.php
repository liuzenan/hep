<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<ul class="thumbnails badges-list">
			<?php $i = 0; ?>
			<?php foreach ($badges as $badge): ?>
			<?php if ($i%3==0) {
				# code...
				echo "<div class='row-fluid'>";
			} ?>
					<li class="span4 pull-left">
						<div class="thumbnail badge-item">
							<img width="100" src="<?php echo base_url() . $badge['badge_pic'] ?>" alt="">

							<p class="text-center"><strong class="text-info"><?php echo $badge['name'] ?></strong>
							<br/>
							<strong class="">Earned: <?php echo $badge['count'] ?></strong></p>
							<p class="text-center"><?php echo $badge['description'] ?></p>
						</div>
					</li>
			<?php 
			$i += 1;
			if ($i%3==0) {
				# code...
				echo "</div>";
			} ?>
			<?php endforeach ?>
		</div>
			</ul>
		</div>
	</div>
</div>