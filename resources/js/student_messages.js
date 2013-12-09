/**
  *学生站内信
  *author: 孙骥
 **/
activityCircle.student.messages = {
	initialize : function(){
		var me = this;
		$('.activitycircle-messages-mode-btn').on('click',me.changeTab);
	},
	changeTab : function(){
		$('.activitycircle-messages-mode-btn').siblings('.active').removeClass('active');
        $(this).addClass('active');
        var btn = $.trim($(this).html());
        switch(btn){
            case '收件箱' :
                $('.yike-studentcenter-receivebox-area').show();
                $('.yike-studentcenter-receivebox-area').siblings().hide();
                break;
            case '发件箱' :
                $('.yike-studentcenter-sendmessage-area').show();
                $('.yike-studentcenter-sendmessage-area').siblings().hide();
                break;
        }
	},
	getReceiveMessages : function(){
		$('.activitycircle-messages-receivemessage-table tbody').html('');
		$.ajax({
            url : '/student/inBox',
            type : 'get',
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                $('.yike-studentcenter-record-table tbody').html('');
                var res = responseText;
                res = $.parseJSON(res);
                var data = res.data;
                $.each(data,function(key,item){
                });
            }
        });
	}
}