<?php  
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
class checkService{
	public $authDpt = "";
	function __construct(){
		if ($_SESSION['user'] == 'admin') {
			$this->authDpt = "";
		}else{
			$arrDpt = implode(",",$_SESSION['dptid']);
			$this->authDpt = " in($arrDpt) ";
		}
	}

	function getTypeAll(){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT id,name from check_type";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function checkOne($arr){
		// [0] => time [1] => type [2] => res [3] => info [4] => class [5] => status [6] => devid
		$arr['user'] = $_SESSION['uid'];
		$arr['info'] = $arr['res'] == 1 ? 'null' : "'{$arr['info']}'"; 
		$arr['status'] = $arr['res'] == 2 ? $arr['status'] : 'null';
		$arr['class'] = $arr['res'] == 3 ? "'{$arr['class']}'" : 'null';
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO `check` (devid,type,res,info,user,time,downClass,chgStatus) 
				values ({$arr['devid']}, {$arr['type']}, {$arr['res']}, {$arr['info']}, {$arr['user']}, '{$arr['time']}', {$arr['class']}, {$arr['status']})";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		$this->setValid($arr['devid']);
		return $res;
	}

	function setValid($devid){
		$sqlHelper = new sqlHelper;
		$sql = "UPDATE buy set 
					valid=date_add(
						( SELECT time from `check` where devid = $devid order by id desc limit 0,1 ),
						interval circle MONTH
					) 
				where id = $devid";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
	}

	function getCheckByDev($id){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT `check`.id,check_type.name type,res,info,user.name user,time,
				status.status,chgStatus,downClass
				from `check`
				left join check_type
				on check_type.id = `check`.type
				left join user
				on user.id = `check`.user
				left join status
				on status.id = `check`.chgStatus
				where `check`.devid=$id
				order by `check`.id desc";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getXlsChk($idStr){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT devid,time,res from `check` where devid in ($idStr)";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function trimXls(Array $check){
		$_check = [];
		foreach ($check as $k => $v) 
			$_check[$v['devid']][] = $v;
		return $_check;
	}

}
?>