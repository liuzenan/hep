<div class="row-fluid">
	<div class="span12">
		<ul class="media-list challenge clearfix current-challenge-list">
			<?php if (!empty($challenges)): ?>
			<?php if ($tab=="my"): ?>
			<div class="rowfluid my">
				<div class="span5 offset1 today">
					<p class="section-title">Today</p>
					<?php	foreach($challenges as $challenge){
						$this->load->view('templates/currentChallengeItem', $challenge);
					} ?>	
				</div>
				<div class="span5 tomorrow">
					<p class="section-title">Tomorrow</p>
					<?php	foreach($tomorrow as $challenge){
						$this->load->view('templates/currentChallengeItem', $challenge);
					} ?>								
				</div>
			</div>
		<?php else: ?>
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
<?php endif ?>
</ul>
</div>
</div>