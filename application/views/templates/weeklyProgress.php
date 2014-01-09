<div class="row-fluid" id="weekly-progress">
	<div class="span12">
		<p class="section-title">House Weekly Progress <a href="javascript:void(0);" class="refresh-data pull-right enabled"><i class="icon-refresh icon-large"></i></a></p>
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active" id="stepsBtn"><a href="#tab1" data-toggle="tab">Steps</a></li>
				<li id="sleepBtn"><a href="#tab2" data-toggle="tab">Sleep</a></li>
				<li id="sleepBtn"><a href="<?php echo base_url().'leaderboard/weekly' ?>">Full Leaderboard <i class="icon-external-link"></i></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<div id="stepsChartContainer" class="progressChart" style="height: <?php echo $leaderboardHeight ?>px"></div>
					<?php if (!empty($stepsPrevHouse)): ?>
						<p class='text-center text-info'>
							<strong><?php echo round($stepsPrevHouse->steps) ?></strong> more steps to overtake
							House <?php echo $stepsPrevHouse->house ?>! Go go go!
						</p>
					<?php elseif (empty($stepsTopHouse)): ?>
						<p class='text-center text-info'>
							Your house is in the lead! Keep it up!
						</p>
					<?php endif ?>
				</div>
				<div class="tab-pane" id="tab2">
					<div id="sleepChartContainer" class="progressChart" style="height: <?php echo $leaderboardHeight ?>px"></div>
					<?php if (!empty($sleepPrevHouse)): ?>
						<p class='text-center text-info'>
							Another <strong><?php echo round($sleepPrevHouse->sleep) ?></strong> hours of sleep and you'll overtake
							House <?php echo $sleepPrevHouse->house ?>! Go go go!
						</p>
					<?php elseif (empty($sleepTopHouse)): ?>
						<p class='text-center text-info'>
							Your house is in the lead! Keep it up!
						</p>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>	
</div>

<script>
var stepsChart; // globally available


var sleepChart; // globally available


$(function() {
	var steps_series_data = [];
	var steps_categories = [];
	<?php foreach($stepsLeaderboard as $row): ?>
		steps_categories.push('<?php echo $row->house ?>');
		steps_series_data.push({
			y: <?php echo $row->steps ?>,
			<?php if ($my_house == $row->house_id) {
				echo 'color: \'#BF0B23\'';
			} ?>
		});
	<?php endforeach ?>

	var sleep_series_data = [];
	var sleep_categories = [];
	<?php foreach($sleepLeaderboard as $row): ?>
		sleep_categories.push('<?php echo $row->house ?>');
		sleep_series_data.push({
			y: <?php echo $row->sleep ?>,
			<?php if ($my_house == $row->house_id) {
				echo 'color: \'#BF0B23\'';
			} ?>
		});
	<?php endforeach ?>
	var stepsChartOptions = {
		credits:{
			enabled:false
		},
		chart: {
			renderTo: 'stepsChartContainer',
			type: 'bar'
		},
		title: {
			text: 'Steps Per Person'
		},
		yAxis: {
			type:'linear',
			title:{
				text: 'Steps'
			},
			min: 0,
		},
		xAxis: {
			type: 'category',
			categories: steps_categories,
			title: {
				text: 'House'
			},
			min: 0
		},
		legend:{
			enabled: false
		},
		plotOptions:{
			bar: {
				pointStart: 0,

			}
		},
		tooltip: {
			formatter: function() {
				return 'House ' + this.x + '<br/>' + Highcharts.numberFormat(this.y, 0) + ' steps';
			}
		},
		series: [{
			type:'bar',
			name:'steps',
			data: steps_series_data
		}]
	};

	var sleepChartRendered = false;

	var sleepChartOptions = {
		credits:{
			enabled:false
		},
		chart: {
			renderTo: 'sleepChartContainer',
			type: 'bar'
		},
		title: {
			text: 'Sleep Per Person'
		},
		yAxis: {
			type:'linear',
			title:{
				text: 'Hours'
			},
			min: 0,
		},
		xAxis: {
			type: 'category',
			categories: sleep_categories,
			title: {
				text: 'House'
			},
			min: 0
		},
		legend:{
			enabled: false
		},
		plotOptions:{
			bar: {
				pointStart: 0,

			}
		},
		tooltip: {
			formatter: function() {
				return 'House ' + this.x + '<br/>' + Highcharts.numberFormat(this.y, 1) + ' hours';
			}
		},
		series: [{
			type:'bar',
			name:'steps',
			data: sleep_series_data
		}]
	};

	stepsChart = new Highcharts.Chart(stepsChartOptions);


	$('#weekly-progress #sleepBtn').click(function() {
		if (!sleepChartRendered) {
			window.setTimeout(function() {
				sleepChart = new Highcharts.Chart(sleepChartOptions);
			}, 100);
			//sleepChartRendered = true;
		}
	});

});

</script>