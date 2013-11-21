$(document).ready(function(){
	activityCircle.studentIndexPage.initialize();
});

/**
  *学生主页
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
	},
	changeTab : function(){
		$('.student-index-topbtn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
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