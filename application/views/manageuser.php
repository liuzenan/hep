<div class="row-fluid">
	<?php $this->load->view('templates/sidebar'); ?>
	<div class="row-fluid">
	<div class="span4 offset2">
		<form action="" id="userinfo">
			<fieldset>
				<label for="firstname">First Name</label>
				<input type="text" name="firstname" id="firstname" value="<?php echo $user->first_name; ?>">
				<label for="lastname">Last Name</label>
				<input type="text" name="lastname" id="lastname" value="<?php echo $user->last_name; ?>">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="<?php echo $user->email; ?>">
				<label for="gender">Gender</label>
				<select name="gender" id="gender">
					<option value="NA" <?php if(!strcmp($user->gender, "NA")) echo "selected"; ?>>NA</option>
					<option value="MALE" <?php if(!strcmp($user->gender, "MALE")) echo "selected"; ?>>MALE</option>
					<option value="FEMALE" <?php if(!strcmp($user->gender, "FEMALE")) echo "selected"; ?>>FEMALE</option>
				</select>
				<label for="house">House</label>
				<select name="house" id="house">
							<option value="1" <?php if($user->house_id==1) echo "selected"; ?>>1</option>
							<option value="2" <?php if($user->house_id==2) echo "selected"; ?>>2</option>
							<option value="3" <?php if($user->house_id==3) echo "selected"; ?>>3</option>
							<option value="4" <?php if($user->house_id==4) echo "selected"; ?>>4</option>
							<option value="5" <?php if($user->house_id==5) echo "selected"; ?>>5</option>
							<option value="6" <?php if($user->house_id==6) echo "selected"; ?>>6</option>
							<option value="7" <?php if($user->house_id==7) echo "selected"; ?>>7</option>
							<option value="8" <?php if($user->house_id==8) echo "selected"; ?>>8</option>
							<option value="9" <?php if($user->house_id==9) echo "selected"; ?>>9</option>
							<option value="10" <?php if($user->house_id==10) echo "selected"; ?>>10</option>
							<option value="-1" <?php if($user->house_id==-1) echo "selected"; ?>>Tutor</option>
				</select>
				<button id="submitbtn" class="btn btn-large btn-block">Update</button>
			</fieldset>
		</form>
	</div>
</div>
</div>

<script src="/assets/js/bootstrap.min.js"></script>
<script>
jQuery(document).ready(function($) {
	$("#submitbtn").click(function(event){
		event.preventDefault();
		if($("#firstname").val() != '' 
			&& $("#lastname").val() != '' 
			&& $("#email").val()!='' 
			&& $("#gender").val()!=''
			&& $("#house").val() != 0
			) {
			console.log("valid");
			console.log($("#userinfo").serialize());
			
			$.post("<?php echo base_url() . 'manage/update' ?>", $("#userinfo").serialize(), function(msg){
				console.log(msg);
			});
		} else {
			alert("Please fill all the infomation");
		}
	
	});
});

</script>