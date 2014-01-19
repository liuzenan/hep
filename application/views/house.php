<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
	<?php if (!empty($rank['house_name'])): ?>
	<p class="section-title">My House</p>
							<table class="table house-stats">
								<tbody>
								<tr>
									<td rowspan="2"><img src="<?php echo $rank['picture']; ?>" alt=""></td>
									<td colspan="2"><span class="house-name"><?php echo $rank['house_name']; ?></span><br>House No.</td>
									<td colspan="2"><span class="house-rank"><?php echo $rank['rank']; ?></span><br>Rank</td>
									<td colspan="2"><span class="house-points"><?php echo $rank['points']; ?></span><br>Points</td>
								</tr>
								<tr>
									<td colspan="3"><span class="house-multiplier"><?php echo round($rank['steps_multiplier'], 2)*100; ?>%</span><br>Steps Boost</td>
									<td colspan="3"><span class="house-multiplier"><?php echo round($rank['sleep_multiplier'], 2)*100; ?>%</span><br>Sleep Boost</td>
								</tr>
								</tbody>
							</table>
	<p class="section-title">House Members</p>			
	<?php endif ?>
		<div class="row-fluid myhouse">
			<?php if (!empty($data)): ?>
			<?php foreach ($data as $value): ?>
				<div class="house-people clearfix">
					<a href="<?php if($value['profile']->fb==1) echo "http://www.facebook.com/" . $value['profile']->username; else echo "http://www.fitbit.com/user/" . $value['profile']->fitbit_id; ?>">
						<div class="house-people-pic">
							<img src="<?php if(!empty($value['profile'])) echo $value['profile']->profile_pic ?>" alt="">
						</div>
					</a>
					<div class="house-people-content">
						
						<div class="house-people-name">
							<div class="row-fluid">
								<div class="span2">
									<a href="#">
								<strong>
									<?php if(!empty($value['profile'])) echo $value['profile']->first_name . ' ' . $value['profile']->last_name; ?>
								</strong>
									</a>
								</div>
								<div class="span3">
									<strong class="muted">
										<small>Avg. Daily Steps</small>
								</strong>
								</div>
								<div class="span3">
									<strong class="muted">
										<small>Avg. Daily Sleep</small>
									
								</strong>
								</div>
								<div class="span4">
									<strong class="muted"><small>Badges</small></strong>
								</div>
							</div>
							
						</div>
						<div class="row-fluid house-people-stats">
							<div class="span2">
								
							</div>
							<div class="span3 house-people-steps">
								<span class="leaderboardTooltip" data-toggle="tooltip" data-placement="bottom" title="base on data collected from <?php empty($value['steps']->valid) ? print(0) : print(round($value['steps']->valid, 0)) ?> days">
									<?php empty($value['steps']->score) ? print(0) : print(round($value['steps']->score, 0)) ?> steps
								</span>
							</div>
							<div class="span3 house-people-sleep">
								<span class="leaderboardTooltip" data-toggle="tooltip" data-placement="bottom" title="base on data collected from <?php empty($value['steps']->valid) ? print(0) : print(round($value['steps']->valid, 0)) ?> days">
									<?php empty($value['sleep']->score) ? print(0) : print(round($value['sleep']->score / 60, 1)) ?> hours
								</span>
							</div>
							<div class="span4 house-pepople-badges">
								<?php if (!empty($value['badge'])): ?>
									<?php foreach ($value['badge'] as $badge): ?>
										<img class="challengeTitleTooltip" data-original-title="<?php echo $badge->name.'<br>Earned: '.$badge->count ?>"  src="<?php echo $badge->badge_pic ?>" alt="">
									<?php endforeach ?>
								<?php endif ?>
							</div>

						</div>
					</div>
				</div>
			<?php endforeach ?>				
			<?php endif ?>
		</div>
	</div>
</div>