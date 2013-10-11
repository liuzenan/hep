<div class="row-fluid" id="createChallenge">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">	
		<div class="row-fluid">
			<div class="row-fluid">
				<div class="media well">
					<img class="media-object pull-left" width="100" src="<?php echo $event->event_image ?>">
					<?php if (!$joined): ?>
						<button data-id="<?php echo $event->id ?>" class="btn pull-right joinbtn">Join</button>
					<?php else: ?>
						<button data-id="<?php echo $event->id ?>" class="btn pull-right sharebtn">Share</button>
						<button data-id="<?php echo $event->id ?>" class="btn pull-right leavebtn">Leave</button>
					<?php endif ?>
	<div class="media-body">
		<h4 class="media-heading"><?php echo $event->title ?></h4>
		<p><?php echo $event->description ?></p>
		<p>
			<strong>Location: </strong><?php echo $event->location ?>
		</p>
		<p><strong>Date: </strong><?php echo $event->date ?></p>
		<p><strong>Time: </strong><?php echo $event->time ?></p>
		<p><strong>Frequency: </strong><?php echo $event->frequency ?></p>
		<p><strong>Time Created: </strong><?php echo $event->time_created ?></p>
	</div>					
				</div>

			</div>
			<div class="row-fluid">
				<div class="span12">
					<h4>Participants</h4>
					<?php if (isset($participants)&&count($participants)>0): ?>
					<?php foreach ($participants as $participant): ?>
						<a href="<?php echo base_url() . 'profile/viewprofile/' . $participant->id ?>"><img src="<?php echo $participant->profile_pic ?>" width="64" alt=""></a>
					<?php endforeach ?>						
					<?php endif ?>
				</div>
			</div>
			<div class="row-fluid">
					<div class="span12">

					<h4><strong>Comments</strong></h4>
						<textarea id="messageBox" name="compose" row="4" class="input-block-level" placeholder="Write a comment..."></textarea>
						<div class="btncontainer clearfix">
							<div class="postbtn pull-right">
								<span class="muted" id="postWordCount">140</span>
								<button class="btn" data-id="<?php echo $event->id ?>" id="postComment">Post</button>
							</div>
						</div>
						<div id="alertContainer"></div>
						<ul class="media-list newsFeed" id="newsFeed">
							<?php if (isset($comments)): ?>
							<?php foreach($comments as $comment){ ?>
							<li class="media">
								<a href="<?php echo base_url() . 'profile/viewprofile/' . $comment->id ?>" title="" class="pull-left post-profile">
									<img src="<?php echo $comment->profile_pic?>" alt="">
								</a>
								<div class="media-body">
									<p class="media-heading"><strong><?php echo $comment->first_name ?></strong></p>
									<p><?php echo htmlentities($comment->comment) ?></p>
									<p><small><span data-livestamp="<?php 
									date_default_timezone_set('UTC'); 
									echo strtotime((string) $comment->time_created); 
									?>"></span></small></p>
								</div>
							</li>
							<?php } ?>								
							<?php endif ?>

						</ul>
					</div>	
			</div>

		</div>
	</div>
</div>