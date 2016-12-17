<?php
require_once 'sqlHelper.class.php';
class userService{
	function checkUser($code,$password){
		$sqlHelper=new sqlHelper();
		$sql="SELECT psw,name,id from user where code='$code'";
		$bsc=$sqlHelper->dql($sql);

		// 用户名不存在 用户名不正确
		if (empty($bsc)) {
			$res = 1;
		}else if ( $bsc['psw'] != $password) {
			$res = 2;
		}else{
			// 存在该用户查询其权限，和管辖范围
			$sql = "SELECT staff_role_func.fid funcid
					from staff_user_role
					left join staff_role_func
					on staff_user_role.rid=staff_role_func.rid
					where uid = {$bsc['id']}";
			$funcid = $sqlHelper->dql_arr($sql);
			for ($i=0; $i < count($funcid); $i++) { 
				$_SESSION['funcid'][] = $funcid[$i]['funcid'];
			}
			$sql = "SELECT dptid from staff_user_dpt where uid={$bsc['id']}";
			$dptid = $sqlHelper->dql_arr($sql);
			for ($i=0; $i < count($dptid); $i++) { 
				$_SESSION['dptid'][] = $dptid[$i]['dptid'];
			}

			$_SESSION['user'] = $bsc['name'];
			$_SESSION['uid'] = $bsc['id'];
			$sqlHelper->close_connect();
			
			$res = 3;
		}
		return $res;
	}

	// 查询当前用户所在分厂
	function getFct($departid){
		$sqlHelper=new sqlHelper();
		$sql="select depart.fid,factory.depart as factory,depart.id as did,depart.depart
			  from depart
			  LEFT JOIN depart as factory 
			  on factory.id=depart.fid
			  where depart.id=$departid";
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}
	
	
}
?>
