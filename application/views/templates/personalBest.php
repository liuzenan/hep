<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Personal Bests</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Activity</th>
					<th>Best Record</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody>
				<?php if($stats) {?>
					<?php foreach($stats['best'] as $key=>$value) {?>
					<tr>
						<td><?php echo $key ?></td>
						<td><?php echo $value['value'] ?></td>
						<td><?php echo $value['date'] ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>