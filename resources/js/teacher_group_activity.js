/**
  *学生个人中心主页
  *author: 孙骥
 **/
activityCircle.teacher.groupActivity = {
	initialize : function(){
		var me = this;
		var template = $('#teacher_group_activity_template').html();
		var html = Mustache.to_html(template).replace(/^\s*/mg, '');
		$('.teacher-index-groupactivity-area').html(html);
		me.buttonBind();
		invokeClick($('.teacher-group-activity-leftbar').find('.teacher-group-activity-leftbar-btn:eq(0)')[0]);
	},
	buttonBind : function(){
		$('.teacher-group-activity-leftbar-btn').on('click',this.changeTab);
		$('#teacher-group-activity-fileupload').fileupload({
			url: '/activity/upload_resources',
    		sequentialUploads: true,
    		start: function (e) {
    			$('.progress').fadeIn();
    		},
    		progressall: function (e, data) {
		        var progress = parseInt(data.loaded / data.total * 100, 10);
		        $('.progress-bar.progress-bar-success').css(
		            'width',
		            progress + '%'
		        );
		    },
		    done: function (e, data) {
	            setTimeout(function(){
	            	$('.progress').fadeOut();
	            	activityCircle.teacher.groupActivity.getAllResources();
	            	setTimeout(function(){$('.progress-bar.progress-bar-success').css(
			            'width',
			            0 + '%'
		        	);},1000);
	            },1000);
	        } 
        });
	},
	changeTab : function(){
		var tid = $('.tid').html();
		$('.teacher-group-activity-leftbar-btn').removeClass('active');
		$(this).addClass('active');
		var btn = $(this).attr('type');
		switch(btn){
			case 'resource_library':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '素材库' ;
				$('.teacher-group-activity-title').html(html);
				$('.teacher-groupActivity-box').hide();
				$('.teacher-resource-box').show();
				activityCircle.teacher.groupActivity.getAllResources();
				break;
			case 'new_activity':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '创建活动' ;
				$('.teacher-group-activity-title').html(html);
				$('.teacher-groupActivity-box').hide();
				$('.teacher-new-activity-box').show();
				break;
			case 'manage_activity':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '管理活动' ;
				$('.teacher-group-activity-title').html(html);
				$('.teacher-groupActivity-box').hide();
				$('.teacher-manage-activity-box').show();
				break;
		}
	},
	getAllResources : function(){
		$('.teacher-resource-library-table tbody').html('');
		$.ajax({
			url : '/activity/show_resources',
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var data = res.data;
				$.each(data,function(key,item){
                	var tpl = $('#teacher-resource-library-table-template').html();
                    var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    $('.teacher-resource-library-table tbody').append(htmlStr);
                });
                $('.teacher-resource-library-table tr:odd').addClass('odd');
                $('.teacher-resource-library-table tr:even').addClass('even');
			}
		});
	},
	deleteResources : function(){

	}
}