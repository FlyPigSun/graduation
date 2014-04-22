/**
  *教师活动页面
  *author: 孙骥
 **/
activityCircle.teacher.groupActivity = {
	newActivityLevel : 1,
	themeScore : 0,
	resScore : 0,
	audioScore : 0,
	voiceScore : 0,
	classScore : 0,
	initialize : function(){
		var me = this;
		var template = $('#teacher_group_activity_template').html();
		var html = Mustache.to_html(template).replace(/^\s*/mg, '');
		$('.teacher-index-groupactivity-area').html(html);
		me.buttonBind();
		invokeClick($('.teacher-group-activity-leftbar').find('.teacher-group-activity-leftbar-btn:eq(0)')[0]);
		$('.teacher-comment-btn').on('click',activityCircle.teacher.groupActivity.submitComment);
	},
	buttonBind : function(){
		var me = this;
		$('.teacher-group-activity-leftbar-btn').on('click',me.changeTab);
		$('.teacher-new-activity-btn').on('click',me.newActivity)
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
        $('.teacher-new-activity-star').raty({
            hints : ['中等', '提高', '竞赛'],
            number : 3,
            score : 1,
            click: function (score, evt) {
                activityCircle.teacher.groupActivity.newActivityLevel = score; 
            }
        });
        $('.teacher-new-activity-theme select:eq(0)').on('change',me.changeTheme);
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
				activityCircle.teacher.groupActivity.returnCreate();
				break;
			case 'manage_activity':
				var html = '<img style="margin-right:10px;" src="/resources/images/personalcenter-header-ico.png"/>'+
                '管理活动' ;
				$('.teacher-group-activity-title').html(html);
				activityCircle.teacher.groupActivity.getAllActivity();
				$('.teacher-groupActivity-box').hide();
				$('.teacher-manage-activity-box').show();
				break;
		}
	},
	getAllResources : function(){
		$('.teacher-resource-library-table tbody').html('');
		$('.teacher-new-activity-resource select:eq(0)').html('');
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
				$('.teacher-new-activity-resource select:eq(0)')
				.append('<option value="0">'+'无'+'</option>');
				$.each(data,function(key,item){
					var html = 
						'<option value="'+item.id+'">'+
                            item.name+
                        '</option>';
                    $('.teacher-new-activity-resource select:eq(0)').append(html);
                	var tpl = $('#teacher-resource-library-table-template').html();
                    var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    $('.teacher-resource-library-table tbody').append(htmlStr);
                });
                $('.teacher-resource-library-delete-btn').on('click',activityCircle.teacher.groupActivity.removeResources);
                $('.teacher-resource-library-table tr:odd').addClass('odd');
                $('.teacher-resource-library-table tr:even').addClass('even');
			}
		});
	},
	removeResources : function(){
		var file = encodeURIComponent($(this).parent().parent().find('.file-name').html());
		if(confirm("是否确认")){
			$.ajax({
				url : '/activity/delete_resources',
				type : 'post',
				data : {
					file:file
				},
				headers:{
				    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
				},
				success : function(responseText){
					var res = responseText;
					res = $.parseJSON(res);
					if(res.errcode == 100){
						alert('删除成功');
						activityCircle.teacher.groupActivity.getAllResources();
					}else if(res.errcode == 103){
						alert('对不起，您不能删除他人上传的文件');
					}else {
						alert('删除失败');
					}
				}
			});
		}else{ 
			return false;
		};
	},
	changeTheme : function(){
		var firstSelect = $('.teacher-new-activity-theme select:eq(0)');
		var secondSelect = $('.teacher-new-activity-theme select:eq(1)');
		secondSelect.html('');
		switch(firstSelect.val()){
			case '个人情况' :
				var html = 
						'<option value="个人信息">'+
                            '个人信息'+
                        '</option>'+
                        '<option value="家庭信息">'+
                            '家庭信息'+
                        '</option>'+
                        '<option value="学校信息">'+
                            '学校信息'+
                        '</option>'+
                        '<option value="兴趣爱好">'+
                            '兴趣爱好'+
                        '</option>'+
                        '<option value="工作与职业">'+
                            '工作与职业'+
                        '</option>';
				break;
			case '日常活动' : 
				var html = 
						'<option value="家庭生活">'+
                            '家庭生活'+
                        '</option>'+
                        '<option value="学校生活">'+
                            '学校生活'+
                        '</option>'+
                        '<option value="周末活动">'+
                            '周末活动'+
                        '</option>';
				break;
			case '个人兴趣' : 
				var html = 
						'<option value="游戏与休闲">'+
                            '游戏与休闲'+
                        '</option>'+
                        '<option value="爱好">'+
                            '爱好'+
                        '</option>'+
                        '<option value="娱乐活动">'+
                            '娱乐活动'+
                        '</option>'+
                        '<option value="旅游">'+
                            '旅游'+
                        '</option>';
				break;
			case '饮食' : 
				var html = 
						'<option value="食物">'+
                            '食物'+
                        '</option>'+
                        '<option value="饮料">'+
                            '饮料'+
                        '</option>'+
                        '<option value="饮食习俗">'+
                            '饮食习俗'+
                        '</option>'+
                        '<option value="点餐">'+
                            '点餐'+
                        '</option>';
				break;
			case '居住环境' : 
				var html = 
						'<option value="房屋与住所">'+
                            '房屋与住所'+
                        '</option>'+
                        '<option value="居室">'+
                            '居室'+
                        '</option>'+
                        '<option value="家具与家庭用品">'+
                            '家具与家庭用品'+
                        '</option>'+
                        '<option value="社区">'+
                            '社区'+
                        '</option>';
				break;
		};
		secondSelect.append(html);
	},
	newActivity : function(){
		var title = encodeURIComponent($('.teacher-new-activity-title input').val());
		var content = encodeURIComponent($('.teacher-new-activity-content textarea').val());
		var goal = encodeURIComponent($('.teacher-new-activity-goal select').val());
		var type = encodeURIComponent($('.teacher-new-activity-type select').val());
		var level = activityCircle.teacher.groupActivity.newActivityLevel;
		var theme = encodeURIComponent($('.teacher-new-activity-theme select:eq(1)').val());
		var rid = $('.teacher-new-activity-resource select').val();
		if(title==''||content==''||level==''){
			alert('以上项目不能为空');
		}else{
			$.ajax({
				url : '/activity/create_activity',
				type : 'post',
				data : {
					title : title,
					content : content,
					goal : goal,
					type : type,
					level : level,
					theme : theme,
					rid : rid
				},
				headers:{
				    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
				},
				success : function(responseText){
					var res = responseText;
					res = $.parseJSON(res);
					if(res.errcode == 100){
						$('.teacher-new-activity-first').animate({'left':'-1000px'});
						$('.teacher-new-activity-second').animate({'left':0});
						$('.teacher-new-activity-title input').val('');
						$('.teacher-new-activity-content textarea').val('');
						$('.teacher-new-activity-second').html('');
						$('.teacher-new-activity-star').raty({
				            hints : ['中等', '提高', '竞赛'],
				            number : 3,
				            score : 1,
				            click: function (score, evt) {
				                activityCircle.teacher.groupActivity.newActivityLevel = score; 
				            }
				        });
				        var tpl = $('#teacher-new-activity-success-template').html();
	                    var htmlStr = Mustache.to_html(tpl, res.data).replace(/^\s*/mg, '');
	                    if(res.data.res_type == 'audio'){
	                    	var html = '<div style="margin:5px">'+res.data.res_name+'</div>'+
	                    	'<audio src="'+res.data.res_address+'" width="300" height="50" wmode="transparent" controls="controls">'+'</audio>';
	                    }else if(res.data.res_type == 'img'){
	                    	var html = '<div style="margin:5px">'+res.data.res_name+'</div>'+
	                    	'<img src="'+res.data.res_address+'" width="100" height="100" wmode="transparent" controls="controls"/>'; 
	                    }else if(res.data.res_type == 'doc'){
	                    	var html = '<div style="margin:5px">'+res.data.res_name+'</div>';
	                    }
	                    $('.teacher-new-activity-second').append(htmlStr);
	                    $('.teacher-new-activity-success-resource-area div').html('');
	                    $('.teacher-new-activity-success-resource-area div').append(html);
	                    $('.teacher-new-activity-enter').unbind();
	                    $('.teacher-new-activity-enter').on('click',activityCircle.teacher.groupActivity.returnCreate);
	                    if(res.data.rid == 0)
	                    	$('.teacher-new-activity-success-resource-box').remove();
					}else{
						alert('活动创建失败');
					}
				}
			});
		}
	},
	returnCreate : function(){
		$('.teacher-new-activity-first').animate({'left':'0px'});
		$('.teacher-new-activity-second').animate({'left':'1000px'});
	},
	getAllActivity : function(){
		$('.teacher-manage-activity-listeningbox').html('');
		$('.teacher-manage-activity-oralbox').html('');
		$('.teacher-manage-activity-readingbox').html('');
		$('.teacher-manage-activity-writtingbox').html('');
		$.ajax({
            url:'/activity/show_activity',
            type : 'post',
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                var res = responseText;
                res = $.parseJSON(res);
                var data = res.data;
                $.each(data,function(key,item){
                    var tpl = $('#teacher-manage-activity-template').html();
                    var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    switch(item.goal){
                    	case '听力能力':
                    		$('.teacher-manage-activity-listeningbox').append(htmlStr);
                    		break;
                    	case '口语能力':
                    		$('.teacher-manage-activity-oralbox').append(htmlStr);
                    		break;
                    	case '阅读能力':
                    		$('.teacher-manage-activity-readingbox').append(htmlStr);
                    		break;
                    	case '写作能力':
                    		$('.teacher-manage-activity-writtingbox').append(htmlStr);
                    		break;
                    }
                    $('.teacher-manage-listening-activity-level-'+item.id).raty({
                        readOnly : true,
                        score : item.level,
                        number : 3,
                        hints : ['中等', '提高', '竞赛'],
                        noRatedMsg: '活动难度'
                    });
                });
				$('.teacher-manege-listening-activity-comment').on('click',[event],activityCircle.teacher.groupActivity.showCommentBox);
				$('.teacher-manage-listening-activity-delete').on('click',[event],activityCircle.teacher.groupActivity.removeActivity);
            }
        });
	},
	removeActivity : function(event){
		var aid = $(this).parent().find('.teacher-manage-activity-infobox-id').html();
		$.ajax({
			url : '/teacher/deleteActivity/'+aid,
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('活动删除成功');
					activityCircle.teacher.groupActivity.getAllActivity();
				}else if(res.errcode == 103){
					alert('对不起，您不能删除他人创建的活动');
				}else {
					alert('删除失败');
				}
			}
		});
		event.preventDefault();
	},
	showCommentBox : function(event){
		var aid = $(this).parent().find('.teacher-manage-activity-infobox-id').html();
		$('.teacher-comment-box-id ').html(aid);
		$('.teacher-comment-box').animate({'top':'200px'});
		$('.index-background').fadeIn();
		$('.index-background').one('click',activityCircle.teacher.groupActivity.hideCommentBox);
		$('.teacher-comment-theme-star').raty({
            hints : ['很不好', '不太好', '一般','不错','非常好'],
            number : 5,
            score : 0,
            click: function (score, evt) {
                activityCircle.teacher.groupActivity.themeScore = score; 
            }
        });
        $('.teacher-comment-res-star').raty({
            hints : ['很不好', '不太好', '一般','不错','非常好'],
            number : 5,
            score : 0,
            click: function (score, evt) {
                activityCircle.teacher.groupActivity.resScore = score; 
            }
        });
        $('.teacher-comment-audio-star').raty({
            hints : ['很不好', '不太好', '一般','不错','非常好'],
            number : 5,
            score : 0,
            click: function (score, evt) {
                activityCircle.teacher.groupActivity.audioScore = score; 
            }
        });
        $('.teacher-comment-voice-star').raty({
            hints : ['很不好', '不太好', '一般','不错','非常好'],
            number : 5,
            score : 0,
            click: function (score, evt) {
                activityCircle.teacher.groupActivity.voiceScore = score; 
            }
        });
        $('.teacher-comment-class-star').raty({
            hints : ['很不好', '不太好', '一般','不错','非常好'],
            number : 5,
            score : 0,
            click: function (score, evt) {
                activityCircle.teacher.groupActivity.classScore = score; 
            }
        });
		event.preventDefault();
	},
	hideCommentBox : function(){
		$('.teacher-comment-box').animate({'top':'-300px'});
		$('.index-background').fadeOut();
	},
	submitComment : function(){
		var aid = $('.teacher-comment-box-id').html();
		if(activityCircle.teacher.groupActivity.themeScore==0||activityCircle.teacher.groupActivity.resScore==0||activityCircle.teacher.groupActivity.audioScore==0||activityCircle.teacher.groupActivity.voiceScore==0||activityCircle.teacher.groupActivity.classScore==0){
			alert('您需要每一项都评论');
			return false;
		}
		$.ajax({
			url : '/teacher_review/addReview/'+aid,
			type : 'post',
			data : {
				theme_match_degree : activityCircle.teacher.groupActivity.themeScore,
				res_match_degree : activityCircle.teacher.groupActivity.resScore,
				audio_clear_degree : activityCircle.teacher.groupActivity.audioScore,
				voice_fluent_degree : activityCircle.teacher.groupActivity.voiceScore,
				class_effect_degree : activityCircle.teacher.groupActivity.classScore
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('评论成功');
					invokeClick($('.index-background')[0]);
					activityCircle.teacher.groupActivity.getAllActivity();
				}else {
					alert('评论失败');
				}
			}
		});
	}
}