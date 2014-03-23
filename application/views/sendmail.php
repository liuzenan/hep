<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<label>Previous emails:</label>
			<div class="accordion">
				<?php $i=0; ?>
				<?php foreach ($emailmsg as $emailContent): ?>
				<div class="accordion-group" id="emailAccordian">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#emailAccordian" href="#collapse<?php echo $i; ?>">
        					<?php echo $filenames[$i]; ?>
      					</a>
					</div>
					<div id="collapse<?php echo $i ?>" class="accordion-body collapse">
      					<div class="accordion-inner">
        					<p id="content<?php echo $i ?>"><?php echo nl2br($emailContent); ?></p>
      					</div>
   					</div>
   				</div>
   				<?php $i++; ?>
				<?php endforeach ?>
			</div>
			<label class="pull-left">Message Title</label>
			<input id="mailTitle" class="span12" type="text">
			<label class="pull-left">Write Message: </label>
			<div class="pull-right" id="sending-indicator" style="display:none;">Sending...</div>
			<textarea id="mailMsg" class="span12" rows="20"><?php if(!empty($today))echo nl2br($today); ?></textarea>
			<button id="sendMail" class="btn btn-primary pull-right">Send Email to All</button>
		</div>
	</div>
</div>