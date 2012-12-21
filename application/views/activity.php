<ul class="nav nav-tabs">
	<li class="<?php if($activeTab==1) echo 'active' ?>">
		<a href="<?php echo base_url() . 'index.php/activity'?>" title="">Steps</a>
	</li>
	<li class="<?php if($activeTab==2) echo 'active' ?>">
		<a href="<?php echo base_url() . 'index.php/activity/floors'?>" title="">Floors</a>
	</li>
	<li class="<?php if($activeTab==3) echo 'active' ?>">
		<a href="<?php echo base_url() . 'index.php/activity/distance'?>" title="">Distance</a>
	</li>
	<li class="<?php if($activeTab==4) echo 'active' ?>">
		<a href="<?php echo base_url() . 'index.php/activity/calories'?>" title="">Calories</a>
	</li>
	<li class="<?php if($activeTab==5) echo 'active' ?>">
		<a href="<?php echo base_url() . 'index.php/activity/activeScore'?>" title="">Active Score</a>
	</li>
</ul>
<div id="container" class="row-fluid" style="height: 400px">
	
</div>
<script type="text/javascript">
var stepsChart; // globally available

function getTimestamp(str) {
  var d = str.match(/\d+/g); // extract date parts
  return +new Date(d[0], d[1] - 1, d[2]); // build Date object
}

$(document).ready(function() {
      stepsChart = new Highcharts.Chart({
      	credits:{
      		enabled:false
      	},
         chart: {
            renderTo: 'container',
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
         		pointStart: getTimestamp('2012-12-01')
         	}
         },
         series: [{
         	type:'column',
         	name:'Step counter',
         	data:[<?php echo implode(",", $stepsData) ?>]
         }]
      });
   });
</script>