<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<?php for($i=1; $i<=10; $i++): ?>
			<li class="<?php if($house_id==$i) echo "active" ?>"><a href="<?php echo base_url() . "manage/house/".$i; ?>"><?php echo $i ?></a></li>
		<?php endfor ?>
		<li class="<?php if($house_id==-1) echo "active" ?>"><a href="<?php echo base_url() . "manage/house/-1"; ?>">Tutor</a></li>
		<li class="<?php if($house_id==0) echo "active" ?>"><a href="<?php echo base_url() . "manage/invalid"; ?>">Invalid</a></li>


		</ul>


		<div class="row-fluid">
			<table class="table">
					<?php foreach($students as $student){
						$this->load->view("templates/studentItem", $student);
					} ?>
			</table>
		</div>
	</div>
</div>