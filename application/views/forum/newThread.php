<div class="row-fluid" id="createChallenge">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">	
		<div class="row-fluid">
			<div class="span12">
				<form id="newThreadForm" data-topic-id="<?php echo $topic_id ?>">
					<fieldset>
						<legend>New Thread</legend>
						<div class="row-fluid">
							<div class="form-horizontal">
								<div class="control-group">
								<label for="title">Thread Title</label>
								<div class="controls">
								<input type="text" class="required input-block-level" name="title" id="title">
								</div>
								</div>
								
								<div class="control-group">
								<label for="description">Message</label>
								<div class="controls">
								<textarea name="message" class="required input-block-level" rows="13" id="description"></textarea>
								</div>
								</div>

								<div class="control-group">
								<label for="anonymous">Anonymous?</label>
								<div class="controls">
								<input name="anonymous" type="checkbox">
								<span>Make this post anonymous to other students.</span>
								</div>
								</div>

								<div class="control-group">
								<label for="subscribe">Subcribe?</label>
								<div class="controls">
								<input name="subscribe" type="checkbox">
								<span>Subscribe to this thread at the same time.</span>
								</div>
								</div>
								<div class="control-group"><div class="controls"><button id="newThread" class="btn btn-primary">Create New Thread</button></div></div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>