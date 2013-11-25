activityCircle = {
	author : 'sunji',
	since : '2013/11/18',
	email : 'sunjipro@gmail.com'
}

activityCircle.student = {
	
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