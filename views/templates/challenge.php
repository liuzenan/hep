<div class="row-fluid">
	<div class="span12">
		<ul class="media-list challenge clearfix current-challenge-list">
<?php if (!empty($challenges)): ?>
	<?php if ($tab=="all"): ?>
		<?php $path = 'templates/challengeItem' ?>
	<?php else: ?>
	<?php $path = 'templates/completedChallengeItem' ?>
	<?php endif ?>
<p class="section-title">Steps Challenges</p>
<div class="clearfix steps">
	<?php $data=array("path"=>$path,"challenges"=>$challenges, "type"=>1) ?>
	<?php $this->load->view("templates/challengeCategory", $data) ?>
</div>

<p class="section-title">Climb Challenges</p>
<div class="clearfix floors">
	<?php $data=array("path"=>$path,"challenges"=>$challenges, "type"=>2) ?>
	<?php $this->load->view("templates/challengeCategory", $data) ?>
</div>

<p class="section-title">Sleep Challenges</p>
<div class="clearfix sleep">
	<?php $data=array("path"=>$path,"challenges"=>$challenges, "type"=>3) ?>
	<?php $this->load->view("templates/challengeCategory", $data) ?>
</div>

<p class="section-title">Time-based Challenges</p>
<div class="clearfix time-based">
	<?php $data=array("path"=>$path,"challenges"=>$challenges, "type"=>0) ?>
	<?php $this->load->view("templates/challengeCategory", $data) ?>
</div>
<?php endif ?>
</ul>
</div>
</div>