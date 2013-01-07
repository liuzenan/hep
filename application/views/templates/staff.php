<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Total Level</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Name</th>
					<th>Level</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$n=0;
					foreach($leader as $row){ 
						$n++;
				?>
				<tr>
					<td><?php echo $n ?></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->total_points ?></td>
					<td><?php 
				$this->load->helper('level');
				echo getLevel(intval($row->total_points)); ?></td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>
<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Steps</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Name</th>
					<th>Level</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$n=0;
					foreach($topSteps as $row){
						$n++;

				 ?>
				<tr>
					<td><?php echo $n ?></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->total_steps ?></td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>
<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Floors</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Name</th>
					<th>Level</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$n=0;
					foreach($topFloors as $row){
						$n++;
				 ?>
				<tr>
					<td><?php echo $n ?></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->total_floors ?></td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>
<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Sleep</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Name</th>
					<th>Level</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$n=0;
					foreach($topSleep as $row){
						$n++;
				 ?>
				<tr>
					<td><?php echo $n ?></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->avg_sleep ?>&#37;</td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>