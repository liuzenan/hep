<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Total Level</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Rank</th>
					<th>Name</th>
					<th>Total Points</th>
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
					<td><?php echo $row->house ?></td>
					<td><?php echo $row->total_points ?></td>
				</tr>
				<?php 
				}
				 ?>
			</tbody>
		</table>
	</div>
</div>
