$(document).ready(function(){
	activityCircle.teacherIndexPage.initialize();
});

/**
  *学生主页
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
				$('.teacher-index-centerarea').html('');
				break;
			case 'personal_center':
				$('.teacher-index-centerarea').html('');
				break;
			case 'teacher_recommend':
				$('.teacher-index-centerarea').html('');
				break;
			case 'group_activety':
				$('.teacher-index-centerarea').html('');
				break;
			case 'honor_box':
				$('.teacher-index-centerarea').html('');
				break;
			case 'friends_circle':
				$('.teacher-index-centerarea').html('');
				break;
			case 'message':
				$('.teacher-index-centerarea').html('');
				break;
		}
	}
}