		<div class="row-fluid">
			<div class="today-activity">
				<p><strong>Today's Activity</strong></p>
				<div class="row-fluid">
					<div class="span2">
						<div class="row-fluid">
							<img src="<?php echo $avatar ?>" alt="">
						</div>				
						<p><strong><?php echo $displayName ?></strong></p>
					</div>
					<div class="span10">	
						<div class="row-fluid activity-icons">
							<div class="span3"><span class="activityIcon steps"></span><h4>Steps</h4></div>
							<div class="span3"><span class="activityIcon floors"></span><h4>Floors</h4></div>
							<div class="span3"><span class="activityIcon distance"></span><h4>Distance</h4></div>
							<div class="span3"><span class="activityIcon"></span><h4>Calories</h4></div>
						</div>
						<div class="row-fluid activity-chart">
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $me_today->steps/$max_today->max_steps*100 ?>px !important; margin-top:<?php echo 100 - $me_today->steps/$max_today->max_steps*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $avg->avg_steps/$max_today->max_steps*100 ?>px !important; margin-top:<?php echo 100 - $avg->avg_steps/$max_today->max_steps*100 ?>px"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $me_today->floors/$max_today->max_floors*100 ?>px !important; margin-top:<?php echo 100 - $me_today->floors/$max_today->max_floors*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $avg->avg_floors/$max_today->max_floors*100 ?>px !important; margin-top:<?php echo 100 - $avg->avg_floors/$max_today->max_floors*100 ?>px"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $me_today->distance/$max_today->max_distance*100 ?>px !important; margin-top:<?php echo 100 - $me_today->distance/$max_today->max_distance*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $avg->avg_distance/$max_today->max_distance*100 ?>px !important; margin-top:<?php echo 100 - $avg->avg_distance/$max_today->max_distance*100 ?>px"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $me_today->calories/$max_today->max_calories*100 ?>px !important; margin-top:<?php echo 100 - $me_today->calories/$max_today->max_calories*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:40px; height:<?php echo $avg->avg_calories/$max_today->max_calories*100 ?>px !important; margin-top:<?php echo 100 - $avg->avg_calories/$max_today->max_calories*100 ?>px"></div>
								</div>
							</div>
						</div>
						<div class="row-fluid user-icons">
							<div class="span3">
								<div class="row-fluid user-icon">
									<div class="span5 offset2"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid user-icon">
									<div class="span5 offset2"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid user-icon">
									<div class="span5 offset2"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>	
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid user-icon">
									<div class="span5 offset2"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>	
								</div>
							</div>
						</div>
					</div>				
				</div>
			</div>
		</div>