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
		}
	},
	changeTab : function(){
		$('.student-index-topbtn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'index':
				alert(btn);
				break;
			case 'personal_center':
				alert(btn);
				break;
			case 'teacher_recommend':
				alert(btn);
				break;
			case 'group_activety':
				alert(btn);
				break;
			case 'honor_box':
				alert(btn);
				break;
			case 'friends_circle':
				alert(btn);
				break;
			case 'notification':
				alert(btn);
				break;
		}
	}
}

/**
 * 性格测试页面
 * author: 孙骥
 */
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
			alert('您需要完成所有问题')
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