$(document).ready(function(){
	activityCircle.studentIndexPage.initialize();
});

/**
  *学生主页
  *author: 孙骥
 **/
activityCircle.studentIndexPage = {
	initialize : function(){
		var me = this;
		$('body').height($(window).height());
		$(window).resize(function(){
			$('body').height($(window).height());
			if($('body').height()<700){
				$('body').height(700);
			}
		});
		$('.student-index-topbtn').on('click',me.changeTab);
		var notfirst = $('.notfirst').html();
		if(notfirst == 0){
			setTimeout(function(){
				$('.activitycircle-test-box').show();
				$('.activitycircle-test-background').show();
				activityCircle.student.testPage.initialize();
			},10);
		};
		activityCircle.student.indexPage.initialize();
	},
	changeTab : function(){
		$('.student-index-topbtn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'index':
				$('.student-index-centerarea').html('');
				activityCircle.student.indexPage.initialize();
				break;
			case 'personal_center':
				$('.student-index-centerarea').html('');
				activityCircle.student.personalCenter.initialize();
				break;
			case 'teacher_recommend':
				$('.student-index-centerarea').html('');
				break;
			case 'group_activety':
				$('.student-index-centerarea').html('');
				break;
			case 'honor_box':
				$('.student-index-centerarea').html('');
				break;
			case 'friends_circle':
				$('.student-index-centerarea').html('');
				break;
			case 'message':
				$('.student-index-centerarea').html('');
				break;
		}
	}
}

/**
 * 学生主页面
 * author: 孙骥
 **/
activityCircle.student.indexPage = {
	initialize : function(){
		var html = $('#student_indexpage_template').html();
		$('.student-index-centerarea').html(html);
	}
}

/**
 * 性格测试页面
 * author: 孙骥
 **/
activityCircle.student.testPage = {
	firstResult : null,
	firstStyle : null,
	secondResult : null,
	secondStyle : null,
	initialize : function(){
		var me = this;
		$('.activitycircle-styletest').find('.activitycircle-test-btn').on('click',me.commitStyleTest);
		$('.activitycircle-hobbytest').find('.activitycircle-test-btn').on('click',me.commitHobbyTest);
		$('body').height($(window).height()-50);
		$(window).resize(function(){
			$('body').height($(window).height()-50);
			if($('body').height()<700){
				$('body').height(700);
			}
		});
	},
	commitStyleTest : function(){
		var firstA = 0;
		var firstB = 0;
		var firstResult = null;
		var secondA = 0;
		var secondB = 0;
		var secondResult = null;
		var firstStyle = null;
		var secondStyle = null;
		$('.first_style input:radio:checked').each(function(){
			var me = $(this);
			if(me.val()=='a'){
				firstA++;
			}else if(me.val()=='b'){
				firstB++;
			}else{
				return false;
			}
		});
		$('.second_style input:radio:checked').each(function(){
			var me = $(this);
			if(me.val()=='a'){
				secondA++;
			}else if(me.val()=='b'){
				secondB++;
			}else{
				return false;
			}
		});
		if(firstA+firstB+secondA+secondB!=10){
			alert('您需要完成所有问题');
		}else{
			if(firstA>firstB){
				activityCircle.student.testPage.firstResult = firstA-firstB+'a';
				activityCircle.student.testPage.firstStyle = '活跃型';
			}else{
				activityCircle.student.testPage.firstResult = firstB-firstA+'b';
				activityCircle.student.testPage.firstStyle = '沉思型';
			}
			if(secondA>secondB){
				activityCircle.student.testPage.secondResult = secondA-secondB+'a';
				activityCircle.student.testPage.secondStyle = '视觉型';
			}else{
				activityCircle.student.testPage.secondResult = secondB-secondA+'b';
				activityCircle.student.testPage.secondStyle = '言语型';
			}
			$('.activitycircle-styletest').hide();
			$('.activitycircle-hobbytest').show();
		}
	},
	commitHobbyTest : function(){
		var result = 0;
		var num = 0;
		var resultText = null;
		var sid = $('.sid').html();
		$('.activitycircle-hobbytest input:radio:checked').each(function(){
			var me = $(this);
			var a = Number(me.val());
			result = result + a;
			num++;
		});
		if(num!=9){
			alert('您需要完成所有问题');
		}else{
			if(result>38){
				resultText = '很感兴趣';
			}else if(result>31){
				resultText = '较感兴趣';
			}else if(result>20){
				resultText = '一般'
			}else if(result>13){
				resultText = '不大感兴趣';
			}else{
				resultText = '很不感兴趣';
			}
			activityCircle.student.testPage.firstStyle = encodeURIComponent(activityCircle.student.testPage.firstStyle);
			activityCircle.student.testPage.secondStyle = encodeURIComponent(activityCircle.student.testPage.secondStyle);
			resultText = encodeURIComponent(resultText);
			$.ajax({
				url : '/student/testSubmit',
				type : 'post',
				data: {
					first_result : activityCircle.student.testPage.firstResult,
					first_style : activityCircle.student.testPage.firstStyle,
					second_result : activityCircle.student.testPage.secondResult,
					second_style : activityCircle.student.testPage.secondStyle,
					hobby_result : result,
					hobby_result_text : resultText,
					sid : sid
				},
				headers:{
				    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
				},
				success : function(responseText){
					var res = responseText;
					res = $.parseJSON(res);
					if(res.errcode == 100){
						$('.activitycircle-test-box').fadeOut();
						$('.activitycircle-test-background').fadeOut();
					}else{
						alert('答题失败')
					}
				}
			});
		}
	}
}

/**
 * 个人中心页面
 * author: 孙骥
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
				//$('.avatar-box').attr('src','/upload_files/student/avatars/'+sid+'_avatar_120.jpg');
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
    }
}