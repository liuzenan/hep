var base_url = 'http://hep.d2.comp.nus.edu.sg/';
var game_start_date = new Date(2013, 1, 28, 0);

jQuery(document).ready(function($) {

	$(".history-challenge-item").popover({
		trigger:'hover'
	});

	$(".example").popover({
		animation: false,
		trigger: 'hover'
	});
	//post message
	$(".postMessage").click(function(event){
		var currentBtn = $(this);
		var threadId = currentBtn.data("threadId");
		var msg = $("#messageBox"+threadId).val().trim();
		$(this).attr("disabled", true);
		if(msg.length<=800&&msg.length>0){
			$.ajax({
				type:'POST',
				url:base_url+'forum/postMessage',
				dataType:'json',
				data:{
					thread_id: threadId,
					comment: msg
				}
			}).done(function(message){
				console.log(message);
				if(message.success){
					window.location.reload();
				}else{
					$("#alertContainer"+currentBtn).html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Error!</strong>Your message is not posted.</div>');
					$(this).attr("disabled", false);
				}
			});
		}
	});
	$(".clearNotification").click(function(e){
		console.log("clearNotification");
		$.ajax({
			type:'POST',
			url:base_url+'forum/clearNotification',
			dataType:'json',
			data:{
			}
		}).done(function(message){
			console.log(message);
			if(message.success){
				window.location.reload();
			}
		});

	});
	$(".deletePost").click(function(e){
		var current = $(this);
		var postId = current.data("postId");
		console.log("post id"+postId);
		$.ajax({
			type:'POST',
			url:base_url+'forum/deletePost',
			dataType:'json',
			data:{
				post_id: postId,
			}
		}).done(function(message){
			console.log(message);
			if(message.success){
				window.location.reload();
			}
		});

	});
	$(".deleteThread").click(function(e){
		var current = $(this);
		var threadId = current.data("threadId");
		console.log("thread id"+threadId);
		$.ajax({
			type:'POST',
			url:base_url+'forum/deleteThread',
			dataType:'json',
			data:{
				thread_id: threadId,
			}
		}).done(function(message){
			console.log(message);
			if(message.success){
				window.location.reload();
			}
		});

	});
	$('.challengeTitleTooltip').tooltip();

	$('.my-badges').tooltip();

	$("#newThread").click(function(e){
		console.log("newThread");
		e.preventDefault();
		var data = $("#description").val().trim();
		console.log(data);

		if(data.length>0) {

			$.post(base_url+'forum/createThread', {message:data}, function(msg){
				console.log(msg);
				if (msg.success == true) {
					window.location.reload();
				} else {
					if (msg.login == true) {
					}else {
						window.location.href = base_url+"login"
					};
				}
			}, 'json');
		}
		
	});
	$('#challengeModal').ready(function() {
		console.log("pull data from fitbit")
		$.ajax({
				type:'POST',
				url:base_url+'subscriber/refresh',
				dataType:'json',
				data:{
				}
			}).done(function(msg){
				console.log(msg);
				window.location.reload();
			});

	});

	//check word limit
	$(".messageBox").keyup(function(event){
		var msgbox = event.target;
		var threadId = $(this).data("threadId");
		limitText(msgbox, $(".btncontainer[data-thread-id="+ threadId +"] .postWordCount"), 800);
	});

	$(".messageBox").autosize();
	

	$(".subscribe-link").click(function(event){
		console.log("here");
		var currentBtn = $(this);
		var threadId = currentBtn.data("threadId");
		console.log("threadId"+threadId);
		$(this).attr("disabled", true);
		if(threadId){
			$.ajax({
				type:'POST',
				url:base_url+'forum/subscribe',
				dataType:'json',
				data:{
					thread_id:threadId,
				}
			}).done(function(msg){
				console.log(msg);
				window.location.reload();
			});
		}
	});
	$(".unsubscribe-link").click(function(event){
		var currentBtn = $(this);
		var threadId = currentBtn.data("threadId");
		console.log("threadId");
		$(this).attr("disabled", true);
		if(threadId){
			$.ajax({
				type:'POST',
				url:base_url+'forum/unsubscribe',
				dataType:'json',
				data:{
					thread_id:threadId,
				}
			}).done(function(msg){
				console.log(msg);
				window.location.reload();
			});
		}
	});

	$('.modal').modal({
		show:false,
		backdrop:false
	});

	$(".thread-content .showmore").click(function(event){
		var current = $(this);

		$(this).parent().parent().children(".full-content").toggleClass("hide");
		$(this).parent().parent().children(".collapsed-content").toggleClass("hide");

		if (current.text()!="Show fewer comments") {
			current.text("Show fewer comments");
		} else {
			current.text("Show all " + current.data("comments") + " comments");
		}
	});

	$(".expandbtn").click(function(event){

		var expandable = $(this).parent().parent().find(".expandable");
		var heights, maxheight;

		if ($(this).children().hasClass("icon-chevron-down")) {
			heights = expandable.children().map(function(){
				return $(this).height();
			}).get();

			maxheight = Math.max.apply(null, heights);
			expandable.height(maxheight);
			$(this).children().removeClass("icon-chevron-down");
			$(this).children().addClass("icon-chevron-up");

		} else {
			$(this).children().removeClass("icon-chevron-up");
			$(this).children().addClass("icon-chevron-down");
			expandable.height(220);
		}

		expandable.toggleClass("expand");

	});

	for (var i = 0; i < 4; i++) {
		$(".today .challenge-type-"+i+" a").bind('click', {type:i}, function(event){
			var data = event.data;
			$("#challengeModal .challenge-wrapper ").html("");
		//console.log(data.type);
		for (var j = allChallenges[data.type].length - 1; j >= 0; j--) {
			var currentChallenge = allChallenges[data.type][j];
			if(currentChallenge.joined_today==1){
				$("#challengeModal .challenge-wrapper").append('<a class="joinChallengeNow" href="javascript:void(0);" data-cpid="'+ currentChallenge.cp_id_today +'" data-user-id="'+ currentChallenge.user_id +'" data-challenge-id="'+ currentChallenge.id +'"><div class="row-fluid"><div class="span1"><i class="icon-ok-sign"></i></div><div class="span4 challenge-modal-title">'+ currentChallenge.title +'</div><div class="span6 challenge-modal-description">'+ currentChallenge.description +'</div><div class="span1 challenge-modal-points">'+ currentChallenge.points +'</div></div></a>');
			} else {
				if (currentChallenge.quota_exceeded==1) {
					$("#challengeModal .challenge-wrapper").append('<a class="joinChallengeNow" href="javascript:void(0);" data-cpid="'+ currentChallenge.cp_id_today +'" data-user-id="'+ currentChallenge.user_id +'" data-challenge-id="'+ currentChallenge.id +'"><div class="row-fluid"><div class="span1"></div><div class="span4 challenge-modal-title">'+ currentChallenge.title +'</div><div class="span6 challenge-modal-description">'+ currentChallenge.description +'</div><div class="span1 challenge-modal-points">'+currentChallenge.points+'</div></div></a>');
				} else {
					$("#challengeModal .challenge-wrapper").append('<a class="joinChallengeNow enabled" href="javascript:void(0);" data-cpid="'+ currentChallenge.cp_id_today +'" data-user-id="'+ currentChallenge.user_id +'" data-challenge-id="'+ currentChallenge.id +'"><div class="row-fluid"><div class="span1"></div><div class="span4 challenge-modal-title">'+ currentChallenge.title +'</div><div class="span6 challenge-modal-description">'+ currentChallenge.description +'</div><div class="span1 challenge-modal-points">'+ currentChallenge.points +'</div></div></a>');
				}
				
			}

		}
	});

$(".tomorrow .challenge-type-"+i+" a").bind('click', {type:i},function(event){
	var data = event.data;
	$("#challengeModal .challenge-wrapper").html("");
	console.log(data.type);
	for (var j = allChallenges[data.type].length - 1; j >= 0; j--) {
		var currentChallenge = allChallenges[data.type][j];
		if(currentChallenge.joined_tomorrow==1){
			$("#challengeModal .challenge-wrapper").append('<a class="joinChallengeTomorrow" href="javascript:void(0);" data-cpid="'+ currentChallenge.cp_id_tomorrow +'" data-user-id="'+ currentChallenge.user_id +'" data-challenge-id="'+ currentChallenge.id+'"><div class="row-fluid"><div class="span1"><i class="icon-ok-sign"></i></div><div class="span4 challenge-modal-title">'+ currentChallenge.title +'</div><div class="span6 challenge-modal-description">'+ currentChallenge.description +'</div><div class="span1 challenge-modal-points">'+ currentChallenge.points +'</div></div></a>');

		} else {
			if (currentChallenge.quota_exceeded==1) {
				$("#challengeModal .challenge-wrapper").append('<a class="joinChallengeTomorrow" href="javascript:void(0);" data-cpid="'+ currentChallenge.cp_id_tomorrow +'" data-user-id="'+ currentChallenge.user_id +'" data-challenge-id="'+ currentChallenge.id+'"><div class="row-fluid"><div class="span1"></div><div class="span4 challenge-modal-title">'+ currentChallenge.title +'</div><div class="span6 challenge-modal-description">'+ currentChallenge.description +'</div><div class="span1 challenge-modal-points">'+ currentChallenge.points +'</div></div></a>');
			} else {
				$("#challengeModal .challenge-wrapper").append('<a class="joinChallengeTomorrow enabled" href="javascript:void(0);" data-cpid="'+ currentChallenge.cp_id_tomorrow +'" data-user-id="'+ currentChallenge.user_id +'" data-challenge-id="'+ currentChallenge.id+'"><div class="row-fluid"><div class="span1"></div><div class="span4 challenge-modal-title">'+ currentChallenge.title +'</div><div class="span6 challenge-modal-description">'+ currentChallenge.description +'</div><div class="span1 challenge-modal-points">'+ currentChallenge.points +'</div></div></a>');
			}
			

		}

	}
});		
};

//joinevents
$(".today").on('click','.joinChallengeNow.enabled',function(event){
	var currentBtn = $(this);
	console.log("joinChallengeNow");
	var challengeId = currentBtn.data("challengeId");
	var userId = currentBtn.data("userId");
	var cpId = currentBtn.data("cpid");
	console.log(challengeId+"-"+userId+"-"+cpId);
	var quit;
	if(cpId>0) {
		quit = confirm("Are you sure to quit this challenge? All your progress so far will be dropped.","HEP Advisor");
	} else {
		quit = 1;
	}

	$(".joinChallengeNow").attr("disabled", true);
	if(challengeId && quit){
		$.ajax({
			type:'POST',
			url:base_url+'challenges/joinChallengeNow',
			dataType:'json',
			data:{
				challenge_id:challengeId,
				user_id:userId,
				cp_id:cpId
			}
		}).done(function(msg){
			console.log("getback");
			console.log(msg);

			$("#challengeModal").hide();
				//alert(msg.message);
				window.location.reload();
				//$(".myactivity .today").append('<a href="#challengeToday" role="button" data-toggle="modal"><div class="challengeItem box"><div class="challengeContainer"><div class="challengeTitle challengeTitleTooltip" data-original-title="'+ msg.challenge.description +'">'+ msg.challenge.title +'<h4>'+ msg.challenge.points +' points · <i class="icon-time icon-large"></i>&nbsp;'+ msg.challenge.start_time.substring(0, 5) +'-'+ msg.challenge.end_time.substring(0, 5) +'</h4><div class="progress progress-warning progress-striped"><div class="bar" style="width:0%"></div></div></div></div></div></a>');

			});
	}
});
$("#challengeModal").on('click',".joinChallengeTomorrow.enabled", function(event){
	var currentBtn = $(this);
	var challengeId = currentBtn.data("challengeId");
	var userId = currentBtn.data("userId");
	var cpId = currentBtn.data("cpid");
	$(".joinChallengeTomorrow").attr("disabled", true);
	if(challengeId){
		$.ajax({
			type:'POST',
			url:base_url+'challenges/joinChallengeTomorrow',
			dataType:'json',
			data:{
				challenge_id:challengeId,
				user_id:userId,
				cp_id:cpId
			}
		}).done(function(msg){
			console.log(msg);
				//alert(msg.message);
				window.location.reload();
			});
	}
});



$(".today .quitChallenge").click(function(event){
	var currentBtn = $(this);
	var cpId = currentBtn.data("cpId");
	var quit = confirm("Are you sure to quit this challenge? All your progress so far will be dropped.","HEP Advisor");
	$(".today .quitChallenge").attr("disabled", true);
	if(quit) {
		if(cpId){
			$.ajax({
				type:'POST',
				url:base_url+'challenges/quitChallenge',
				dataType:'json',
				data:{
					id:cpId,
				}
			}).done(function(msg){
				console.log(msg);
				alert(msg.message);
				window.location.reload();
			});
		}
	}});
$(".tomorrow .quitChallenge").click(function(event){
	var currentBtn = $(this);
	var cpId = currentBtn.data("cpId");	
	$(".tomorrow .quitChallenge").attr("disabled", true);
	if(cpId){
		$.ajax({
			type:'POST',
			url:base_url+'challenges/quitChallenge',
			dataType:'json',
			data:{
				id:cpId,
			}
		}).done(function(msg){
			console.log(msg);
			alert(msg.message);
			window.location.reload();
		});
	}
});
$(".myactivity .current-challenges").mouseenter(function(event){
	var button = $(this).find(".expandbtn");
	var expandable = button.parent().parent().find(".expandable");
	if (button.children().hasClass("icon-chevron-down")) {
		heights = expandable.children().map(function(){
			return $(this).height();
		}).get();

		maxheight = Math.max.apply(null, heights);
		expandable.height(maxheight);
		button.children().removeClass("icon-chevron-down");
		button.children().addClass("icon-chevron-up");

	}

	expandable.addClass("expand");
});

$(".myactivity .current-challenges").mouseleave(function(event){
	var button = $(this).find(".expandbtn");
	var expandable = button.parent().parent().find(".expandable");
	if (button.children().hasClass("icon-chevron-up")) {
		button.children().removeClass("icon-chevron-up");
		button.children().addClass("icon-chevron-down");
		expandable.height(220);
	}

	expandable.removeClass("expand");
});

var counter = setInterval(timer, 1000);
	console.log(game_start_date);
});






function timer(){
	var current = new Date();
	var oneDay = 24*60*60*1000;
	var oneHour = 60*60*1000;
	var oneMinute = 60*1000;
	var oneSecond = 1000;
	$("#count-down-days").text(Math.floor((game_start_date.getTime()-current.getTime())/(oneDay))+" Days ");
	$("#count-down-hours").text(""+Math.floor(((game_start_date.getTime()-current.getTime())%(oneDay))/(oneHour)) + " Hours ");
	$("#count-down-minutes").text(""+Math.floor((((game_start_date.getTime()-current.getTime())%(oneDay))%(oneHour))/(oneMinute)) + " Minutes ");
	$("#count-down-seconds").text(""+Math.floor(((((game_start_date.getTime()-current.getTime())%(oneDay))%(oneHour))%(oneMinute))/(oneSecond)) + " Seconds ");
}

//limit text
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.html(limitNum - limitField.value.length);
	}
}