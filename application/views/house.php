<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="span10">
	<p class="section-title">My House</p>
							<table class="table house-stats">
								<tbody>
									<td><img src="<?php echo $rank['picture']; ?>" alt=""></td>
									<td><span class="house-name"><?php echo $rank['house_name']; ?></span><br>House No.</td>
									<td><span class="house-rank"><?php echo $rank['rank']; ?></span><br>Rank</td>
									<td><span class="house-points"><?php echo $rank['points']; ?></span><br>Points</td>
								</tbody>
							</table>
	<p class="section-title">House Members</p>
		<div class="row-fluid myhouse">
			<?php if (!empty($data)): ?>
			<?php foreach ($data as $value): ?>
				<div class="house-people clearfix">
					<div class="house-people-pic">
						<img src="<?php if(!empty($value['completed'])) echo $value['completed']->avatar ?>" alt="">
					</div>
					<div class="house-people-content">
						
						<div class="house-people-name">
							<div class="row-fluid">
								<div class="span2">
									<a href="#">
								<strong>
									<?php if(!empty($value['completed'])) echo $value['completed']->firstname . ' ' . $value['completed']->lastname; ?>
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
								<small class="muted">completed <?php if(!empty($value['completed'])) echo $value['completed']->score ?> challenges</small>
							</div>
							<div class="span3 house-people-current">
								<?php if (!empty($value['current'])): ?>
									<?php foreach ($value['current'] as $current): ?>
									<img src="<?php echo $current->badge_pic ?>" alt="">
									<?php endforeach ?>
								<?php endif ?>
							</div>
							<div class="span3 house-people-tomorrow">
								<?php if (!empty($value['tomorrow'])): ?>
									<?php foreach ($value['tomorrow'] as $tomorrow): ?>
									<img src="<?php echo $current->badge_pic ?>" alt="">
									<?php endforeach ?>
								<?php endif ?>
							</div>
							<div class="span4 house-pepople-badges">
								<?php if (!empty($value['badge'])): ?>
									<?php foreach ($value['badge'] as $badge): ?>
										<img src="<?php echo $badge->badge_pic ?>" alt="">
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