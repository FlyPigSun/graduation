/**
  *学生好友圈
  *author: 孙骥
 **/
activityCircle.student.friends = {
	initialize : function(){
		var me = this;
		$('.activitycircle-addfriends-area a').on('click',me.addFriends);
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
					invokeClick($('.student-index-topbtnarea').find('.student-index-topbtn:eq(5)')[0]);
				}else if(res.errcode == 102){
					alert('该用户已经是您的好友')
				}else{
					alert('用户不存在')
				}
			}
		})
	}
}