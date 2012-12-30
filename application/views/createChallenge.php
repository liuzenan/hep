<div class="row-fluid" id="createChallenge">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">	
		<div class="row-fluid">
			<div class="span12">
				<form action="">
					<fieldset>
						<legend>New Challenge</legend>
						<div class="row-fluid">
							<div class="span6">
								<label for="title">Event Title</label>
								<input type="text" class="required" name="title" id="title">
								<label for="description">description</label>
								<textarea name="description" class="required" rows="10" id="description"></textarea>
							</div>
							<div class="span6">
								<label>Event Date</label>
								<div class="input-append date datepicker" id="date" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
									<input size="16" type="text" class="required" value="12-02-2012">
									<span class="add-on"><i class="icon-th"></i></span>
								</div>
								<label for="time">Time</label>
								<div class="input-append bootstrap-timepicker-component">
								    <input type="text" class="timepicker-default input-small" id="time">
								    <span class="add-on">
								        <i class="icon-time"></i>
								    </span>
								</div>
								<label for="frequency">Frequency</label>
								<select name="frequency" id="frequency">
									<option value="once">Once</option>
									<option value="daily">Daily</option>
									<option value="weekly">Weekly</option>
									<option value="monthly">Monthly</option>
								</select>
								<label for="location">Location</label>
								<input type="text" id="location" name="location">
								<label for="level">Minimum Level</label>
								<input type="text" id="level" class="required" name="level">
								<div>
									<button id="createEvent" class="btn btn-primary">Create</button>
									<button id="cancelEvent" class="btn">Cancel</button>
								</div>								
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>