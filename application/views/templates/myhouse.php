<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Total Level</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Name</th>
					<th>Points</th>
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
					<td><?php echo $row->score ?></td>		
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
					<th>Steps</th>
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
					<td><?php echo $row->score ?></td>
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
					<th>Floors</th>
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
					<td><?php echo $row->score ?></td>
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
					<th>Avg. Sleep Efficiency</th>
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
					<td><?php echo $row->score ?>&#37;</td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>