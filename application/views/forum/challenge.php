<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid forum-header">
			<ul class="breadcrumb">
				<li><a href="<?php echo base_url() . "forum" ?>">Forum</a> <span class="divider">/</span></li>
				<li class="active">Challenges</li>
			</ul>
		</div>
		<div class="row-fluid">
			<?php foreach ($threads as $thread_id => $thread): ?>

				<h2><?php echo $thread["title"]; ?> </h2>

					<?php foreach ($thread["comments"] as $comment_id => $comment): ?>
						<a href="#">Commenter <?php echo $comment['commenter_id']; ?></a>
						<b><?php echo $comment['comment']; ?></b><br>&middot
						<span data-livestamp="<?php date_default_timezone_set('UTC'); echo strtotime((string) $comment['comment_time']) - 60 ?>"></span>
						<hr>
					<?php endforeach ?>
					<div class="row-fluid">
					<div class="span12">
						<textarea id="messageBox<?php echo $thread_id ?>" data-thread-id="<?php echo $thread_id ?>" name="compose messageBox" row="4" class="input-block-level" placeholder="Write a new message..."></textarea>
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