<?php if (!empty($stats_user) && $stats_user->id != $this->session->userdata("user_id")): ?>
	<p class="section-title">Activity history for <?php echo $stats_user->first_name . " " . $stats_user->last_name; ?></p>
<?php endif ?>
<div id="chartContainer" class="row-fluid" style="height: 400px"></div>
<div class="row-fluid">
	<div class="span6">
		<ul id="activitybtns" class="nav nav-pills">
			<li class="<?php if($activeActivity=='steps') echo 'active' ?>">
				<a href="<?php echo base_url() . 'stats/history/steps' ?>">steps</a>
			</li>
			<li class="<?php if($activeActivity=='sleep') echo 'active' ?>"><a href="<?php echo base_url() . 'stats/history/sleep' ?>">sleep</a></li>
			<li class="<?php if($activeActivity=='sedentary') echo 'active' ?>"><a href="<?php echo base_url() . 'stats/history/sedentary' ?>">sedentary</a></li>
		</ul>
	</div>
	<div class="span4 offset2">
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
  return +new Date(d[0], d[1] - 1, d[2], d[3], d[4], d[5]); // build Date object
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
				pointStart: <?php echo strtotime($startDate)*1000; ?> + 24*3600*500
			}
		},
		series: [{
			type:'column',
			name:'<?php echo $chartTitle ?>',
			data:[<?php echo implode(",", $currentActivity) ?>]
		}]
	});
});
</script>