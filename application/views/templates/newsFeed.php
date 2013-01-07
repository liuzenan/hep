			<div class="span6 well">
				<div class="row-fluid">
					<div class="span12">
						<textarea id="messageBox" name="compose" row="4" class="input-block-level" placeholder="Write a new message..."></textarea>
						<div class="btncontainer clearfix">
							<div class="postbtn pull-right">
								<span class="muted" id="postWordCount">140</span>
								<button class="btn" id="postMessage">Post</button>
							</div>
						</div>
						<div id="alertContainer"></div>
					</div>				
				</div>
				<div class="row-fluid">
					<div class="span12">
					<p><strong>News Feed</strong></p>
						<ul class="media-list newsFeed" id="newsFeed">
							<?php foreach($posts as $post){ ?>
							<li class="media">
								<a href="#" title="" class="pull-left post-profile">
									<img src="<?php echo $post['profile_pic'] ?>" alt="">
								</a>
								<div class="media-body">
									<p class="media-heading"><strong><?php echo $post['username'] ?></strong></p>
									<p><?php echo htmlentities($post['description']) ?></p>
									<p><small><span data-livestamp="<?php 
									if($post['type']=="0"){
										date_default_timezone_set('Asia/Singapore');
									}else{
										date_default_timezone_set('UTC'); 
									}
									echo strtotime((string) $post['time']); 
									?>"></span></small></p>
								</div>
							</li>
							<?php } ?>
						</ul>
					</div>	
				</div>
			</div>
