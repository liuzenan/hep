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
			<td><img src="<?php echo $rank['picture']; ?>" alt=""></td>
			<td><span class="house-name"><?php echo $rank['house_name']; ?></span><br>House No.</td>
			<td><span class="house-rank"><?php echo $rank['rank']; ?></span><br>Rank</td>
			<td><span class="house-points"><?php echo $rank['points']; ?></span><br>Points</td>
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
							<a href="#">
								<strong>
									<?php if(!empty($value['profile'])) echo $value['profile']->first_name . ' ' . $value['profile']->last_name; ?>
								</strong>
							</a>
						</div>
						<div class="span3">
							<strong class="muted">
								<small>Today's Challenges</small>
							</strong>
						</div>
						<div class="span3">
							<strong class="muted">
								<small>Tomorrow's Challenges</small>

							</strong>
						</div>
						<div class="span4">
							<strong class="muted"><small>Badges</small></strong>
						</div>
					</div>

				</div>
				<div class="row-fluid house-people-stats">
					<div class="span2">
						<small class="muted">completed <?php echo empty($value['completed']) ? 0 : $value['completed']->score ?> challenges</small>
					</div>
					<div class="span3 house-people-current">
						<?php if (!empty($value['current'])): ?>
						<?php foreach ($value['current'] as $current): ?>
						<img class="challengeTitleTooltip" data-original-title="<?php echo $current->title ?>" src="<?php echo $current->badge_pic ?>" alt="">
					<?php endforeach ?>
				<?php endif ?>
			</div>
			<div class="span3 house-people-tomorrow">
				<?php if (!empty($value['tomorrow'])): ?>
				<?php foreach ($value['tomorrow'] as $tomorrow): ?>
				<img class="challengeTitleTooltip" data-original-title="<?php echo $tomorrow->title ?>" src="<?php echo $tomorrow->badge_pic ?>" alt="">
			<?php endforeach ?>
		<?php endif ?>
	</div>
	<div class="span4 house-pepople-badges">
		<?php if (!empty($value['badge'])): ?>
		<?php foreach ($value['badge'] as $badge): ?>
		<img class="challengeTitleTooltip" data-original-title="<?php echo $badge->name ?>"  src="<?php echo $badge->badge_pic ?>" alt="">
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