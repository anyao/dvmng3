<?php
class userService{
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->sqlHelper = $sqlHelper;
	}
	// 验证密码
	function getPwd($code){
		$sql = "SELECT psw,name,id,code,departid FROM user where code='$code'";
		$bsc = $this->sqlHelper->dql($sql);
		if (empty($bsc)) 
			return ["err" => "notFind", "data" => ""];
		else
			return [
					"err" => "",
				    "data" => [
				    	'psw' => $bsc['psw'],
				        'uid' => $bsc['id'], 
				        'user' => $bsc['name'], 
				        'code' => $bsc['code'],
				        'udptid' => $bsc['departid']
				    ]
				   ];
	}

	// 查询用户权限,uid
	function getAuth($uid){
		$sql = "SELECT staff_role_func.fid funcid
				FROM staff_user_role
				LEFT JOIN staff_role_func
				ON staff_user_role.rid = staff_role_func.rid
				WHERE uid = $uid";
		$funcid = $this->sqlHelper->dql_arr($sql);
		$_SESSION['funcid'] = array_column($funcid, 'funcid');

		$sql = "SELECT dptid from staff_user_dpt where uid= $uid";
		$dptid = $this->sqlHelper->dql_arr($sql);
		$_SESSION['dptid'] = array_column($dptid,'dptid');
	}

	function getDpt(){
		$sql = "SELECT depart.depart,num,factory.depart factory
				from depart
				left join dpt_num
				on depart.id = dpt_num.depart
				left join depart factory
				on factory.id = depart.fid
				where depart.id = {$_SESSION['udptid']}";
		$res = $this->sqlHelper->dql($sql);
		return $res;
	}


	// 查询当前用户所在分厂
	function getFct($uid){
		$sql="SELECT depart.fid,factory.depart as factory,depart.id as did,depart.depart
			  from user 
			  left join depart
			  on user.departid=depart.id
			  LEFT JOIN depart as factory 
			  on factory.id=depart.fid
			  where user.id=$uid";
		$res=$this->sqlHelper->dql($sql);
		 // [fid] => [factory] => [did] => 1 [depart] => 新区竖炉
		 if (empty($res['fid'])) {
		 	$res['factory'] = $res['depart'];
		 	$res['depart'] = "分厂级";
		 }
		return $res;
	}

	// 修改密码
	function chgPwd($new, $uid){
		$sql = "UPDATE user SET psw = '{$new}' WHERE id = {$uid}";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}
	
	
}
?>
