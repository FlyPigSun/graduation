/**
  *学生好友圈
  *author: 孙骥
 **/
activityCircle.student.friends = {
	initialize : function(){
		var me = this;
		$('.activitycircle-addfriends-area a').on('click',me.addFriends);
		$('.activitycircle-friends-delete-btn').on('click',me.removeFriends);
	},
	addFriends : function(){
		var realname = $('.activitycircle-addfriends-area input').val();
		$.ajax({
			url : '/student/addFriends',
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
					activityCircle.studentIndexPage.setFriendsHtml();
				}else if(res.errcode == 102){
					alert('该用户已经是您的好友')
				}else{
					alert('用户不存在')
				}
			}
		})
	},
	removeFriends : function(){
		var fid = $(this).siblings('.friend-id').html();
		$.ajax({
			url : '/student/deleteFriends',
			type : 'post',
			data : {
				fid : fid
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('删除成功');
					activityCircle.studentIndexPage.setFriendsHtml();
				}else{
					alert('删除失败');
				}
			}
		})
	}
}