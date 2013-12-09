/**
  *学生站内信
  *author: 孙骥
 **/
activityCircle.student.messages = {
	initialize : function(){
		var me = this;
		$('.activitycircle-messages-mode-btn').on('click',me.changeTab);
		$(document).delegate('.activitycircle-messages-receivemessage-table tr','click',{'item':me},me.showMessageDetail);
		this.getReceiveMessages();
	},
	changeTab : function(){
		$('.activitycircle-messages-mode-btn').siblings('.active').removeClass('active');
        $(this).addClass('active');
        var btn = $.trim($(this).html());
        switch(btn){
            case '收件箱' :
                $('.activitycircle-messages-receivebox-area').show();
                $('.activitycircle-messages-receivebox-area').siblings().hide();
                activityCircle.student.messages.getReceiveMessages();
                break;
            case '发件箱' :
                $('.activitycircle-messages-sendmessage-area').show();
                $('.activitycircle-messages-sendmessage-area').siblings().hide();
                activityCircle.student.messages.getSendMessages();
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
                $('.activitycircle-messages-receivemessage-table tbody').html('');
                var res = responseText;
                res = $.parseJSON(res);
                var data = res.data;
                $.each(data,function(key,item){
                	var tpl = $('#activitycircle-messages-receivemessage-table-template').html();
                    var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    $('.activitycircle-messages-receivemessage-table tbody').append(htmlStr);
                });
                $('.activitycircle-messages-receivemessage-table tr:odd').addClass('odd');
                $('.activitycircle-messages-receivemessage-table tr:even').addClass('even');
            }
        });
	},
	getSendMessages : function (){
		$('.activitycircle-messages-sendmessage-table tbody').html('');
		$.ajax({
            url : '/student/outBox',
            type : 'get',
            headers:{
                'CONTENT-TYPE': 'application/x-www-form-urlencoded'
            },
            success : function(responseText){
                $('.activitycircle-messages-sendmessage-table tbody').html('');
                var res = responseText;
                res = $.parseJSON(res);
                var data = res.data;
                $.each(data,function(key,item){
                	var tpl = $('#activitycircle-messages-sendmessage-table-template').html();
                    var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
                    $('.activitycircle-messages-sendmessage-table tbody').append(htmlStr);
                });
                $('.activitycircle-messages-sendmessage-table tr:odd').addClass('odd');
                $('.activitycircle-messages-sendmessage-table tr:even').addClass('even');
            }
        });
	},
	showMessageDetail : function(){

	}
}