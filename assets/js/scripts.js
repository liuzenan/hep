jQuery(document).ready(function($) {

	$(".example").popover({
		animation: false,
		trigger: 'hover'
	});
	//post message
	$("#postMessage").click(function(event){
		var msg = $("#messageBox").val();
		console.log(msg);
		if(msg.length<=800&&msg.length>0){
			$.ajax({
				type:'POST',
				url:'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/postMessage',
				dataType:'json',
				data:{
					thread_id: $("#postMessage").data("threadId"),
					comment: msg
				}
			}).done(function(msg){
				if(msg.success==true){
					window.location.reload();
				}else{
					$("#alertContainer").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong>Your message is not posted.</div>');
				}
			});
		}
	});

	$("#newThread").click(function(e){
		e.preventDefault();
		var data = $("#newThreadForm").serializeArray();
		data.push({name: 'topic_id', value: $("#newThreadForm").data("topicId")});
		$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/createThread', data, function(msg){
			if (msg.success == true) {
				window.location.href = 'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/discussion/' + msg.thread_id;
			} else {
				if (msg.login == true) {
				}else {
					window.location.href = "http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/login"
				};
			}
		}, 'json');
	});

	$("#postComment").click(function(event){
		var msg = $("#messageBox").val();
		console.log(msg);
		if(msg.length<=800&&msg.length>0){
			$.ajax({
				type:'POST',
				url:'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges/postComment',
				dataType:'json',
				data:{
					message: msg,
					event_id: $('#postComment').data('id')
				}
			}).done(function(msg){
				if(msg.success==true){
					//window.location.reload();
				}else{
					$("#alertContainer").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong>Your message is not posted.</div>');
				}
			});
		}
	});

	//check word limit
	$("#messageBox").keyup(function(event){
		var msgbox = event.target;
		limitText(msgbox, $("#postWordCount"), 800);
	});

	//joinevents
	$('.joinbtn').click(function(event){
		var eventId = $(this).data("id");
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
					window.location.reload();
				}else{
					alert("There's an error, we are working to fix it. Sorry!");
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
			dataType:'json',
			data:{
				title: $('#title').val(),
				description: $('#description').val(),
				date: $('#date').data('date'),
				time: $('#time').val(),
				frequency: $('#frequency').val(),
				location: $('#location').val(),
				level: $('#level').val()
			}
		}).done(function(msg){
			if(msg.success){
				alert("event successfully created");
				window.location.replace('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges');
			}else{
				alert("event not created, please check your inputs");
			}
		});
	});

	$('#cancelEvent').click(function(event){
		event.preventDefault();
		window.location.replace('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/challenges');
	});


	$('.thread-post .vote-button').click(function(event){
		event.preventDefault();
		var clicked = $(this);
		var action = clicked.data("actionValue");
		var thread_id = clicked.parent().parent().data('threadId');
		if (action=="up") {
			$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/upvote',{thread_id: thread_id}, function(data){
				if (data.success) {
					$('.thread-post .vote-button').removeClass("vote-enabled").addClass("vote-disabled");
					$('.thread-post .vote-button').unbind('click');
					$('.thread-post .vote-count').text(parseInt($('.thread-post .vote-count').text())+1);
				};
		}, "json");
		} else {
			$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/downvote',{thread_id: thread_id}, function(data){
				if (data.success) {
					$('.thread-post .vote-button').removeClass("vote-enabled").addClass("vote-disabled");
					$('.thread-post .vote-button').unbind('click');
					$('.thread-post .vote-count').text(parseInt($('.thread-post .vote-count').text())-1);
				};
		}, "json");
		
		};
	});

	$('.thread-reply .vote-button').click(function(event){
		event.preventDefault();
		var clicked = $(this);
		var action = clicked.data("actionValue");
		var post_id = clicked.parent().parent().data('postId');
		var post_control = clicked.parent().children(".vote-button");
		var vote_count = clicked.parent().children(".vote-count");
		if (action=="up") {
			$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/upvote',{post_id: post_id}, function(data){
				if (data.success) {
					post_control.removeClass("vote-enabled").addClass("vote-disabled");
					post_control.unbind('click');
					vote_count.text(parseInt($('.thread-reply .vote-count').text())+1);
				};
		}, "json");
		} else {
			$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/downvote', {post_id: post_id}, function(data){
				if (data.success) {
					post_control.removeClass("vote-enabled").addClass("vote-disabled");
					post_control.unbind('click');
					vote_count.text(parseInt($('.thread-reply .vote-count').text())-1);
				};
		}, "json");
		
		};		
	});

	$('.thread-post .flag-button').click(function(event){
		event.preventDefault();
		var clicked = $(this);
		var thread_id = clicked.parent().parent().parent().data('threadId');

		$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/markspam',{thread_id: thread_id}, function(data){
				if (data.success) {
					clicked.removeClass("flag-enabled").addClass("flag-disabled");
					clicked.unbind('click');
				};
		}, "json");
	});

	$('.thread-reply .flag-button').click(function(event){
		event.preventDefault();
		var clicked = $(this);
		var post_id = clicked.parent().parent().parent().data('postId');

		$.post('http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/forum/markspam',{post_id: post_id}, function(data){
				if (data.success) {
					clicked.removeClass("flag-enabled").addClass("flag-disabled");
					clicked.unbind('click');
				};
		}, "json");
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