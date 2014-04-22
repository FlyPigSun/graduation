/**
  *学生：系统推送活动页面
  *author: 孙骥
 **/
activityCircle.student.systemPush = {
	initialize : function(){
		activityCircle.student.systemPush.refresh();
	},
    refresh : function(){
        $.ajax({
            url : '/student/systemPush',
            type : 'post',
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                var res = responseText;
                res = $.parseJSON(res);
                var data = res.data;
                $('.student-teacher-recommend-undone-center').html('');
                $('.student-teacher-recommend-done-center').html('');
                $.each(data,function(key,item){
                    var tpl = $('#student-teacher-recommend-activity-template').html();
                    var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    if(item.is_finish == 0)
                        $('.student-teacher-recommend-undone-center').append(htmlStr);
                    else
                        $('.student-teacher-recommend-done-center').append(htmlStr);
                    $('.student-teacher-recommend-activity-level-'+item.id).raty({
                        readOnly : true,
                        score : item.level,
                        number : 3,
                        hints : ['中等', '提高', '竞赛'],
                        noRatedMsg: '活动难度'
                    });
                });
                $('.student-teacher-recommend-undone-center').append('<div class="clear"></div>');
                $('.student-teacher-recommend-done-center').append('<div class="clear"></div>');
            }
        });
    }
}