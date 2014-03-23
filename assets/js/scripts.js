var base_url = 'http://hep.d2.comp.nus.edu.sg/';
var game_start_date = new Date(2013, 1, 28, 0);
var today = new Date();

jQuery(document).ready(function($) {

	$(".example").popover({
		animation: false,
		trigger: 'hover'
	});

	$("#sendReminder").click(function(event){
		if (($(this)).hasClass('disabled')) {
			return;
		}
		var emailMsg = $("#mailMsg").val();
		var title = $("#mailTitle").val();
		($(this)).addClass('disabled');
		($(this)).text('Sending... Do not close this page');
		var that = $(this);
		$("#sending-indicator").show();
		$.ajax({
			type:"POST",
			url:base_url+'sendmail/sendReminder',
			dataType:'json',
			data:{
				msg: emailMsg,
				title: title
			}
		}).done(function(msg){
			$("#sending-indicator").hide();
			if (msg.success) {
				alert("Email message sent!");
				that.text('Sent!');
				window.location.reload();
			} else {
				alert("Something went wrong...");
			}
		});
	});

	$("#sendMail").click(function(event){
		if (($(this)).hasClass('disabled')) {
			return;
		}
		var emailMsg = $("#mailMsg").val();
		var title = $("#mailTitle").val();
		($(this)).addClass('disabled');
		($(this)).text('Sending... Do not close this page');
		$("#sending-indicator").show();
		var that = $(this);
		$.ajax({
			type:"POST",
			url:base_url+'sendmail/sendMailMessage',
			dataType:'json',
			data:{
				msg: emailMsg,
				title: title
			}
		}).done(function(msg){
			if (msg.success) {
				alert("Email message sent!");
				that.text('Sent!');
				$("#sending-indicator").hide();
				window.location.reload();
			} else {
				alert("Something went wrong...");
			}
		});
	});

	$("#survey_form").submit(function(event){

		
	});

	$("#updateMail").click(function(event){
		var emailMsg = $("#mailMsg").val();
		$.ajax({
			type:"POST",
			url:base_url+'editmail/updateEmailMessage',
			dataType:'json',
			data:{
				msg: emailMsg,
				date: $("#emailDate").val()
			}
		}).done(function(msg){
			if (msg.success) {
				alert("Email message has been updated");
				window.location.reload();
			} else {
				alert("Something went wrong...");
			}
		});
	});

	$(".emailedit").click(function(event){
		event.preventDefault();
		var currentBtn = $(this);

		var editdate = currentBtn.data("date");
		var contentItem = $("#content"+currentBtn.data("msgId"));
		var content = $("#content"+currentBtn.data("msgId")).text();
		console.log(contentItem);

		$("#emailDate").val(editdate);
		$("#mailMsg").val(content);
	});

	$(".emaildelete").click(function(event){
		event.preventDefault();
		var currentBtn = $(this);

		var editdate = currentBtn.data("date");
		$.ajax({
			type:"POST",
			url:base_url+'editmail/deleteEmailMessage',
			dataType:'json',
			data:{
				date: editdate
			}
		}).done(function(msg){
			if (msg.success) {
				alert("Email message has been deleted");
				window.location.reload();
			} else {
				alert("Something went wrong...");
			}
		});
	});

	$(".date-picker").datepicker();
	$(".refresh-data.enabled").on("click",function(event){
		console.log("refesh");
		var currentBtn = $(this);
		if (currentBtn.hasClass("enabled")) {
			currentBtn.removeClass("enabled");
			currentBtn.addClass("icon-spin");
			
			$.ajax({
				type:'GET',
				url:base_url+'subscriber/refresh',
				dataType:'json'
			}).done(function(message){
				if (message.success) {
					alert("All data updated successfully");
				} else {
					alert("Data cannot be updated.");
				}
				currentBtn.removeClass("icon-spin");
				currentBtn.addClass("enabled");
				currentBtn.removeAttr("disabled");
				location.reload();
			});
		} else {
			event.preventDefault();
		}

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
	$('.challengeTitleTooltip').tooltip({html:true});

	$('.my-badges').tooltip({html: true});

	$('.leaderboardTooltip').tooltip();

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
	
	$('#verifydata').click(function(e) {
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
		// backdrop:false
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

});



//limit text
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.html(limitNum - limitField.value.length);
	}
}