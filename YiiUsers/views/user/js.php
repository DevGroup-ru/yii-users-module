// Le JavaScript of SSO

"use strict"; // jshint ;_;


var YiiUsers = function() {

	}

YiiUsers.prototype = {
	constructor: YiiUsers

}

YiiUsers.checkLoggedIn = function (){
	var rnd = Math.random()
	$.getScript("<?php echo Yii::app()->request->getHostInfo();?>/YiiUsers/user/checkLoggedIn?rnd="+rnd)

}

YiiUsers.checkLoggedInCallback = function(data) {
	console.log("checkLoggedInCallback:");
	console.log(data);
}

;