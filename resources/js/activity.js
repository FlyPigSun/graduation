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
                    if(data.res_type == 'audio'){
                    	var html = '<div style="margin:5px">'+data.res_name+'</div>'+
                    	'<audio src="'+data.res_address+'" width="300" height="50" wmode="transparent" controls="controls">'+'</audio>';
                    }else if(data.res_type == 'img'){
                    	var html = '<div style="margin:5px">'+data.res_name+'</div>'+
                    	'<img src="'+data.res_address+'" width="100" height="100" wmode="transparent" controls="controls"/>'; 
                    }else if(data.res_type == 'doc'){
                    	var html = '<div style="margin:5px">'+data.res_name+'</div>';
                    }
                    $('.activity-resource-area').html('');
                    $('.activity-resource-area').append(html);
                    if(data.rid == 0)
                    	$('.activity-resource-area').remove();
				}else{
					alert('活动创建失败');
				}
			}
		});
	}
}