<?php 
foreach ($challenges as $challenge) {
	if ($challenge->category == $type) {
		$this->load->view($path, $challenge);
	}
}

 ?>
		