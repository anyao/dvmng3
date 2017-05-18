<?php
require_once 'sqlHelper.class.php';
class userService{
	// 验证密码
	function getPwd($code){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT psw,name,id,code FROM user where code='$code'";
		$bsc = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		if (empty($bsc)) 
			return array("err" => "notFind", "data" => "");
		else
			return array('err' => "", "data" => array('psw' => $bsc['psw'], 'uid' => $bsc['id'], 'user' => $bsc['name'], 'code' => $bsc['code']));
	}

	// 查询用户权限,uid
	function getAuth($uid){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT staff_role_func.fid funcid
				FROM staff_user_role
				LEFT JOIN staff_role_func
				ON staff_user_role.rid = staff_role_func.rid
				WHERE uid = $uid";
		$funcid = $sqlHelper->dql_arr($sql);
		$_SESSION['funcid'] = array_column($funcid, 'funcid');

		$sql = "SELECT dptid from staff_user_dpt where uid= $uid";
		$dptid = $sqlHelper->dql_arr($sql);
		$_SESSION['dptid'] = array_column($dptid,'dptid');
		$sqlHelper->close_connect();
	}

	// 查询当前用户所在分厂
	function getFct($uid){
		$sqlHelper=new sqlHelper();
		$sql="SELECT depart.fid,factory.depart as factory,depart.id as did,depart.depart
			  from user 
			  left join depart
			  on user.departid=depart.id
			  LEFT JOIN depart as factory 
			  on factory.id=depart.fid
			  where user.id=$uid";
		$res=$sqlHelper->dql($sql);
		 // [fid] => [factory] => [did] => 1 [depart] => 新区竖炉
		 if (empty($res['fid'])) {
		 	$res['factory'] = $res['depart'];
		 	$res['depart'] = "分厂级";
		 }
		$sqlHelper->close_connect();
		return $res;
	}

	// 修改密码
	function chgPwd($new, $uid){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE user SET psw = '{$new}' WHERE id = {$uid}";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}
	
	
}
?>
