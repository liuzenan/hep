var base_url = 'http://hep.d2.comp.nus.edu.sg/';
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

	$('.challengeTitleTooltip').tooltip();

	$('.my-badges').tooltip();

	$("#newThread").click(function(e){
		console.log("newThread");
		e.preventDefault();
		var data = $("#description").val();
		console.log(data);


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
		
	});

	//check word limit
	$(".messageBox").keyup(function(event){
		var msgbox = event.target;
		var threadId = $(this).data("threadId");
		limitText(msgbox, $(".btncontainer[data-thread-id="+ threadId +"] .postWordCount"), 800);
	});

	$(".messageBox").autosize();
	//joinevents
	$(".joinChallengeNow").click(function(event){
		var currentBtn = $(this);
		var challengeId = currentBtn.data("challengeId");
		var userId = currentBtn.data("userId");
		$(".joinChallengeNow").attr("disabled", true);
		if(challengeId){
			$.ajax({
				type:'POST',
				url:base_url+'challenges/joinChallengeNow',
				dataType:'json',
				data:{
					challenge_id:challengeId,
					user_id:userId
				}
			}).done(function(msg){
				//alert(msg.message);
				//window.location.reload();
				console.log(msg);
				$(".myactivity .today").append('<a href="#challengeToday" role="button" data-toggle="modal"><div class="challengeItem box"><div class="challengeContainer"><div class="challengeTitle challengeTitleTooltip" data-original-title="'+ msg.challenge.description +'">'+ msg.challenge.title +'<h4>'+ msg.challenge.points +' points · <i class="icon-time icon-large"></i>&nbsp;'+ msg.challenge.start_time.substring(0, 5) +'-'+ msg.challenge.end_time.substring(0, 5) +'</h4><div class="progress progress-warning progress-striped"><div class="bar" style="width:0%"></div></div></div></div></div></a>');

			});
		}
	});
	$(".joinChallengeTomorrow").click(function(event){
		var currentBtn = $(this);
		var challengeId = currentBtn.data("challengeId");
		var userId = currentBtn.data("userId");
		$(".joinChallengeTomorrow").attr("disabled", true);
		if(challengeId){
			$.ajax({
				type:'POST',
				url:base_url+'challenges/joinChallengeTomorrow',
				dataType:'json',
				data:{
					challenge_id:challengeId,
					user_id:userId
				}
			}).done(function(msg){
				console.log(msg);
				//alert(msg.message);
				//window.location.reload();
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

	$(".subscribe-link").click(function(event){
		var currentBtn = $(this);
		var threadId = currentBtn.data("threadId");
		console.log("threadId");
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

		if (current.text()!="Show less comments") {
			current.text("Show less comments");
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

});

//limit text
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.html(limitNum - limitField.value.length);
	}
}