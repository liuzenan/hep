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
				}
			});
		}
	});

	$('.challengeTitleTooltip').tooltip();

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
				alert(msg.message);
				window.location.reload();
			});
		}
	});
	$(".joinChallengeTomorrow").click(function(event){
		var currentBtn = $(this);
		var challengeId = currentBtn.data("challengeId");
		var userId = currentBtn.data("userId");
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
				alert(msg.message);
				window.location.reload();
			});
		}
	});

	$(".quitChallenge").click(function(event){
		var currentBtn = $(this);
		var cpId = currentBtn.data("cpId");
		var quit = confirm("Are you sure to quit this challenge? All your progress so far will be dropped.");
	
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

	$(".subscribe-link").click(function(event){
		var currentBtn = $(this);
		var threadId = currentBtn.data("threadId");
		console.log("threadId");
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

});

//limit text
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.html(limitNum - limitField.value.length);
	}
}