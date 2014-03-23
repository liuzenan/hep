<h4 class="maintitle">Students that have not completed the survey</h4 class="maintitle">
<table class='table'>
	<thead>
		<tr>
			<th>Name</th>
			<th>Email</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($students as $student): ?>
		<tr>
		<td><?php echo $student->first_name . ' ' . $student->last_name; ?></td>
		<td><?php echo $student->email; ?></td>
		</tr>
	<?php endforeach ?>
	</tbody>

</table>