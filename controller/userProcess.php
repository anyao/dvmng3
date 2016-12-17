<?php
header("content-type:text/html;charset=utf-8");
require_once '../model/userService.class.php';

// 接受用户id和password
$code=$_POST['code'];
$psw=$_POST['psw'];

// 是否保存cookie 如果保存则存1天
if (!empty($_POST['keep'])) {
	$keep=$_POST['keep'];
	if (!empty($code)) {
		setcookie("user",$code,time()+3600*24);
	}
}else{
	setcookie("user",$code,time()-100);
}

$userService=new userService();
$checkUser=$userService->checkUser($code,$psw);
echo "$checkUser";
exit();

?>