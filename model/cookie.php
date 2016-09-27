<?php
header("content-type:text/html;charset=utf-8");
function getCookieval($key){
	if (empty($_COOKIE[$key])) {
		return "";
	}else{
		return $_COOKIE[$key];
	}
}

//验证是否登录，若未登录则返回登录页面
function checkValidate(){
	// session_start();
	if(empty($_SESSION['user'])){
		header("location:./login.php");
		exit();
	}
}
?>