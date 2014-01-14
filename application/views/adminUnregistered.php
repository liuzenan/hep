<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<?php for($i=1; $i<=10; $i++): ?>
			<li><a href="<?php echo base_url() . "manage/house/".$i; ?>"><?php echo $i ?></a></li>
		<?php endfor ?>
		<li><a href="<?php echo base_url() . "manage/house/-1"; ?>">Tutor</a></li>
		<li><a href="<?php echo base_url() . "manage/invalid"; ?>">Invalid</a></li>
		<li class="active"><a href="<?php echo base_url() . "manage/unregistered"; ?>">Unregistered</a></li>		</ul>
		<div class="row-fluid">
			<div style="text-align: center; margin-bottom: 1em;">
   				<a href="#" class="btn btn-primary" id="emailall">Email all unregistered students</a>
			</div>
			<table class="table">
				<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Code</th>
				<th>Type</th>
				<th>Send Email</th>
				</tr>
					<?php foreach($students as $student){
						$this->load->view("templates/unregisteredItem", $student);
					} ?>
			</table>
		</div>
	</div>
</div>

<script>
$(function(){
	$('#emailall').click(function(evt) {
		evt.preventDefault();
		if ($(this).hasClass('disabled')) {
			return false;
		}

		$(this).addClass('disabled');
		$(this).text('Sending...');
		var that = this;
		$.post("<?php echo base_url() . 'mail/invite' ?>", '', function(msg){
			$(that).text('Email sent successfully');
		});
	});

	$('.inviteBtn').click(function(evt) {
		evt.preventDefault();
		if ($(this).hasClass('disabled')) {
			return false;
		}
		$(this).addClass('disabled');
		$(this).text('Sending...');
		var link = $(this).attr('href');
		var that = this;
		$.post(link, '', function(msg){
			$(that).text('Sent');
			$(that).addClass('btn-success');
		});
	});
})
</script>