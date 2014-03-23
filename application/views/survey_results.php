<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
	<ul class="breadcrumb">
		<li><a href="<?php echo base_url() . 'survey'; ?>">Survey</a> <span class="divider">/</span></li>
		<li class="active"><?php echo $survey->title; ?></li>
	</ul>
      <ul class="nav nav-tabs">
      	<li class="<?php if($currentTab=="view") echo "active" ?>"><a href="<?php echo base_url() . "surveyresult/view/".$survey_id; ?>">Results</a></li>
        <li class="<?php if($currentTab=="incomplete") echo "active" ?>"><a href="<?php echo base_url() . "surveyresult/incomplete/".$survey_id; ?>">Pending submissions</a></li>
      </ul>
      <?php $this->load->view('survey/' . $currentTab) ?>
	</div>
</div>