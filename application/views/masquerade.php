<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(function() {

		$( "#autocomplete" ).autocomplete({
			source: function(request, response) {
				$.ajax({ 
					url: '<?php echo base_url(); ?>masquerade/suggestions',
					data: { term: $("#autocomplete").val()},
					dataType: "json",
					type: "POST",
					success: function(data){
						response(data);
					}
				});
			},
			minLength: 2
		});

		$("#masquerade").click(function(e){
			var name = $("#autocomplete").val();
			console.log(name);
			if(name.trim().length>0) {
				$.ajax({ 
					url: '<?php echo base_url(); ?>masquerade/switchUser',
					data: { term: name},
					dataType: "json",
					type: "POST",
					success: function(data){
						console.log("response"+data);
						window.location.reload();
					}
				});
			}
		});
		$("#switchback").click(function(e){
			$.ajax({ 
					url: '<?php echo base_url(); ?>masquerade/switchBack',
					data: { term: name},
					dataType: "json",
					type: "POST",
					success: function(data){
						console.log("response"+data);
						window.location.reload();
					}
				});
		});
	});
});
</script>
<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
<div class="row-fluid">

	Name: <input type="text" id="autocomplete" />
	<button id="masquerade" class="btn btn-primary">Masquerade</button>
	<button id="switchback" class="btn btn-primary">Switch Back</button>

</div>
</div>