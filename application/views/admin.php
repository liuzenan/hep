<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<table class="table">
					<?php foreach($students as $student){
						$this->load->view("templates/studentItem", $student);
					} ?>
			</table>
		</div>
	</div>
</div>