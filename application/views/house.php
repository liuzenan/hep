<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Sub-forum</th>
						<th>Latest Activity</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($forum_list): ?>
						<?php foreach ($forum_list as $row): ?>
							<?php if ($isTutor || $row->visible_student): ?>
								<tr>
									<td><H4><a href="<?php echo base_url() . 'forum/topic/' . $row->id ?>" title=""><?php echo $row->title ?></a></H4>
										<p><?php echo $row->description ?></p>
									</td>
									<td></td>
								</tr>
							<?php endif ?>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>