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
        					<div class="pull-right">
								<a id="emailedit<?php echo $i ?>" data-msg-id="<?php echo $i ?>" data-date="<?php echo $filenames[$i]; ?>" href="javascript:void(0);" class="emailedit">Edit</a>
        						<span> | </span>
        						<a id="emaildelete<?php echo $i ?>" data-msg-id="<?php echo $i ?>" data-date="<?php echo $filenames[$i]; ?>" href="javascript:void(0);" class="emaildelete">Delete</a>
							</div>
      					</div>
   					</div>
   				</div>
   				<?php $i++; ?>
				<?php endforeach ?>
			</div>
			<label class="pull-left">Add/Update additional e-mail message for date: </label>
			<div class="input-append date pull-left date-picker" id="dp3" data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
			  <input id="emailDate" class="span10" size="16" type="text" value="<?php echo date('Y-m-d'); ?>">
			  <span class="add-on"><i class="icon-th"></i></span>
			</div>
			<textarea id="mailMsg" class="span12" rows="20"><?php if(!empty($today))echo nl2br($today); ?></textarea>
			<button id="updateMail" class="btn btn-primary pull-right">Update</button>
		</div>
	</div>
</div>