<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<?php foreach ($threads as $thread_id => $thread): ?>
				<h2><?php echo $thread["title"]; ?> </h2>
				<?php if (isset($thread["comments"])): ?>
					<?php foreach ($thread["comments"] as $comment_id => $comment): ?>
					    <img src="<?php echo $users[$comment['commenter_id']]->profile_pic; ?>" width="25px" height="25px"/>
						<a href="#"> <?php echo htmlentities($users[$comment['commenter_id']]->first_name)." ".htmlentities($users[$comment['commenter_id']]->last_name); ?></a>
						<div class="forum-post-text">
						<b><?php echo $comment['comment']; ?></b>
						</div>				
						<span data-livestamp="<?php echo strtotime((string) $comment['comment_time']) - 60 ?>"></span>
						<hr>
					<?php endforeach ?>
				<?php endif ?>
					<div class="row-fluid">
					<div class="span12">
						<textarea id="messageBox<?php echo $thread_id ?>" data-thread-id="<?php echo $thread_id ?>" name="compose" row="4" class="input-block-level messageBox" placeholder="Write a new message..."></textarea>
						<div class="btncontainer clearfix" data-thread-id="<?php echo $thread_id ?>">
							<div class="postbtn pull-right">
								<span class="muted postWordCount" id="postWordCount<?php echo $thread_id ?>">800</span>
								<button class="btn postMessage" data-thread-id="<?php echo $thread_id ?>">Post</button>
							</div>
						</div>
						<div id="alertContainer<?php echo $thread_id ?>"></div>
					</div>				
				</div>
				<hr>
			<?php endforeach ?>
		</div>
	</div>
</div>