<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid forum-header">
			<ul class="breadcrumb">
				<li><a href="<?php echo base_url() . "forum" ?>">Forum</a> <span class="divider">/</span></li>
				<li class="active"><?php echo $forum->title ?></li>
			</ul>
		</div>
		<div class="row-fluid">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Topics</th>
						<th>Latest Activity</th>
					</tr>
				</thead>
				<tbody>
					<?php if (isset($topic_list)): ?>
						<?php foreach ($topic_list as $row): ?>
							<tr>
								<td><h4>
									<a href="<?php echo base_url() . "forum/thread/" . $row->id ?>" title=""><?php echo htmlentities($row->title) ?>
									</a></h4>
									<p><?php echo htmlentities($row->description) ?></p>
								</td>
								<td></td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
		</div>
	</div>
</div>