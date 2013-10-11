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
						<div class="row-fluid activity-chart">
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $me_today->steps/$max_today->max_steps*100 ?>px !important"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $avg->avg_steps/$max_today->max_steps*100 ?>px !important"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $me_today->floors/$max_today->max_floors*100 ?>px !important"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $avg->avg_floors/$max_today->max_floors*100 ?>px !important"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $me_today->distance/$max_today->max_distance*100 ?>px !important"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $avg->avg_distance/$max_today->max_distance*100 ?>px !important"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><img src="/assets/img/pixelorange.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $me_today->steps/$max_today->max_steps*100 ?>px !important"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix" alt="" style="width:30px; height:<?php echo $avg->avg_steps/$max_today->max_steps*100 ?>px !important"></div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>	
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>	
								</div>
							</div>
						</div>
					</div>				
				</div>
			</div>
		</div>