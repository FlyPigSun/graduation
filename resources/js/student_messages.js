/**
  *学生站内信
  *author: 孙骥
 **/
activityCircle.student.messages = {
	messages: [],

    curMessage: {},

	initialize : function(){
		var me = this;
		$('.activitycircle-messages-mode-btn').on('click',me.changeTab);
		$(document).delegate('.activitycircle-messages-receivemessage-table tr','click',{'item':me},me.showMessageDetail);
		$(document).delegate('.activitycircle-messages-messagesend-button','click',{'item':me},me.sendMessage);
		$(document).delegate('.activitycircle-messages-sendmessage-table tr','click',{'item':me},me.showMessageDetail);
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
		var me = this;
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
                me.messages = data;
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
		var me = this;
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
                me.messages = data;
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
	showMessageDetail : function(e){
		if($('.activitycircle-messages-sendmessage-btn').hasClass('active')){
			$('.activitycircle-messages-messagesend').hide();
		}else{
			$('.activitycircle-messages-messagesend').show();
		}
		var me = e.data.item;
        var index = $(this).parent().find("tr").index($(this));
        var item = me.messages[index];
        me.curMessage = item;
        $('.activitycircle-messages-messagedetail').html('');
        var tpl = $('#activitycircle-messages-messagedetail-template').html();
        var htmlStr = Mustache.to_html(tpl, item).replace(/^\s*/mg, '');
        $('.activitycircle-messages-messagedetail').append(htmlStr);
        $('.activitycircle-messages-messagedetail-area').siblings().hide();
        $('.activitycircle-messages-messagedetail-area').show();
	},
	sendMessage: function(e){
        var me = e.data.item;
        var title = '回复 ' + me.curMessage.title;
        var content = $('.activitycircle-messages-messagesend-content').val();
        var to_id = me.curMessage.from_id;
        $.post('/student/sendLetters',{
            to_id: to_id,
            title: encodeURIComponent(title),
            content: encodeURIComponent(content)
        },function(data,status){
            alert('发送成功');
            $('.activitycircle-messages-messagesend-content').val('');
        });
    }
}