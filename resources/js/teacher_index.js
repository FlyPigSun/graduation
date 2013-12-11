$(document).ready(function(){
	activityCircle.teacherIndexPage.initialize();
});

/**
  *教师主页
  *author: 孙骥
 **/
activityCircle.teacherIndexPage = {
	initialize : function(){
		var me = this;
		$('body').height($(window).height());
		$(window).resize(function(){
			$('body').height($(window).height());
			if($('body').height()<700){
				$('body').height(700);
			}
		});
		$('.teacher-index-topbtn').on('click',me.changeTab);
	},
	changeTab : function(){
		$('.teacher-index-topbtn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'index':
				$('.teacher-index-centerarea').children('div').hide();
				$('.teacher-index-first-area').show();
				break;
			case 'personal_center':
				$('.teacher-index-centerarea').children('div').hide();
				$('.teacher-index-personalcenter-area').show();
				activityCircle.teacher.personalCenter.initialize();
				break;
			case 'group_activity':
				$('.teacher-index-centerarea').children('div').hide();
				$('.teacher-index-groupactivity-area').show();
				activityCircle.teacher.groupActivity.initialize();
				break;
			case 'honor_box':
				$('.teacher-index-centerarea').children('div').hide();
				$('.teacher-index-hornor-area').show();
				break;
			case 'friends_circle':
				$('.teacher-index-centerarea').children('div').hide();
				$('.teacher-index-friends-area').show();
				break;
			case 'message':
				$('.teacher-index-centerarea').children('div').hide();
				$('.teacher-index-message-area').show();
				break;
		}
	}
}