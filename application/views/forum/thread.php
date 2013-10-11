<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid forum-header">
			<ul class="breadcrumb">
				<li><a href="<?php echo base_url() . "forum" ?>">Forum</a> <span class="divider">/</span></li>
				<li><a href="<?php echo base_url() . "forum/topic/" . $first_row->forum_id ?>"><?php echo $first_row->forum_title ?></a> <span class="divider">/</span></li>
			</ul>
			<h2><?php echo $first_row->topic_title ?></h2>
			<div class="row-fluid thread-control">
				<a href="<?php echo base_url() . "forum/newThread/" . $first_row->topic_id ?>" class="btn">Start New Thread</a>
			</div>
		</div>
		<div class="alert alert-block" style="clear:right;">
Please help all of us experience the best discussion environment possible: <br>
<ul style="margin-bottom:0px;">
    <li>Be friendly and considerate when talking to your fellow students.</li>
    <li>Use up-votes to bring attention to thoughtful, helpful posts.</li>
    <li>Post in the appropriate topic.</li> 
    <li>Please flag posts to report inappropriate content.</li>
</ul>

</div>
		<div class="row-fluid">
			<?php if (!isset($thread_list)): ?>
				<table class="table table-bordered table-striped">
				    <tbody>
				    	<tr>
				    		<td colspan="5" style="text-align:center;height:100px;vertical-align:middle;">
								Sorry, nothing here at the moment.			</td>
				    	</tr>
				    </tbody>
				</table>
			<?php endif ?>
			<?php if (isset($thread_list)): ?>
			<table class="table">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
						<?php foreach ($thread_list as $key => $value): ?>
							<tr>
								<td><span style="font-size:16px">
									<a href="<?php echo base_url() . 'forum/discussion/' . $value->id ?>" title="">
										<?php echo htmlentities($value->title) ?></a></span></td>
								<td class="forum-stats"><span><?php echo $value->votes ?></span><br>votes</td>
								<td class="forum-stats"><span><?php echo $value->num_posts ?></span><br>posts</td>
								<td class="forum-stats"><span><?php echo $value->views ?></span><br>views</td>
							</tr>
						<?php endforeach ?>
				</tbody>
			</table>
			<?php endif ?>
		</div>
	</div>
</div>