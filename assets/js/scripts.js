jQuery(document).ready(function($) {

	//post message
	$("#postMessage").click(function(event){
		var msg = $("#messageBox").val();
		console.log(msg);
		if(msg.length<=140&&msg.length>0){
			$.ajax({
				type:'POST',
				url:'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/home/postMessage',
				dataType:'json',
				data:{
					message: msg
				}
			}).done(function(msg){
				if(msg.success==true){
					$("#newsFeed").prepend('<li class="media"><a href="#" title="" class="pull-left post-profile"><img src="'+msg.posts.profile_pic+'" alt=""></a><div class="media-body"><p class="media-heading"><strong>'+msg.posts.username+'</strong></p><p>'+$('<div/>').text(msg.posts.description).html()+'</p><p><small><span data-livestamp="'+ (msg.posts.time-10) +'"></span></small></p></div></li>');
					$("#messageBox").val("");
				}else{
					$("#alertContainer").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong>Your message is not posted.</div>');
				}
			});
		}
	});

	//check word limit
	$("#messageBox").keyup(function(event){
		var msgbox = event.target;
		limitText(msgbox, $("#postWordCount"), 140);
	});

	//joinevents
	$('.joinbtn').click(function(event){
		var eventId = $(this).parent().parent().data("eventId");
		if(eventId){
			$.ajax({
				type:'POST',
				url:'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/joinEvent',
				dataType:'json',
				data:{
					event_id:eventId
				}
			}).done(function(msg){
				if(msg.success){
					alert("success");
				}else{
					alert("not joined");
				}
			});
		}
	});

	$('#allChallenges').on("click", "#recentWorkouts",function(event){
		event.preventDefault();
		$("#workouts").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/recentWorkouts");
	});
	$('#allChallenges').on("click", "#popularWorkouts",  function(event){
		event.preventDefault();
		$("#workouts").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/popularWorkouts");
	});
	$('#allChallenges').on("click", "#recentEvents", function(event){
		event.preventDefault();
		$("#events").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/recentEvents");
	});
	$('#allChallenges').on("click", "#popularEvents", function(event){
		event.preventDefault();
		$("#events").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/popularEvents");
	});
	$('#myChallenges').on("click", "#recentWorkouts",function(event){
		event.preventDefault();
		$("#workouts").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/myRecentWorkouts");
	});
	$('#myChallenges').on("click", "#popularWorkouts",  function(event){
		event.preventDefault();
		$("#workouts").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/myPopularWorkouts");
	});
	$('#myChallenges').on("click", "#recentEvents", function(event){
		event.preventDefault();
		$("#events").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/myRecentEvents");
	});
	$('#myChallenges').on("click", "#popularEvents", function(event){
		event.preventDefault();
		$("#events").load("http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/myPopularEvents");
	});


	$('.datepicker').datepicker();

	$('.timepicker-default').timepicker();

	$('#createEvent').click(function(event){
		event.preventDefault();
		$.ajax({
			type:'POST',
			url:'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/newChallenge',
			data:{
				title: $('#title').val(),
				description: $('#description').val(),
				date: $('#date').data('date'),
				time: $('#time').val(),
				frequency: $('#frequency').val(),
				location: $('#location').val()
			}
		});
	});

});

//limit text
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.html(limitNum - limitField.value.length);
	}
}