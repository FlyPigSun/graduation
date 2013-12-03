/**
  *学生个人中心主页
  *author: 孙骥
 **/
activityCircle.student.personalCenter = {
	initialize : function(){
		var me = this;
		var sid = $('.sid').html();
		$.ajax({
			url : '/student/personalInfo/'+sid,
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var template = $('#student_personal_center_template').html();
				var html = Mustache.to_html(template, res.data).replace(/^\s*/mg, '');
				$('.student-index-centerarea').html(html);
				me.buttonBind();
				invokeClick($('.student-personal-center-leftbar').find('.student-personal-center-leftbar-btn:eq(0)')[0]);
			}
		});
	},
	buttonBind : function(){
		var me = this;
		$('.student-personal-center-leftbar-btn').on('click',me.changeTab);
		$('.show-box-edit-btn').on('click',me.showEditInfoBox);
		$('.student-personal-info-btn').on('click',me.enterEditInfo);
		$('.student-changepassword-btn').on('click',me.enterChangePassword);
		$('.single-impress-select').on('click',me.enterImpress);
		$(document).delegate('.single-impress-delete',"click",{'item':me},me.deleteImpress);
		$('.input-impress-enter-btn').on('click',me.inputImpress);
		$('.change-avatar-btn').on('click',me.showAvatarBox);
	},
	changeTab : function(){
		var sid = $('.sid').html();
		$('.student-personal-center-leftbar-btn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'info':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '个人信息' ;
				$('.student-personal-center-title').html(html);
				$('.student-personalcenter-box').hide();
				$('.student-personal-info-box').show();
				break;
			case 'impression':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的印象' ;
				$('.student-personal-center-title').html(html);
				$('.student-personalcenter-box').hide();
				$('.student-personal-impression-box').show();
				activityCircle.student.personalCenter.getImpress();
				break;
			case 'questionnaire':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的问卷' ;
				$('.student-personal-center-title').html(html);
				$('.student-personalcenter-box').hide();
				$('.student-personal-questionnaire-box').show();
				activityCircle.student.personalCenter.getQuestionnaireResult();
				break;
			case 'record':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '学习记录' ;
				$('.student-personal-center-title').html(html);
				$('.student-personalcenter-box').hide();
				break;
			case 'activity':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的活动' ;
				$('.student-personal-center-title').html(html);
				$('.student-personalcenter-box').hide();
				break;
		}
	},
	showEditInfoBox : function(){
		$('.show-box').hide();
		$('.change-box').show();
		var realname = $('.show-box').find('.name-box').find('span:eq(1)').html();
		var gender = $('.show-box').find('.gender-box').find('span:eq(1)').html();
		var studentnumber = $('.show-box').find('.studentnumber-box').find('span:eq(1)').html();
		var grade = $('.show-box').find('.grade-box').find('span:eq(1)').html();
		var motto = $('.show-box').find('.motto-box').find('span:eq(1)').html();
		$('.change-box').find('.name-box input').val(realname);
		$('.change-box').find('.gender-box input').val(gender);
		$('.change-box').find('.studentnumber-box input').val(studentnumber);
		$('.change-box').find('.grade-box input').val(grade);
		$('.change-box').find('.motto-box input').val(motto);
	},
	enterEditInfo : function(){
		var realname = $('.change-box').find('.name-box input').val();
		var gender = $('.change-box').find('.gender-box input').val();
		var studentnumber = $('.change-box').find('.studentnumber-box input').val();
		var grade = $('.change-box').find('.grade-box select').val();
		var motto = $('.change-box').find('.motto-box input').val();
		$.ajax({
			url : '/student/updateInfo',
			type : 'post',
			data : {
				realname : encodeURIComponent(realname),
				gender : encodeURIComponent(gender),
				studentnumber : encodeURIComponent(studentnumber),
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
					$('.show-box').find('.studentnumber-box').find('span:eq(1)').html(studentnumber);
					$('.show-box').find('.grade-box').find('span:eq(1)').html(grade);
					$('.show-box').find('.motto-box').find('span:eq(1)').html(motto);
					$('.student-index-topinfoarea').find('div:eq(0)').html(realname);
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
					url : '/student/updatepassword',
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
	getImpress : function(){
		$('.my-impress').html('');
		$.ajax({
			url : '/student/findAllHobby',
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var data = res.data;
				$.each(data,function(key,item){
					var html = '<div class="single-impress yellow">'+
                        	'<img class="single-impress-delete" src="/resources/images/close.png"/>'+
                        '<div>'+item+'</div>'+'</div>'
                    $('.my-impress').append(html);
				});
			}
		});
	},
	enterImpress : function(){
		var impress = $.trim($(this).html());
		var impression = encodeURIComponent(impress);
		$.ajax({
			url : '/student/selectHobby',
			type : 'post',
			data : {
				hobby : impression
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					var html = '<div class="single-impress activitycircle-hide yellow">'+
                        	'<img class="single-impress-delete" src="/resources/images/close.png"/>'+
                        '<div>'+impress+'</div>'+'</div>';
                    $('.my-impress').append(html);
                    $('.single-impress').fadeIn();
				}else if(res.errcode ==  102){
					alert('您已经选择了这个特点');
				}else{
					alert('您选择的特点过多');
				}
			}
		});
	},
	deleteImpress : function(){
		var me = this;
		var impression = $(this).siblings('div').html();
		$.ajax({
			url : '/student/deleteHobby',
			type : 'post',
			data : {
				hobby : impression
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					$(me).parent().fadeOut();
					setTimeout(function(){
						$(me).parent().remove();
					},500);
				}else{
					alert('删除失败');
				}
			}
		});
	},
	inputImpress : function(){
		var impress = $('.input-impress input').val();
		var impression = encodeURIComponent(impress);
		$.ajax({
			url : '/student/myHobby',
			type : 'post',
			data : {
				myhobby : impression
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					var html = '<div class="single-impress activitycircle-hide yellow">'+
                        	'<img class="single-impress-delete" src="/resources/images/close.png"/>'+
                        '<div>'+impress+'</div>'+'</div>';
                    $('.my-impress').append(html);
                    $('.single-impress').fadeIn();
                    $('.input-impress input').val('');
				}else if(res.errcode == 102){
					alert('您已经选择了这个特点');
				}else{
					alert('您选择的特点过多');
				}
			}
		});
	},
    showAvatarBox : function(){
        $('.change-avatar-box').html('');
        $('.change-avatar-box').animate({top:$(document).scrollTop()+150+'px'});
        var html = '<embed src="/resources/avatar_face/face.swf" id="weibo" name="weibo" quality="high" wmode="opaque" flashvars="uploadServerUrl=/student/uploadAvatar&amp;defaultImg=/resources/avatar_face/images/default-avatar.jpg" pluginspage="http://www.adobe.com/go/getflashplayer" type="application/x-shockwave-flash" width="530" height="380">'
        $('.change-avatar-box').append(html);
        $('.index-background').fadeIn();
        $('.index-background').one('click',activityCircle.student.personalCenter.hideAvatarBox);
    },
    hideAvatarBox : function(){
        $('.change-avatar-box').animate({top:'-500px'});
        $('.index-background').fadeOut();
    },
    getQuestionnaireResult : function(){
    	$.ajax({
			url : '/student/myQuestionnaireResult',
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
			}
		});
    }
}