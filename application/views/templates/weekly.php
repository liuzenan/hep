<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
				<p class="section-title">Steps</p>
				<div class="clearfix">
				<div id="stepsChartContainer" class="row-fluid" style="height: 400px"></div>
		</div>
		<div class="row-fluid">
				<p class="section-title">Sleep</p>
				<div class="clearfix">
				<div id="sleepChartContainer" class="row-fluid" style="height: 400px"></div>
		</div>
		<br/>
		
	</div>
</div>

<script>
var stepsChart; // globally available
var steps_series_data = [];
var steps_categories = [];
<?php foreach($steps as $row): ?>
	steps_categories.push(<?php echo $row->house ?>);
	steps_series_data.push({
		y: <?php echo $row->steps ?>,
		<?php if ($my_house == $row->house_id) {
			echo 'color: \'#BF0B23\'';
		} ?>
	});
<?php endforeach ?>

var sleepChart; // globally available
var sleep_series_data = [];
var sleep_categories = [];
<?php foreach($sleep as $row): ?>
	sleep_categories.push(<?php echo $row->house ?>);
	sleep_series_data.push({
		y: <?php echo $row->sleep ?>,
		<?php if ($my_house == $row->house_id) {
			echo 'color: \'#BF0B23\'';
		} ?>
	});
<?php endforeach ?>

$(function() {
	stepsChart = new Highcharts.Chart({
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
	});

	sleepChart = new Highcharts.Chart({
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
	});
});

</script>