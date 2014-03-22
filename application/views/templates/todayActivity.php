		<div class="row-fluid">
			<div class="today-activity">
				<p class="section-title">Today's Activity  <span class="pull-right"><a class="challengeTitleTooltip" id="verifydata" data-original-title="Different from device record? Click to verify fitbit get the right data." href="http://www.fitbit.com/user/<?php echo $fitbit_id ?>" target="_black">last sync: <span data-livestamp="<?php if(!empty($me_today->last_update)) echo strtotime((string) $me_today->last_update) - 60; ?>"></span></a></span></p>
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid activity-chart">
							<div class="span4">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="<?php if($me_today->steps<$max_today->max_steps) echo "/assets/img/pixelred.gif"; else echo "/assets/img/pixelorange.gif" ?>" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->steps ?>" alt="" style="width:40px; height:<?php echo $max_today->max_steps==0?0:$me_today->steps/$max_today->max_steps*100 ?>px !important; margin-top:<?php echo ($max_today->max_steps==0?0:100 - $me_today->steps/$max_today->max_steps*100) ?>px"></div>
									<div class="span5"><img src="<?php echo base_url(); ?>/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_steps ?>" alt="" style="width:40px; height:<?php echo $max_today->max_steps==0?0:$avg->avg_steps/$max_today->max_steps*100 ?>px !important; margin-top:<?php echo $max_today->max_steps==0?0:100 - $avg->avg_steps/$max_today->max_steps*100 ?>px"></div>
								</div>
							</div>
							<div class="span4">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="<?php if($me_today->distance<$max_today->max_distance) echo "/assets/img/pixelred.gif"; else echo "/assets/img/pixelorange.gif" ?>" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->distance ?>" alt="" style="width:40px; height:<?php echo $max_today->max_distance==0?0:$me_today->distance/$max_today->max_distance*100 ?>px !important; margin-top:<?php echo $max_today->max_distance==0?0:100 - $me_today->distance/$max_today->max_distance*100 ?>px"></div>
									<div class="span5"><img src="<?php echo base_url(); ?>/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_distance ?>" alt="" style="width:40px; height:<?php echo $max_today->max_distance==0?0:$avg->avg_distance/$max_today->max_distance*100 ?>px !important; margin-top:<?php echo ($max_today->max_distance==0 ? 1 : 100 - $avg->avg_distance/$max_today->max_distance*100) ?>px"></div>
								</div>
							</div>
							<div class="span4">
								<div class="row-fluid">
									<div class="span5 offset2"><img src="<?php if($me_today->sleep<$max_today->max_sleep) echo "/assets/img/pixelred.gif"; else echo "/assets/img/pixelorange.gif" ?>" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "You: ".$me_today->sleep ?>" alt="" style="width:40px; height:<?php echo $max_today->max_sleep==0?0:$me_today->sleep/$max_today->max_sleep*100 ?>px !important; margin-top:<?php echo $max_today->max_sleep==0?0:100 - $me_today->sleep/$max_today->max_sleep*100 ?>px"></div>
									<div class="span5"><img src="<?php echo base_url(); ?>/img/pixelgreendark.gif" class="image-fix challengeTitleTooltip" data-original-title="<?php echo "Everybody: ".$avg->avg_sleep ?>" alt="" style="width:40px; height:<?php echo $max_today->max_sleep==0?0:$avg->avg_sleep/$max_today->max_sleep*100 ?>px !important; margin-top:<?php echo $max_today->max_sleep==0?0:100 - $avg->avg_sleep/$max_today->max_sleep*100 ?>px"></div>
								</div>
							</div>
						</div>
						<div class="row-fluid user-icons">
							<div class="span4">
								<div class="row-fluid user-icon">
									<div class="span5 offset2"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>
								</div>
							</div>
							<div class="span4">
								<div class="row-fluid user-icon">
									<div class="span5 offset2"><i class="icon-user icon-large"></i></div>
									<div class="span5"><i class="icon-group icon-large"></i></div>
								</div>
							</div>
							<div class="span4">
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
									<td class="activity-stats"><strong><span><?php echo $me_today->distance ?> km</span><br><span class="activityIcon distance"></span>&nbsp;Distance&nbsp;</strong><i class="<?php if($delta_distance>0) echo "icon-arrow-up"; else if ($delta_distance<0) echo "icon-arrow-down"; ?>"></i><?php if($delta_distance!=0) echo abs(floor($delta_distance*100)) . '%' ?></td>
									<td class="activity-stats"><strong><span><?php echo $me_today->sleep ?> hrs</span><br><span class="activityIcon sleep"></span>&nbsp;Sleep&nbsp;</strong><i class="<?php if($delta_sleep>0) echo "icon-arrow-up"; else if ($delta_sleep<0) echo "icon-arrow-down"; ?>"></i><?php if($delta_sleep!=0) echo abs(floor($delta_sleep*100)) . '%' ?></td>
								</tbody>
							</table>
						</div>
					</div>				
				</div>
			</div>
		</div>