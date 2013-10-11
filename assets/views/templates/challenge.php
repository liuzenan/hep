<div class="row-fluid">
	<div class="span12">
		<ul class="media-list challenge">
			<?php 
				if(!empty($challenges)) {
					if ($tab=="my") {
						foreach($challenges as $challenge){
							$this->load->view('templates/currentChallengeItem', $challenge);
						}
					} else {
						foreach($challenges as $challenge){
							$challenge['user_id'] = $user_id;
							$this->load->view('templates/challengeItem', $challenge);
						}
					}
					
				}
			?>
		</ul>
	</div>
</div>