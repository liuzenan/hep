<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<p><strong>Student List for House: <?php echo $currentHouse ?></strong></p>
		<div class="row-fluid">
			<div class="span12">
				<ul class="media-list achievements">
					<?php foreach($students as $student){
						$this->load->view("templates/studentItem", $student);
					} ?>
				</ul>
			</div>
		</div>
	</div>
</div>