<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid forum-header">
			<ul class="breadcrumb">
				<li><a href="<?php echo base_url() . "forum" ?>">Forum</a> <span class="divider">/</span></li>
				<li><a href="<?php echo base_url() . "forum/topic/" . $first_row->forum_id ?>"><?php echo htmlentities($first_row->forum_title) ?></a> <span class="divider">/</span></li>
				<li><a href="<?php echo base_url() . "forum/thread/" . $first_row->topic_id ?>"><?php echo htmlentities($first_row->topic_title) ?></a> <span class="divider">/</span></li>
			</ul>
			<h3><?php echo htmlentities($first_row->title) ?></h3>
		</div>
		<?php if (!isset($first_row)): ?>
			<table class="table table-bordered table-striped">
			    <tbody>
			    	<tr>
			    		<td colspan="5" style="text-align:center;height:100px;vertical-align:middle;">
							Sorry, nothing here at the moment.			</td>
			    	</tr>
			    </tbody>
			</table>
		<?php endif ?>
		<?php if (isset($first_row)): ?>
			<div class="forum-post-container thread-post" data-thread-id="<?php echo $first_row->thread_id ?>">
				<div class="forum-post-header">
					<?php if ($first_row->anonymous==1): ?>
					Anonymous
					<?php else: ?>
					<?php echo htmlentities($first_row->creator_first_name) . " " . htmlentities($first_row->creator_last_name) ?>
					<?php endif ?>
					&middot
					<span data-livestamp="<?php date_default_timezone_set('UTC'); echo strtotime((string) $first_row->thread_create_time) - 60 ?>"></span>
				</div>
				<div class="forum-post-text">
					<p><?php echo htmlentities($first_row->thread_comment) ?></p>
				</div>
				<div class="forum-post-vote-control">
					<a href="javascript:void(0)" class="vote-button vote-up vote-enabled" data-action-value="up" role="button">
						<span class="hidden">Vote this post up</span>
						<i class="icon-chevron-up">
						</i>
					</a>
					<span class="vote-count"><?php echo $first_row->thread_votes ?></span>
					<a href="javascript:void(0)" class="vote-button vote-down vote-enabled" data-action-value="down" role="button">
						<span class="hidden">Vote this post down</span>
						<i class="icon-chevron-down">
						</i>
					</a>
					<div class="pull-right">
						<a href="javascript:void(0)" class="flag-button flag-enabled" role="button">
							<span>Flag as spam</span>
							<i class="icon-flag"></i>
						</a>
					</div>
					
				</div>
			</div>
			<?php if (isset($discussion_list)): ?>
				<?php foreach ($discussion_list as $key => $value): ?>
				<div class="forum-post-container thread-reply" data-post-id="<?php echo $value->id ?>">
					<div class="forum-post-header">
						<?php if ($value->anonymous==1): ?>
						Anonymous
							<?php else: ?>
						<?php echo htmlentities($value->first_name) . " " . htmlentities($value->last_name) ?>
						<?php endif ?>
						&middot
						<span data-livestamp="<?php date_default_timezone_set('UTC'); echo strtotime((string) $value->create_time) - 60 ?>"></span>
					</div>
					<div class="forum-post-text">
						<p><?php echo htmlentities($value->comment) ?></p>
					</div>
					<div class="forum-post-vote-control">
						<a href="javascript:void(0)" class="vote-button vote-up vote-enabled" data-action-value="up" role="button">
							<span class="hidden">Vote this post up</span>
							<i class="icon-chevron-up">
							</i>
						</a>
						<span class="vote-count"><?php echo $value->votes ?></span>
						<a href="javascript:void(0)" class="vote-button vote-down vote-enabled" data-action-value="down" role="button">
							<span class="hidden">Vote this post down</span>
							<i class="icon-chevron-down">
							</i>
						</a>
						<div class="pull-right">
							<a href="javascript:void(0)" class="flag-button flag-enabled" role="button">
								<span>Flag as spam</span>
								<i class="icon-flag"></i>
							</a>
						</div>
					</div>
				</div>
				<?php endforeach ?>
			<?php endif ?>
				<div class="row-fluid">
					<div class="span12">
						<textarea id="messageBox" name="compose" row="4" class="input-block-level" placeholder="Write a new message..."></textarea>
						<div class="btncontainer clearfix">
							<div class="postbtn pull-right">
								<span class="muted" id="postWordCount">800</span>
								<button class="btn" id="postMessage" data-thread-id="<?php echo $first_row->thread_id ?>">Post</button>
							</div>
						</div>
						<div id="alertContainer"></div>
					</div>				
				</div>
		<?php endif ?>
	</div>
</div>