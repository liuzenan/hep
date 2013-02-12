<?php $comments = array_slice($thread["comments"], -3, 3, true); ?>
						<?php foreach ($comments as $comment_id => $comment): ?>
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