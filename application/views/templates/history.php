<div id="chartContainer" class="row-fluid" style="height: 400px"></div>
<div class="row-fluid">
	<div class="span4">
		<ul id="activitybtns" class="nav nav-pills">
			<li class="<?php if($activeActivity=='steps') echo 'active' ?>">
				<a href="<?php echo base_url() . 'stats/history' ?>">steps</a>
			</li>
			<li class="<?php if($activeActivity=='floors') echo 'active' ?>"><a href="<?php echo base_url() . 'stats/history/floors' ?>">floors</a></li>
			<li class="<?php if($activeActivity=='calories') echo 'active' ?>"><a href="<?php echo base_url() . 'stats/history/calories' ?>">calories</a></li>
		</ul>
	</div>
	<div class="span4 offset4">
		<ul class="nav nav-pills">
			<li class="<?php if($span=='week') echo 'active' ?>">
				<a id="weekbtn" href="#">Week</a>
			</li>
			<li class="<?php if($span=='month') echo 'active' ?>">
				<a id="monthbtn" href="#">Month</a>
			</li>
		</ul>
	</div>
</div>
<script>

var stepsChart; // globally available

function getTimestamp(str) {
  var d = str.match(/\d+/g); // extract date parts
  return +new Date(d[0], d[1] - 1, d[2]); // build Date object
}

jQuery(document).ready(function($) {
	var activeActivity = $("#activitybtns .active a").text();
	$("#weekbtn").click(function(e) {
		e.preventDefault();
		if (activeActivity) {
			window.location.href = "<?php echo base_url() . 'stats/history/' ?>" + activeActivity + "/week";
		};
	});

	$("#monthbtn").click(function(e) {
		e.preventDefault();
		if (activeActivity) {
			window.location.href = "<?php echo base_url() . 'stats/history/' ?>" + activeActivity + "/month";
		};
	});
	stepsChart = new Highcharts.Chart({
		credits:{
			enabled:false
		},
		chart: {
			renderTo: 'chartContainer',
			type: 'column'
		},
		title: {
			text: '<?php echo $chartTitle ?>'
		},
		xAxis: {
			type:'datetime',
			title:{
				text:null
			}
		},
		yAxis: {
			title: {
				text: 'Total <?php echo $chartTitle ?>'
			},
			min: 0
		},
		legend:{
			enabled: false
		},
		plotOptions:{
			column:{
				pointInterval:24 * 3600 * 1000,
				pointStart: getTimestamp('<?php echo $startDate ?>')
			}
		},
		series: [{
			type:'column',
			name:'Step counter',
			data:[<?php echo implode(",", $currentActivity) ?>]
		}]
	});
});
</script>