<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<?php if (count($threads)>0): ?>

		<div class="row-fluid challenge-forum">
			<?php foreach ($threads as $thread_id => $thread): ?>
			<div class="thread-container clearfix">
				<div class="thread-pic pull-left">
					<img src="<?php echo $thread["badge_pic"]; ?>" alt="">
				</div>
				<div class="thread-content">
				<div>
					<strong><?php echo $thread["title"]; ?></strong>
					<?php if ($thread["subscribe"]==0): ?>
						<a href="javascript:(void);" class="subscribe-link pull-right" data-thread-id="<?php echo $thread_id ?>"><small>Subscribe</small></a>
					<?php endif ?>
					<?php if ($thread["subscribe"]==1): ?>
						<a href="javascript:(void);" class="unsubscribe-link pull-right" data-thread-id="<?php echo $thread_id ?>"><small>Unsubscribe</small></a>
					<?php endif ?>
				</div>
				<div class="thread-comments-container">
					<?php if (!empty($thread["comments"])): ?>
					<small><a class="show-button showmore <?php if(count($thread["comments"])<=3) echo "hide"; ?>" data-comments="<?php echo count($thread['comments']); ?>" href="javascript:void(0);">Show all <?php echo count($thread['comments']) ?> comments</a></small>
					<div class="collapsed-content <?php if(count($thread["comments"])<=3) echo "hide"; ?>">
						<?php $data = array("thread"=>$thread); $this->load->view("forum/collapsedItem", $data) ?>						
					</div>
					<div class="full-content <?php if(count($thread["comments"])>3) echo "hide"; ?>">
						<?php $data = array("thread"=>$thread); $this->load->view("forum/fullItem", $data) ?>
					</div>
					<?php endif ?>						
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="clearfix thread-comment">
							<div class="thread-commment-pic"><img src="<?php echo $avatar ?>" width="36" height="36"/></div>
							<div class="thread-comment-content clearfix">
						<textarea id="messageBox<?php echo $thread_id ?>" data-thread-id="<?php echo $thread_id ?>" name="compose" class="input-block-level messageBox" placeholder="Write a new message..."></textarea>
						<div class="btncontainer" data-thread-id="<?php echo $thread_id ?>">
							<div class="postbtn">
								<span class="muted postWordCount" id="postWordCount<?php echo $thread_id ?>">800</span>
								<button class="btn postMessage" data-thread-id="<?php echo $thread_id ?>">Post</button>
							</div>
						</div>
						</div>
						<div id="alertContainer<?php echo $thread_id ?>"></div>
					</div>
					</div>				
				</div>					
				</div>			
			</div>
			<hr>
			<?php endforeach ?>
		</div>
		<?php endif ?>
	</div>
</div>