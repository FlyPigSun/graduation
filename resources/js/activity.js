$(document).ready(function(){
	activityCircle.activity.initialize();
});


/**
 * 活动页面
 * author: sunji
 */
activityCircle.activity = {
	initialize : function(){
		activityCircle.activity.refresh();
		$('.activity-push-btn').on('click',
			activityCircle.activity.pushActivity);
		$('.activity-sendcomment-btn').on('click',
			activityCircle.activity.sendComment);
		$('.activity-answer-btn').on('click',
			activityCircle.activity.finishActivity);
	},
	refresh : function(){
		$('.activity-student-checkbox-area').html('');
		var aid = $('.acitvity-aid').html();
		$.ajax({
			url : '/activity/findByAid/'+aid,
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var data = res.data;
				if(res.errcode == 100){
					$('.activity-star').raty({
                        readOnly : true,
                        score : data.level,
                        number : 3,
                        hints : ['中等', '提高', '竞赛'],
                        noRatedMsg: '活动难度'
                    });
                    if(data.res_info == ''){
                    	$('.activity-resource-area').html('无');
                    }else{
	                    if(data.res_info.file_type == 'audio'){
	                    	var html = '<div style="margin:5px">'+data.res_info.name+'</div>'+
	                    	'<audio style="margin-left: 90px;" src="'+data.res_info.address+'" width="300" height="50" wmode="transparent" controls="controls">'+'</audio>';
	                    }else if(data.res_info.file_type == 'img'){
	                    	var html = '<div style="margin:5px">'+data.res_info.name+'</div>'+
	                    	'<img style="margin-left: 90px;" src="'+data.res_info.address+'" width="100" height="100" wmode="transparent" controls="controls"/>'; 
	                    }else if(data.res_info.file_type == 'doc'){
	                    	var html = '<a style="margin:5px" href='+data.res_info.address+'>'+data.res_info.name+'</a>';
	                    }
	                    $('.activity-resource-area').html('');
	                    $('.activity-resource-area').append(html);
	                }
                    $('.activity-student-area').html('');
                    for (var i = 0; i < data.sInfo.length; i++) {
                    	var isFinish = $('.acitvity-isfinish').html();
                    	if(isFinish == '')
                    		isFinish = 1;
                    	if(isFinish != 0)
                    		var studenthtml = '<a href="/activity/'+aid+'/'+data.sInfo[i].sid+'" class="activity-student-name" target="_blank">'+data.sInfo[i].name+'</a>'
                    	else
                    		var studenthtml = '<span class="activity-student-name" style="cursor:default">'+data.sInfo[i].name+'</span>'
                    	$('.activity-student-area').append(studenthtml);
                    };
				}
			}
		});
		$.ajax({
			url : '/teacher/showStudents',
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var data = res.data;
				$.each(data,function(key,item){
					var html = 
						'<div class="activity-student-checkbox-item">'+
                    		'<input type="checkbox" class="activity-student-checkbox" value="'+item.id+'"/>'+
                    			item.realname+
                		'</div>';
                	$('.activity-student-checkbox-area').prepend(html);
				});
				$('.activity-student-checkbox-all').on('click',activityCircle.activity.pushAll);
			}
		});
		activityCircle.activity.getAllComment();
	},
	pushAll : function(){
		var length = 
			$('.activity-student-checkbox-area').find('.activity-student-checkbox').length; 
		if($(this).is(':checked'))
			for(var i = 0;i<length;i++){
				$('.activity-student-checkbox-area').find('.activity-student-checkbox:eq('+i+')')[0].checked = true;
			}
		else
			for(var i = 0;i<length;i++){
				$('.activity-student-checkbox-area').find('.activity-student-checkbox:eq('+i+')')[0].checked = false;
			}
	},
	pushActivity : function(){
		var studentArray = [];
		var length = 
			$('.activity-student-checkbox-area').find('.activity-student-checkbox').length;
		var aid = $('.acitvity-aid').html();
		for(var i = 0;i<length;i++){
			if($('.activity-student-checkbox-area').find('.activity-student-checkbox:eq('+i+')').is(':checked')){
				studentArray.push($('.activity-student-checkbox-area').find('.activity-student-checkbox:eq('+i+')').val());
			}
		}
		$.ajax({
			url : '/teacher/recommendActivity/'+aid,
			type : 'post',
			data : {
				sid : studentArray
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('推送成功');
					activityCircle.activity.refresh();
				}else if(res.errcode == 104){
					alert('您已经给这名同学推送过该活动了');
				}else{
					alert('推送失败');
				}
			}
		});
	},
	sendComment : function(){
		var aid = $('.acitvity-aid').html();
		var comment = encodeURIComponent($('.activity-comment-textarea').val());
		$.ajax({
			url : '/comment/addcomment',
			type : 'post',
			data : {
				commented_aid : aid,
				comment : comment
			},
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				if(res.errcode == 100){
					alert('评论发送成功');
					$('.activity-comment-textarea').val('');
					activityCircle.activity.getAllComment();
				}else if(res.errcode == 104){
					alert('评论发送失败');
				}
			}
		});
	},
	getAllComment : function(){
		$('.activity-comment-center-area').html('');
		var aid = $('.acitvity-aid').html();
		$.ajax({
			url : '/comment/showComment/'+aid,
			type : 'post',
			headers:{
			    'CONTENT-TYPE': 'application/x-www-form-urlencoded'
			},
			success : function(responseText){
				var res = responseText;
				res = $.parseJSON(res);
				var data = res.data;
				if(res.errcode == 100){
                	$.each(data,function(key,item){
                		var tpl = $('#activity_comment_template').html();
                    	var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    	$('.activity-comment-center-area').append(htmlStr);
                    	$('.yike-teacher-detail-comment-reply-btn').on('click',
                    		activityCircle.activity.showReplayArea);
                	});
                	$('.yike-teacher-detail-comment-reply-btn').on('click',activityCircle.activity.showCommentReply);
	                $('.yike-teacher-detail-send-reply-btn').on('click',activityCircle.activity.sendCommentReply);
	                $('.yike-teacher-detail-comment-delete-btn').on('click',activityCircle.activity.deleteComment);
					}
			}
		});
	},
	showReplayArea : function(){
		$(this).siblings('.yike-teacher-detail-comment-reply-area').show();
	},
	sendCommentReply : function(){
		var aid = $('.acitvity-aid').html();
        var targetname =$(this).parent().siblings('.yike-teacher-detail-comment-title').find('span').html()
        var comment = '回复'+targetname+'：'+$(this).siblings('textarea').val();
        comment = encodeURIComponent(comment);
        $.ajax({
            url : '/comment/addcomment',
            type : 'post',
            data : {
            	commented_aid : aid,
                comment : comment
            }, 
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                var res = responseText;
                res = $.parseJSON(res);
                if(res.errcode==100){
                    alert('信息发送成功');
                    activityCircle.activity.getAllComment();
                }
                else{
                    alert('信息发送失败');
                }
            }
        });
    },
    deleteComment : function(){
        var cid = $(this).siblings('.yike-teacher-detail-comment-cid').html();
        $.ajax({
            url : '/comment/deleteComment/'+cid,
            type : 'post',
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                var res = responseText;
                res = $.parseJSON(res);
                if(res.errcode==100){
                    alert('信息删除成功');
                   activityCircle.activity.getAllComment();
                }
                else{
                    alert('信息删除失败');
                }
            }
        });
    },
    finishActivity : function(){
    	var aid = $('.acitvity-aid').html();
    	var answer = $('.activity-answer-textarea').val();
    	$.ajax({
            url : '/activity/studentAnswer',
            type : 'post',
            data : {
            	aid : aid,
            	answer : answer
            },
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                var res = responseText;
                res = $.parseJSON(res);
                if(res.errcode==100){
                    alert('答题成功');
                   	window.location.reload();
                }
                else{
                    alert('答题失败');
                }
            }
        });
    }
}