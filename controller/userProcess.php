<?php
header("content-type:text/html;charset=utf-8");
require_once '../model/userService.class.php';
$userService=new userService();
if (!empty($_REQUEST['flag'])) {
	$flag = $_REQUEST['flag'];
	if ($flag == "login") {
		// 接受用户id和password
		$code=$_POST['code'];
		$psw=$_POST['psw'];
		
		// 是否保存cookie 如果保存则存1天
		if (!empty($_POST['keep'])) {
			if (!empty($code)) {
				setcookie("user",$code,time()+3600*24);
			}
		}else{
			setcookie("user",$code,time()-100);
		}
		$res = $userService->getPwd($code);
		if (empty($res['err'])) {
			if ($res['data']['psw'] == $psw) {
				$_SESSION['user'] = $res['data']['user'];
				$_SESSION['uid'] = $res['data']['uid'];
				$_SESSION['code'] = $res['data']['code'];
				$_SESSION['udptid'] = $res['data']['udptid'];
				$userService->getAuth($res['data']['uid']);
				echo 3; die;
			}else
				echo 2; die;
		}else
			echo 1; die;
	}

	else if ($flag == "chgPwd") {
		$uid = $_SESSION['uid'];
		$pre = $_GET['pre'];
		$new = $_GET['new'];
		$res = $userService->getPwd($_SESSION['code']);
		if ($pre != $res['data']['psw']) {
			echo "dif"; die;
		}else{
			$res = $userService->chgPwd($new, $_SESSION['uid']);
			echo "suc"; die;
		}
	}
}




?>