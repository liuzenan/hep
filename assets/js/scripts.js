var base_url = 'http://ec2-54-251-40-149.ap-southeast-1.compute.amazonaws.com/fitbit/';
jQuery(document).ready(function($) {

	$(".example").popover({
		animation: false,
		trigger: 'hover'
	});
	//post message
	$(".postMessage").click(function(event){
		var currentBtn = $(this);
		var threadId = currentBtn.data("threadId");
		var msg = $("#messageBox"+threadId).val();
		console.log(msg);
		if(msg.length<=800&&msg.length>0){
			$.ajax({
				type:'POST',
				url:base_url+'forum/postMessage',
				dataType:'json',
				data:{
					thread_id: threadId,
					comment: msg
				}
			}).done(function(msg){
				if(msg.success==true){
					window.location.reload();
				}else{
					$("#alertContainer"+currentBtn).html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong>Your message is not posted.</div>');
				}
			});
		}
	});

	$("#newThread").click(function(e){
		e.preventDefault();
		var data = $("#newThreadForm").serializeArray();
		data.push({name: 'topic_id', value: $("#newThreadForm").data("topicId")});
		$.post(base_url+'forum/createThread', data, function(msg){
			if (msg.success == true) {
				window.location.href = base_url+'forum/discussion/' + msg.thread_id;
			} else {
				if (msg.login == true) {
				}else {
					window.location.href = base_url+"login"
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
				url:base_url+'challenges/postComment',
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
	$(".messageBox").keyup(function(event){
		var msgbox = event.target;
		var threadId = $(this).data("threadId");
		limitText(msgbox, $(".btncontainer[data-thread-id="+ threadId +"] .postWordCount"), 800);
	});

	//joinevents
	$('.joinbtn').click(function(event){
		var eventId = $(this).data("id");
		if(eventId){
			$.ajax({
				type:'POST',
				url:base_url+'challenges/joinEvent',
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
		$("#workouts").load(base_url+"challenges/recentWorkouts");
	});
	$('#allChallenges').on("click", "#popularWorkouts",  function(event){
		event.preventDefault();
		$("#workouts").load(base_url+"challenges/popularWorkouts");
	});
	$('#allChallenges').on("click", "#recentEvents", function(event){
		event.preventDefault();
		$("#events").load(base_url+"challenges/recentEvents");
	});
	$('#allChallenges').on("click", "#popularEvents", function(event){
		event.preventDefault();
		$("#events").load(base_url+"challenges/popularEvents");
	});
	$('#myChallenges').on("click", "#recentWorkouts",function(event){
		event.preventDefault();
		$("#workouts").load(base_url+"challenges/myRecentWorkouts");
	});
	$('#myChallenges').on("click", "#popularWorkouts",  function(event){
		event.preventDefault();
		$("#workouts").load(base_url+"challenges/myPopularWorkouts");
	});
	$('#myChallenges').on("click", "#recentEvents", function(event){
		event.preventDefault();
		$("#events").load(base_url+"challenges/myRecentEvents");
	});
	$('#myChallenges').on("click", "#popularEvents", function(event){
		event.preventDefault();
		$("#events").load(base_url+"challenges/myPopularEvents");
	});


	$('.datepicker').datepicker();

	$('.timepicker-default').timepicker();

	$('#createEvent').click(function(event){
		event.preventDefault();
		$.ajax({
			type:'POST',
			url:base_url+'challenges/newChallenge',
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
				window.location.replace(base_url+'challenges');
			}else{
				alert("event not created, please check your inputs");
			}
		});
	});

	$('#cancelEvent').click(function(event){
		event.preventDefault();
		window.location.replace(base_url+'challenges');
	});


	$('.thread-post .vote-button').click(function(event){
		event.preventDefault();
		var clicked = $(this);
		var action = clicked.data("actionValue");
		var thread_id = clicked.parent().parent().data('threadId');
		if (action=="up") {
			$.post(base_url+'forum/upvote',{thread_id: thread_id}, function(data){
				if (data.success) {
					$('.thread-post .vote-button').removeClass("vote-enabled").addClass("vote-disabled");
					$('.thread-post .vote-button').unbind('click');
					$('.thread-post .vote-count').text(parseInt($('.thread-post .vote-count').text())+1);
				};
		}, "json");
		} else {
			$.post(base_url+'forum/downvote',{thread_id: thread_id}, function(data){
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
			$.post(base_url+'forum/upvote',{post_id: post_id}, function(data){
				if (data.success) {
					post_control.removeClass("vote-enabled").addClass("vote-disabled");
					post_control.unbind('click');
					vote_count.text(parseInt($('.thread-reply .vote-count').text())+1);
				};
		}, "json");
		} else {
			$.post(base_url+'forum/downvote', {post_id: post_id}, function(data){
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

		$.post(base_url+'forum/markspam',{thread_id: thread_id}, function(data){
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

		$.post(base_url+'forum/markspam',{post_id: post_id}, function(data){
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