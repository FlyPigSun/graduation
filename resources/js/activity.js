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
		$('.activity-btn:eq(0)').on('click',activityCircle.activity.pushActivity);
	},
	refresh : function(){
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
                    	$('.activity-resource-area').remove();
                    }else{
	                    if(data.res_info.file_type == 'audio'){
	                    	var html = '<div style="margin:5px">'+data.res_info.name+'</div>'+
	                    	'<audio src="'+data.res_info.address+'" width="300" height="50" wmode="transparent" controls="controls">'+'</audio>';
	                    }else if(data.res_info.file_type == 'img'){
	                    	var html = '<div style="margin:5px">'+data.res_info.name+'</div>'+
	                    	'<img src="'+data.res_info.address+'" width="100" height="100" wmode="transparent" controls="controls"/>'; 
	                    }else if(data.res_info.file_type == 'doc'){
	                    	var html = '<a style="margin:5px" href='+data.res_info.address+'>'+data.res_info.name+'</a>';
	                    }
	                    $('.activity-resource-area').html('');
	                    $('.activity-resource-area').append(html);
	                }
                    $('.activity-student-area').html('');
                    for (var i = 0; i < data.sInfo.length; i++) {
                    	var studenthtml = '<a class="activity-student-name">'+data.sInfo[i].name+'</a>'
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
	}
}