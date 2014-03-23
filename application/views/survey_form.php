<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
	<h3 class="maintitle"><?php echo $survey->title ?></h3>
	<?php
	$notice = $this->session->flashdata('survey-message');
	if ($notice) {
		echo '<div class="alert alert-success">'.$notice.'</div>';
	} ?>


	<div><?php echo $survey->description; ?></div>
	<form id="survey_form" action="<?php echo base_url() . "survey/submitSurvey/" . $survey->id; ?>" accept-charset="utf-8" method="POST">
		<?php 
		$index = 0;
		foreach ($questions as $question) {
			$index++;
			echo '<div class="survey-item">' . $index.'. '.nl2br(htmlspecialchars($question->description)).'</div class="survey-item">';
			$data = array('question' => $question);
			if (isset($response[$question->id])) {
				$data['response'] = $response[$question->id];
			} else {
				$data['response'] = null;
			}
			switch ($question->type) {
				case 'radio':
					$this->load->view('survey/radio', $data);
					break;
				case 'checkbox':
					$this->load->view('survey/checkbox', $data);
					break;
				case 'essay':
					$this->load->view('survey/essay', $data);
					break;
				case 'input':
					$this->load->view('survey/input', $data);
					break;
				
				default:
					# code...
					break;
			}
		} ?>
		<br>
		<button type="submit" name="submitBtn" value="draft" class="btn">Save Draft</button>
		<button type="submit" name="submitBtn" value="submit" class="btn btn-primary">Submit</button>
	</form>
</div>