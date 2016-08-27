<?php
header("content-type:text/html;charset=utf-8");
require_once '../model/userService.class.php';

// 接受用户id和password
if (!empty($_POST['code'])) {
	$code=$_POST['code'];
}else{
	echo "账户为空";
	exit();
}

if (!empty($_POST['psw'])) {
	$psw=$_POST['psw'];
}else{
	echo "密码为空";
	exit();
}

// 是否保存cookie 如果保存则存1天
if (!empty($_POST['keep'])) {
	$keep=$_POST['keep'];
	if (!empty($code)) {
		setcookie("user",$code,time()-100);
	}
}else{
	setcookie("user",$code,time()+3600*24);
}

$userService=new userService();
$checkUser=$userService->checkUser($code,$psw);
if ($checkUser!=1 && $checkUser!=2) {
	session_start();
	$_SESSION['user']=$checkUser;
	// echo $_SESSION['user'];
	header("location:../homePage.php");
	exit();
}else if($checkUser==1){
	// 用户不存在或用户名错误
	header("location:../login.php?err=1");
	exit();
}else if($checkUser==2){
	// 密码错误
	header("location:../login.php?err=2");
	exit();
}



?>