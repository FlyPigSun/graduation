/**
  *学生个人中心主页
  *author: 孙骥
 **/
activityCircle.teacher.personalCenter = {
	studyStyle : {
		'沉思型':'沉思型学习者更喜欢首先安静地思考问题，“我们先好好想想吧”是沉思型学习者的通常反应。',
		'言语型':'言语型学习者更擅长从文字的和口头的解释中获取信息。',
		'视觉型':' 视觉型学习者很擅长记住他们所看到的东西，如图片、图表、流程图、图像、影片和演示中的内容。',
		'活跃型':'活跃型学习者倾向于通过积极地做一些事—讨论或应用或解释给别人听来掌握信息。“来，我们试试看，看会怎样”这是活跃型学习者的口头禅。他们更喜欢集体工作'
	},
	initialize : function(){
		var me = this;
		var tid = $('.tid').html();
		$.ajax({
			url : '/teacher/personalInfo/'+tid,
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var template = $('#teacher_personal_center_template').html();
				var html = Mustache.to_html(template, res.data).replace(/^\s*/mg, '');
				$('.teacher-index-centerarea').html(html);
				me.buttonBind();
				invokeClick($('.teacher-personal-center-leftbar').find('.teacher-personal-center-leftbar-btn:eq(0)')[0]);
			}
		});
	},
	buttonBind : function(){
		var me = this;
		$('.teacher-personal-center-leftbar-btn').on('click',me.changeTab);
		$('.show-box-edit-btn').on('click',me.showEditInfoBox);
		$('.teacher-personal-info-btn').on('click',me.enterEditInfo);
		$('.teacher-changepassword-btn').on('click',me.enterChangePassword);
		$('.change-avatar-btn').on('click',me.showAvatarBox);
	},
	changeTab : function(){
		var tid = $('.tid').html();
		$('.teacher-personal-center-leftbar-btn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'info':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '个人信息' ;
				$('.teacher-personal-center-title').html(html);
				$('.teacher-personalcenter-box').hide();
				$('.teacher-personal-info-box').show();
				break;
			case 'activity':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的活动' ;
				$('.teacher-personal-center-title').html(html);
				$('.teacher-personalcenter-box').hide();
				break;
		}
	},
	showEditInfoBox : function(){
		$('.show-box').hide();
		$('.change-box').show();
		var realname = $('.show-box').find('.name-box').find('span:eq(1)').html();
		var gender = $('.show-box').find('.gender-box').find('span:eq(1)').html();
		var teachernumber = $('.show-box').find('.teachernumber-box').find('span:eq(1)').html();
		var grade = $('.show-box').find('.grade-box').find('span:eq(1)').html();
		var motto = $('.show-box').find('.motto-box').find('span:eq(1)').html();
		$('.change-box').find('.name-box input').val(realname);
		$('.change-box').find('.gender-box input').val(gender);
		$('.change-box').find('.teachernumber-box input').val(teachernumber);
		$('.change-box').find('.grade-box input').val(grade);
		$('.change-box').find('.motto-box input').val(motto);
	},
	enterEditInfo : function(){
		var realname = $('.change-box').find('.name-box input').val();
		var gender = $('.change-box').find('.gender-box input').val();
		var teachernumber = $('.change-box').find('.teachernumber-box input').val();
		var grade = $('.change-box').find('.grade-box select').val();
		var motto = $('.change-box').find('.motto-box input').val();
		$.ajax({
			url : '/teacher/updateInfo',
			type : 'post',
			data : {
				realname : encodeURIComponent(realname),
				gender : encodeURIComponent(gender),
				teachernumber : encodeURIComponent(teachernumber),
				grade : encodeURIComponent(grade),
				motto : encodeURIComponent(motto),
				class : ''
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					$('.show-box').find('.name-box').find('span:eq(1)').html(realname);
					$('.show-box').find('.gender-box').find('span:eq(1)').html(gender);
					$('.show-box').find('.teachernumber-box').find('span:eq(1)').html(teachernumber);
					$('.show-box').find('.grade-box').find('span:eq(1)').html(grade);
					$('.show-box').find('.motto-box').find('span:eq(1)').html(motto);
					$('.teacher-index-topinfoarea').find('div:eq(0)').html(realname);
					$('.show-box').show();
					$('.change-box').hide();
				}else{
					alert('信息修改失败');
				}
			}
		});
	},
	enterChangePassword : function(){
		var oldPassword = $('.change-password-box input:eq(0)').val();
		var newPassword = $('.change-password-box input:eq(1)').val();
		var newPassword2 = $('.change-password-box input:eq(2)').val();
		if(oldPassword!=''&&newPassword!=''&&newPassword2!=''){
			if(newPassword==newPassword2){
				oldPassword = $.md5(oldPassword);
				newPassword = $.md5(newPassword);
				$.ajax({
					url : '/teacher/updatepassword',
					type : 'post',
					data : {
						oldPassword : oldPassword,
						newPassword : newPassword
					},
					headers:{
					    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
					},
					success : function(responseText){
						var res = responseText;
						res = $.parseJSON(res);
						if(res.errcode == 100){
							alert('密码修改成功');
							$('.change-password-box input:eq(0)').val('');
							$('.change-password-box input:eq(1)').val('');
							$('.change-password-box input:eq(2)').val('');
						}else{
							alert('原始密码输入错误')
						}
					}
				})
			}else{
				alert('两次密码需要输入一致');
			}
		}else{
			alert('数据不能为空');
		}
	},
    showAvatarBox : function(){
        $('.change-avatar-box').html('');
        $('.change-avatar-box').animate({top:$(document).scrollTop()+150+'px'});
        var html = '<embed src="/resources/avatar_face/face.swf" id="weibo" name="weibo" quality="high" wmode="opaque" flashvars="uploadServerUrl=/teacher/uploadAvatar&amp;defaultImg=/resources/avatar_face/images/default-avatar.jpg" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash" width="530" height="380">'
        $('.change-avatar-box').append(html);
        $('.index-background').fadeIn();
        $('.index-background').one('click',activityCircle.teacher.personalCenter.hideAvatarBox);
    },
    hideAvatarBox : function(){
        $('.change-avatar-box').animate({top:'-500px'});
        $('.index-background').fadeOut();
    }
}