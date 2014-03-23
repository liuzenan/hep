<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<?php if($notice = $this->session->flashdata('survey-message')) {
			echo '<div class="alert alert-success">'.$notice.'</div>';
		} else if ($notice = $this->session->flashdata('survey-error')) {
			echo '<div class="alert alert-error">'.$notice.'</div>';
		}?>
		<?php if (count($surveys) == 0): ?>
			<h4 class='text-center'>No surveys are open at the moment</h4>
		<?php else: ?>
			<h3 class="maintitle"> Current Surveys </h3>
			<table class='table'>
				<thead>
					<tr>
						<th>Title</th>
						<th>Open Dates</th>
						<th>Progress</th>
						<?php if((!empty($isTutor) && $isTutor==1)  || (!empty($isAdmin) && $isAdmin==1)): ?>
						<th>Result</th>
						<th>Edit</th>
						<?php endif ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($surveys as $survey): ?>
						<tr class="<?php echo isset($survey->completed) ? $survey->completed ? '' : 'info' : 'info' ?>">
							<?php if (isset($survey->completed) && $survey->completed): ?>
							<td><?php echo $survey->title ?></td>
							<?php else: ?>
							<td><a href="./survey/id/<?php echo $survey->id ?>"><?php echo $survey->title ?></a></td>
							<?php endif ?>
							<td><?php echo my_date($survey->open_date).' - '.my_date($survey->close_date); ?></td>
							<td><?php echo isset($survey->completed) ? $survey->completed ? 'Completed' : 'In Progress' : 'Not Started' ?></td>
							<?php if((!empty($isTutor) && $isTutor==1)  || (!empty($isAdmin) && $isAdmin==1)): ?>
							<td><a href="<?php echo base_url().'surveyresult/view/'.$survey->id;?>">Result</a></td>
							<td>Coming soon</td>
							<?php endif ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>

		<?php if (count($closedSurveys) != 0): ?>
			<h3 class="maintitle"> Closed Surveys (admin only) </h3>
			<table class='table'>
				<thead>
					<tr>
						<th>Title</th>
						<th>Open Dates</th>
						<th>Progress</th>
						<th>Result</th>
						<th>Edit</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($closedSurveys as $survey): ?>
						<tr class="<?php echo isset($survey->completed) ? $survey->completed ? 'success' : 'info' : '' ?>">
							<td><a href="./survey/id/<?php echo $survey->id ?>"><?php echo $survey->title ?></a></td>
							<td><?php echo my_date($survey->open_date).' - '.my_date($survey->close_date); ?></td>
							<td><?php echo isset($survey->completed) ? $survey->completed ? 'Completed' : 'In Progress' : 'Not Started' ?></td>
							<td><a href="<?php echo base_url().'surveyresult/view/'.$survey->id;?>">Result</a></td>
							<td>Coming soon</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>

	</div>
</div>