		<div class="row-fluid">
			<div class="span12 well">
				<div class="row-fluid">
					<div class="span12">
						<textarea name="compose" row="4" class="input-block-level" placeholder="Write a new message..."></textarea>
						<button class="btn pull-right">Post</button>
					</div>
					<div class="span12">
					<p><strong>News Feed</strong></p>
						<?php foreach($posts as $post){ ?>
						<ul class="media-list">
							<li class="media">
								<a href="#" title="" class="pull-left">
									<img src="<?php echo $post['profile_pic'] ?>" alt="">
								</a>
								<div class="media-body">
									<h4 class="media-heading"><?php echo $post['username'] ?></h4>
									<p><?php echo $post['description'] ?></p>
									<p><small><span data-livestamp="<?php date_default_timezone_set('Asia/Singapore'); echo (strtotime((string) $post['time']) - 86400); ?>"></span></small></p>
								</div>
							</li>
						</ul>
						<?php } ?>
					</div>					
				</div>
			</div>
		</div>