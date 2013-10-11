<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<div class="row-fluid">
			<div class="media well">
				<img class="pull-left" src="<?php echo $userdata->profile_pic ?>" alt="">
				<div class="media-body">
					<h4 class="media-heading"><?php echo $userdata->first_name . " " . $userdata->last_name ?></h4>
					<p><strong>Gender: </strong><?php echo $userdata->gender ?></p>
					<?php if ($userdata->leader==1): ?>
					<p><strong>House Leader</strong></p>
				<?php endif ?>
				<?php if ($userdata->staff==1): ?>
				<p><strong>Tutor</strong></p>
			<?php endif ?>
			<p><strong>Exp points: </strong><?php echo $userdata->points ?></p>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12 well">
				<p><strong>Achievements</strong></p>
				<?php if(isset($userachievement) && count($userachievement)>0) {?>
				<?php foreach($userachievement as $badge){ ?>
				<img src="<?php echo $badge->badge_pic ?>" alt="">
				<?php }} else {?>
				<p class="mute">You currently don't have any Fitbit achievements yet... :(</p>
				<?php } ?>
			</div>		
		</div>
		<div class="row-fluid">
			<div class="span12 well">
				<p><strong>Challenges</strong></p>
				<?php if(isset($userevents) && count($userevents)>0) {?>
				<ul class="media-list">
					<?php foreach($userevents as $challenge){ ?>
					<li class="media">
						<a href="<?php echo base_url() . 'challenges/viewevent/' .$challenge->id ?>" class="pull-left"><img class="media-object" width="64" src="<?php echo $challenge->event_image ?>"></a> 
						<a href="<?php echo base_url() . 'challenges/viewevent/' .$challenge->id ?>" class="btn pull-right">View</a>
						<div class="media-body">
							<p class="media-heading"><?php echo $challenge->title ?></p>
							<p><small><strong>Date: </strong><?php echo $challenge->date ?></small></p>
						</div>
					</li>

					<?php } ?>
				</ul>
				<?php } else {?>
				<p class="mute">You currently don't have any challenges yet...</p>
				<?php } ?>
			</div>	
		</div>

		<div class="row-fluid">
					<div class="span12">
					<h4><strong>Latest News</strong></h4>
						<ul class="media-list newsFeed" id="newsFeed">
							<?php if (isset($userposts) && count($userposts)>0): ?>
							<?php foreach($userposts as $comment){ ?>
							<li class="media">
								<a href="#" title="" class="pull-left post-profile">
									<img src="<?php echo $userdata->profile_pic?>" alt="">
								</a>
								<div class="media-body">
									<p class="media-heading"><strong><?php echo $userdata->first_name ?></strong></p>
									<p><?php echo htmlentities($comment->description) ?></p>
									<p><small><span data-livestamp="<?php 
									if($comment->type=="0"){
										date_default_timezone_set('Asia/Singapore');
									}else{
										date_default_timezone_set('UTC'); 
									}
									echo strtotime((string) $comment->time); 
									?>"></span></small></p>
								</div>
							</li>
							<?php } ?>								
							<?php endif ?>

						</ul>
					</div>	
		</div>

</div>