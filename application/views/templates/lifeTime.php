<div class="row-fluid">
	<div class="span12 well">
		<p><strong>Total Lifetime Activity</strong></p>
		<table class="table">
			<thead>
				<tr>
					<th>Activity</th>
					<th>Total Record</th>
				</tr>
			</thead>
			<tbody>
				<?php if($stats) {?>
					<?php foreach($stats['lifetime'] as $key=>$value) {?>
					<tr>
						<td><?php echo $key ?></td>
						<td><?php echo $value ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>