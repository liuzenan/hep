<div class="row-fluid">
	<div class="span12">
		<ul class="media-list challenge clearfix">
			<?php 
				if(!empty($challenges)) {
					if ($tab=="my") {
						foreach($challenges as $challenge){
							$this->load->view('templates/currentChallengeItem', $challenge);
						}
					} else if ($tab=="all"){
						foreach($challenges as $challenge){
							$challenge->user_id = $user_id;
							$this->load->view('templates/challengeItem', $challenge);
						}
					} else {
						foreach($challenges as $challenge){
							$this->load->view('templates/completedChallengeItem', $challenge);
						}
					}
					
				}
			?>
		</ul>
	</div>
</div>