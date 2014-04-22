activityCircle = {
	author : 'sunji',
	since : '2013/11/18',
	email : 'sunjipro@gmail.com'
}

activityCircle.student = {
	
}

activityCircle.teacher = {
    
}
/**
 *Js模拟用户点击
 *author: 孙骥
 **/
function invokeClick(element) {  
    if(element.click)element.click(); //判断是否支持click() 事件  
    else if(element.fireEvent)element.fireEvent('onclick'); //触发click() 事件  
    else if(document.createEvent){  
        var evt = document.createEvent("MouseEvents"); //创建click() 事件  
        evt.initEvent("click", true, true); //初始化click() 事件  
        element.dispatchEvent(evt); //分发click() 事件  
    }         
}

/**
 *头像上传回调函数
 *author: 孙骥
 */
function avatar_success(){
    var src = $('.avatar-box').attr('src');
    if(src=='/upload_files/student/avatars/default_avatar.jpg'){
        var sid = $('.sid').html();
        $('.avatar-box').attr('src','/upload_files/student/avatars/'+sid+'_avatar_120.jpg'+
            '?'+Math.random());
    }else if(src=='/upload_files/teacher/avatars/default_avatar.jpg'){
        var tid = $('.tid').html();
        $('.avatar-box').attr('src','/upload_files/teacher/avatars/'+tid+'_avatar_120.jpg'+
            '?'+Math.random());
    }else{
        $('.avatar-box').attr('src',src+'?'+Math.random());
    }
    activityCircle.student.personalCenter.hideAvatarBox();
}