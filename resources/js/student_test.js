$(document).ready(function(){
	activityCircle.student.testPage.initialize();
});


/**
 * 登录页面
 * author: sunji
 */
activityCircle.student.testPage = {
	initialize : function(){
		var me = this;
		$('.activitycircle-styletest').find('.activitycircle-test-btn').on('click',me.commitStyleTest);
		$('.activitycircle-hobbytest').find('.activitycircle-test-btn').on('click',me.commitHobbyTest);
	},
	commitStyleTest : function(){
		var firstA = 0;
		var firstB = 0;
		var firstResult = null;
		var secondA = 0;
		var secondB = 0;
		var secondResult = null;
		var firstStyle = null;
		var secondStyle = null;
		$('.first_style input:radio:checked').each(function(){
			var me = $(this);
			if(me.val()=='a'){
				firstA++;
			}else if(me.val()=='b'){
				firstB++;
			}else{
				return false;
			}
		});
		$('.second_style input:radio:checked').each(function(){
			var me = $(this);
			if(me.val()=='a'){
				secondA++;
			}else if(me.val()=='b'){
				secondB++;
			}else{
				return false;
			}
		});
		if(firstA+firstB+secondA+secondB!=10){
			alert('您需要完成所有问题');
		}else{
			if(firstA>firstB){
				firstResult = firstA-firstB+'a';
				firstStyle = '活跃型';
			}else{
				firstResult = firstB-firstA+'b';
				firstStyle = '沉思型';
			}
			if(secondA>secondB){
				secondResult = secondA-secondB+'a';
				secondStyle = '视觉型';
			}else{
				secondResult = secondB-secondA+'b';
				secondStyle = '言语型';
			}
			$('.activitycircle-styletest').hide();
			$('.activitycircle-hobbytest').show();
		}
	},
	commitHobbyTest : function(){
		var result = 0;
		var num = 0;
		var resultText = null;
		$('.activitycircle-hobbytest input:radio:checked').each(function(){
			var me = $(this);
			result = result + me.val();
			num++;
		});
		if(num!=9){
			alert('您需要完成所有问题')
		}else{
			if(result>38){
				resultText = '很感兴趣';
			}else if(result>31){
				resultText = '较感兴趣';
			}else if(result>20){
				resultText = '一般'
			}else if(result>13){
				resultText = '不大感兴趣';
			}else{
				resultText = '很不感兴趣';
			}
		}
	}
}