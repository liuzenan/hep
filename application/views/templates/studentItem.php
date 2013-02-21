<tr>
	<td><strong><?php echo $first_name . ' ' . $last_name ?></strong></td>
	<td><?php echo $email ?></td>
	<td>
		<?php if ($admin==1): ?>
			<span class="label"><?php echo "Admin" ?></span>
		<?php endif ?>
		<?php if ($phantom==1): ?>
			<span class="label"><?php echo "Phantom User" ?></span>
		<?php endif ?>
		<?php if ($staff==1): ?>
			<span class="label"><?php echo "Tutor" ?></span>
		<?php endif ?>
		<?php if ($staff==0 && $house_id>0): ?>
			<span class="label"><?php echo "Student" ?></span>
			<span class="label"><?php echo "House: " . $house_id ?></span>
		<?php endif ?>
		<?php if ($leader==1): ?>
			<span class="label"><?php echo "House Leader" ?></span>
		<?php endif ?>	
	</td>
	<td><a href="<?php echo base_url() . "manage/user/" . $id; ?>" class="btn">Edit</a></td>
	<td></td>
</tr>