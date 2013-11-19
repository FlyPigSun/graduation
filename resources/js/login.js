$(document).ready(function(){
	activityCircle.loginPage.initialize();
});


/**
  *登录页面
 **/
activityCircle.loginPage = {
	mode : 'login',
	initialize : function(){
		var me = this;
		$('body').height($(window).height()-150);
		$('.activitycircle-login-btn').on('click',this.login);
		$('.activitycircle-changetab').on('click',this.changeTab);
		$('.activitycircle-register-btn').on('click',this.studentRegister);
		$(window).resize(function(){
			$('body').height($(window).height()-150);
		});
		$(document).keypress(function(e){    
            if(e.which == 13){
                me.login();
            }
        }); 
	},
	login : function(){
		var username = $('.activitycircle-login-area').find('input:eq(0)').val();
		var password = $('.activitycircle-login-area').find('input:eq(1)').val();
		var role = $('.activitycircle-login-area').find('.activitycircle-login-select').val();
		password = $.md5(password);
		if(username!=''&&password!=''){
			$.ajax({
				url : '/login/userlogin',
				type : 'post',
				data: {
				    username : username,
				    password : password,
				    role : role
				},
				headers:{
				    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
				},
				success : function(responseText){
					var res = responseText;
					res = $.parseJSON(res);
					if(res.errcode==100){
						location.reload();
					}else{
						alert('用户名密码错误');
					}
				}
			});
		}else{
			alert('用户名密码不能为空');
		}
	},
	changeTab : function(){
		if(activityCircle.loginPage.mode=='login'){
			$('.activitycircle-register-area').animate({'left':'0px'});
			$('.activitycircle-login-area').animate({'left':'-400px'});
			$('.activitycircle-login-box').animate({'height':'460px'});
			activityCircle.loginPage.mode = 'register';
		}else{
			$('.activitycircle-register-area').animate({'left':'400px'});
			$('.activitycircle-login-area').animate({'left':'0px'});
			$('.activitycircle-login-box').animate({'height':'300px'});
			activityCircle.loginPage.mode = 'login';
		}
	},
	studentRegister : function(){
		var username = $('.activitycircle-register-area input:eq(0)').val();
		var realname = $('.activitycircle-register-area input:eq(1)').val();
		realname = encodeURIComponent(realname);
		var password_1 = $('.activitycircle-register-area input:eq(2)').val();
		var password_2 = $('.activitycircle-register-area input:eq(3)').val();
		var grade = $('.activitycircle-register-area').find('.activitycircle-login-select').val();
		var studentnum = $('.activitycircle-register-area input:eq(4)').val();
		var gender = $('.activitycircle-register-area input:radio[name="sex"]:checked').val();
		if(username!=''&&realname!=''&&password_1!=''&&password_2!=''&&grade!=''&&studentnum!=''&&gender!=''){
			if(password_1==password_2){
				var password = $.md5(password_1);
				$.ajax({
					url : '/student/register',
					type : 'post',
					data: {
					    username : username,
					    password : password,
					    realname : realname,
					    grade : grade,
					    studentnum : studentnum,
					    gender : gender
					},
					headers:{
					    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
					},
					success : function(responseText){

					}
				});
			}else{
				alert('两次输入的密码不一致');
			}
		}else{
			alert('必填项不能为空');
		}
	}
}