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
			case 'notification':
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
			result = result + me.val();
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
	},
	changeTab : function(){
		$('.student-personal-center-leftbar-btn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'info':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '个人信息' ;
				$('.student-personal-center-title').html(html);
				break;
			case 'impression':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的印象' ;
				$('.student-personal-center-title').html(html);
				break;
			case 'questionnaire':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的问卷' ;
				$('.student-personal-center-title').html(html);
				break;
			case 'record':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '学习记录' ;
				$('.student-personal-center-title').html(html);
				break;
			case 'activity':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '我的活动' ;
				$('.student-personal-center-title').html(html);
				break;
		}
	},
	showEditInfoBox : function(){
		$('.show-box').hide();
		$('.change-box').show();
		var name = $('.show-box').find('.name-box').find('span:eq(1)').html();
		var gender = $('.show-box').find('.gender-box').find('span:eq(1)').html();
		var studentnumber = $('.show-box').find('.studentnumber-box').find('span:eq(1)').html();
		var grade = $('.show-box').find('.grade-box').find('span:eq(1)').html();
		var motto = $('.show-box').find('.motto-box').find('span:eq(1)').html();
		$('.change-box').find('.name-box input').val(name);
		$('.change-box').find('.gender-box input').val(gender);
		$('.change-box').find('.studentnumber-box input').val(studentnumber);
		$('.change-box').find('.grade-box input').val(grade);
		$('.change-box').find('.motto-box input').val(motto);
	},
	enterEditInfo : function(){
		var name = $('.change-box').find('.name-box input').val();
		var gender = $('.change-box').find('.gender-box input').val();
		var studentnumber = $('.change-box').find('.studentnumber-box input').val();
		var grade = $('.change-box').find('.grade-box input').val();
		var motto = $('.change-box').find('.motto-box input').val();
		$.ajax({
			url : '/student/updateInfo',
			type : 'post',
			data : {
				name : name,
				gender : gender,
				studentnumber : studentnumber,
				grade : grade,
				motto : motto
			},
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