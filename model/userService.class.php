<?php
require_once 'sqlHelper.class.php';
class userService{
	function checkUser($code,$password){
		$sqlHelper=new sqlHelper();
		$sql="select psw,name,departid as dptid,permit,id from user where code='$code'";	
		$info=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();

		// 用户名不存在 用户名不正确
		if (empty($info)) {
			$res=1;
		}else{
			// 密码错误
			if ($password!=$info['psw']) {
				$res=2;
			}else{
				$res['name']=$info['name'];
				$res['dptid']=$info['dptid'];
				$res['permit']=$info['permit'];
				$res['id']=$info['id'];
				$res=implode(",",$res);
			}
		}
		return $res;
	}
}
?>
