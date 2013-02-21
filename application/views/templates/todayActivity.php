		<div class="row-fluid">
			<div class="today-activity">
				<p class="section-title">Today's Activity  <span class="pull-right">last sync: <span data-livestamp="<?php if(!empty($me_today->last_update)) echo strtotime((string) $me_today->last_update) - 60; ?>"></span></span></p>
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid activity-chart">
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->steps ?>" alt="" style="width:40px; height:<?php echo $max_today->max_steps==0?0:$me_today->steps/$max_today->max_steps*100 ?>px !important; margin-top:<?php echo ($max_today->max_steps==0?0:100 - $me_today->steps/$max_today->max_steps*100) ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_steps ?>" alt="" style="width:40px; height:<?php echo $max_today->max_steps==0?0:$avg->avg_steps/$max_today->max_steps*100 ?>px !important; margin-top:<?php echo $max_today->max_steps==0?0:100 - $avg->avg_steps/$max_today->max_steps*100 ?>px"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->floors ?>" alt="" style="width:40px; height:<?php echo $max_today->max_floors==0?0:$me_today->floors/$max_today->max_floors*100 ?>px !important; margin-top:<?php echo $max_today->max_floors==0?0:100 - $me_today->floors/$max_today->max_floors*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_floors ?>" alt="" style="width:40px; height:<?php echo $max_today->max_floors==0?0:$avg->avg_floors/$max_today->max_floors*100 ?>px !important; margin-top:<?php echo $max_today->max_floors==0?0:100 - $avg->avg_floors/$max_today->max_floors*100 ?>px"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->distance ?>" alt="" style="width:40px; height:<?php echo $max_today->max_distance==0?0:$me_today->distance/$max_today->max_distance*100 ?>px !important; margin-top:<?php echo $max_today->max_distance==0?0:100 - $me_today->distance/$max_today->max_distance*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_distance ?>" alt="" style="width:40px; height:<?php echo $max_today->max_distance==0?0:$avg->avg_distance/$max_today->max_distance*100 ?>px !important; margin-top:<?php echo ($max_today->max_distance==0 ? 1 : 100 - $avg->avg_distance/$max_today->max_distance*100) ?>px"></div>
								</div>
							</div>
							<div class="span3">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="/assets/img/pixelorange.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->calories ?>" alt="" style="width:40px; height:<?php echo $max_today->max_calories==0?0:$me_today->calories/$max_today->max_calories*100 ?>px !important; margin-top:<?php echo $max_today->max_calories==0?0:100 - $me_today->calories/$max_today->max_calories*100 ?>px"></div>
									<div class="span5"><img src="/assets/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_calories ?>" alt="" style="width:40px; height:<?php echo $max_today->max_calories==0?0:$avg->avg_calories/$max_today->max_calories*100 ?>px !important; margin-top:<?php echo $max_today->max_calories==0?0:100 - $avg->avg_calories/$max_today->max_calories*100 ?>px"></div>
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
						<div class="row-fluid">
							<table class="table activity-stats">
								<tbody>
									<td class="activity-stats"><strong><span><?php echo $me_today->steps ?></span><br><span class="activityIcon steps"></span>&nbsp;Steps&nbsp;</strong><i class="<?php if($delta_steps>0) echo "icon-arrow-up"; else if ($delta_steps<0) echo "icon-arrow-down"; ?>"></i><?php if($delta_steps!=0) echo abs(floor($delta_steps*100)) .'%' ?></td>
									<td class="activity-stats"><strong><span><?php echo $me_today->floors ?></span><br><span class="activityIcon floors"></span>&nbsp;Floors&nbsp;</strong><i class="<?php if($delta_floors>0) echo "icon-arrow-up"; else if ($delta_floors<0) echo "icon-arrow-down"; ?>"></i><?php if($delta_floors!=0) echo abs(floor($delta_floors*100)) . '%' ?></td>
									<td class="activity-stats"><strong><span><?php echo $me_today->distance ?></span><br><span class="activityIcon distance"></span>&nbsp;Distance&nbsp;</strong><i class="<?php if($delta_distance>0) echo "icon-arrow-up"; else if ($delta_distance<0) echo "icon-arrow-down"; ?>"></i><?php if($delta_distance!=0) echo abs(floor($delta_distance*100)) . '%' ?></td>
									<td class="activity-stats"><strong><span><?php echo $me_today->calories ?></span><br><span class="activityIcon sleep"></span>&nbsp;Calories&nbsp;</strong><i class="<?php if($delta_calories>0) echo "icon-arrow-up"; else if ($delta_calories<0) echo "icon-arrow-down"; ?>"></i><?php if($delta_calories!=0) echo abs(floor($delta_calories*100)) . '%' ?></td>
								</tbody>
							</table>
						</div>
					</div>				
				</div>
			</div>
		</div>