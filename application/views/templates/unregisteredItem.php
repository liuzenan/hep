<tr>
	<td><strong><?php echo $name ?></strong></td>
	<td><?php echo $email ?></td>
	<td><?php echo $code ?></td>
	<td>
		<?php if ($supercode==1): ?>
			<span class="label label-important"><?php echo "Supercode" ?></span>
		<?php endif ?>
		<?php if ($access==1): ?>
			<span class="label"><?php echo "Group 2" ?></span>
		<?php else: ?>
			<span class="label label-info"><?php echo "Group 3" ?></span>
		<?php endif ?>
	</td>
	<td><a href="<?php echo base_url() . "mail/invite/" . $code; ?>" class="btn inviteBtn">Resend Email</a></td>
</tr>