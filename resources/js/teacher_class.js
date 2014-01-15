/**
  *学生好友圈
  *author: 孙骥
 **/
activityCircle.teacher.class = {
	initialize : function(){
		var me = this;
		$('.activitycircle-addfriends-area a').unbind();
		$('.activitycircle-friends-delete-btn').unbind();
		$('.index-background').unbind();
		$('.index-background').on('click',me.hideMessageBox);
		$('.activitycircle-friends-box').find('.activitycircle-friends-box-avatar').on('click',me.showMessageBox);
		$('.activitycircle-addfriends-area a').on('click',me.addFriends);
		$('.activitycircle-friends-delete-btn').on('click',me.removeFriends);
	},
	addFriends : function(){
		var realname = $('.activitycircle-addfriends-area input').val();
		$.ajax({
			url : '/teacher/addStudents',
			type : 'post',
			data : {
				realname : realname
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('好友添加成功');
					activityCircle.teacherIndexPage.setClassHtml();
				}else if(res.errcode == 102){
					alert('该用户已经是您的好友')
				}else{
					alert('用户不存在')
				}
			}
		})
	},
	removeFriends : function(){
		var sid = $(this).siblings('.sid').html();
		$.ajax({
			url : '/teacher/deleteStudents',
			type : 'post',
			data : {
				sid : sid
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('删除成功');
					activityCircle.teacherIndexPage.setClassHtml();
				}else{
					alert('删除失败');
				}
			}
		})
	},
	showMessageBox : function(){
		var to_id = $(this).siblings('.friend-id').html();
		var friends_name = $(this).siblings('.activitycircle-friends-name').html();
		$('.activitycircle-friends-message-send-friend-name').html(friends_name);
        $('.activitycircle-friends-message-send-box').animate({top:$(document).scrollTop()+150+'px'});
        $('.index-background').fadeIn();
        $('.activitycircle-friends-message-send-btn').one('click',function(){activityCircle.teacher.class.sendMessage(to_id)});
	},
	sendMessage : function(to_id){
		var title = encodeURIComponent($('.activitycircle-friends-message-title').val());
		var content = encodeURIComponent($('.activitycircle-friends-message-send-textarea').val());
		$.ajax({
			url : '/student/sendLetters',
			type : 'post',
			data : {
				to_id : to_id,
				title : title,
				content : content
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('发送成功');
					activityCircle.teacher.class.hideMessageBox();
				}else{
					alert('发送失败');
					activityCircle.teacher.class.hideMessageBox();
				}
			}
		});
	},
	hideMessageBox : function(){
		$('.activitycircle-friends-message-send-box').animate({top:"-400px"});
        $('.index-background').fadeOut();
        $('.activitycircle-friends-message-send-btn').unbind();
        $('.activitycircle-friends-message-title').val('');
        $('.activitycircle-friends-message-send-textarea').val('');
	}
}