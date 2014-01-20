<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
		<ul class="nav nav-tabs">
			<?php for($i=1; $i<=10; $i++): ?>
			<li class="<?php if($house_id==$i) echo "active" ?>"><a href="<?php echo base_url() . "allhouse/house/".$i; ?>"><?php echo $i ?></a></li>
		<?php endfor ?>
		<li class="<?php if($house_id==-1) echo "active" ?>"><a href="<?php echo base_url() . "allhouse/house/-1"; ?>">Tutor</a></li>

	</ul>
	<?php if ($house_id!=-1): ?>
	<p class="section-title">House Stats</p>
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
			<div class="house-people-pic">
				<img src="<?php if(!empty($value['profile'])) echo $value['profile']->profile_pic ?>" alt="">
			</div>
			<div class="house-people-content">

				<div class="house-people-name">
					<div class="row-fluid">
						<div class="span2">
							<?php if (!empty($value['profile']): ?>
								<a href="<?php echo base_url() . "stats/history/steps/week/" . $value['profile']->id; ?>">
							<?php else: ?>
								<a href="#">
							<?php endif ?>
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
						<?php if(!empty($value['profile']) && $value['profile']->access == 0): ?>
							<small class="muted">No access</small>
						<?php endif ?>
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