<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<?php foreach ($threads as $thread_id => $thread): ?>
			<div class="thread-container clearfix">
				<div class="thread-pic pull-left">
					<img src="http://placehold.it/64x64" alt="">
				</div>
				<div class="thread-content">
				<div><a href="#"><strong><?php echo $thread["title"]; ?></strong></a></div>
				
					<?php if (!empty($thread["comments"])): ?>
						<?php foreach ($thread["comments"] as $comment_id => $comment): ?>
						<div class="clearfix thread-comment">
							<div class="thread-commment-pic">
								<img src="<?php echo $users[$comment['commenter_id']]->profile_pic; ?>" width="36" height="36"/>
							</div>
						    <div class="thread-comment-content">
						    	<a href="#"><strong><?php echo htmlentities($users[$comment['commenter_id']]->first_name)." ".htmlentities($users[$comment['commenter_id']]->last_name); ?></strong></a>
								<span><?php echo $comment['comment']; ?></span>		
								<div class="muted">
									<small><span data-livestamp="<?php echo strtotime((string) $comment['comment_time']) - 60 ?>"></span></small>
								</div>
						    </div>
						    </div>
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
				</div>			
			</div>
			<hr>
			<?php endforeach ?>
		</div>
	</div>
</div>