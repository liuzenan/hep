<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Overall Ranking</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th></th>
					<th>Name</th>
					<th>House</th>
					<th>Score</th>
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
					<td> <img src="<?php echo $row->avatar ?>" width="25px" height="25px"/></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->house ?></td>
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
		<p><strong>Female Ranking</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th></th>
					<th>Name</th>
					<th>House</th>
					<th>Score</th>
				</tr>
			</thead>
			<tbody>
				
				<?php 
					$n=0;
					foreach($female as $row){ 
						$n++;
				?>
				<tr>
					<td><?php echo $n ?></td>
					<td> <img src="<?php echo $row->avatar ?>" width="25px" height="25px"/></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->house ?></td>
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
		<p><strong>Male Ranking</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th></th>
					<th>Name</th>
					<th>House</th>
					<th>Score</th>
				</tr>
			</thead>
			<tbody>
				
				<?php 
					$n=0;
					foreach($male as $row){ 
						$n++;
				?>
				<tr>
					<td><?php echo $n ?></td>
					<td> <img src="<?php echo $row->avatar ?>" width="25px" height="25px"/></td>
					<td><?php echo $row->firstname . ' ' . $row->lastname ?></td>
					<td><?php echo $row->house ?></td>
					<td><?php echo $row->score ?></td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>