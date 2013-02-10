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
				console.log(msg);
				if(msg.success){

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

});

//limit text
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.html(limitNum - limitField.value.length);
	}
}