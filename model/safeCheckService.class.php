<?php
header("content-type:text/html;charset=utf-8");
class safeCheckService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getMisPaging($paging){
		$sql1 = "SELECT safe.id,codeManu,safe.name,circle,valid,loc,
				factory.depart factory,depart.depart
				from safe
				left join depart
				on safe.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				where valid <= NOW()
				and takeDpt {$this->authDpt}
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from safe
				 where valid <= NOW()
				and takeDpt {$this->authDpt}";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function getChkPaging($paging){
		$sql1 = "SELECT safe.id,codeManu,safe.name,circle,valid,loc,
				factory.depart factory,depart.depart,
				safe_check.time,res,info,
				user.name
				from safe_check
				left join safe
				on safe.id = safe_check.devid
				left join depart
				on safe.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				left join user
				on user.id = safe_check.user
				where takeDpt {$this->authDpt}
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				from safe_check
				left join safe
				on safe.id = `safe_check`.devid
				where takeDpt {$this->authDpt}";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function addCheck($arr){
		$_arr = ["user = '{$_SESSION['uid']}'"];
		$sql = "INSERT INTO `safe_check` set ".CommonService::sqlTgther($_arr, $arr);
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function setValid($devid, $checkTime){
		$sql = "UPDATE safe set 
					valid = date_sub(
								date_add(
									'{$checkTime}',
									interval circle MONTH
								),
								interval 1 DAY
							)
				where id = $devid";
		$res = $this->sqlHelper->dml($sql);
	}

	public function findMisPaging($paging){
		$arr = $paging->para['para']['data'];
		$name = empty($arr['name']) ? "" : "safe.name like '%{$arr['name']}%'";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$name, $codeManu, $takeDpt]);
		$sql1 = "SELECT safe.id,codeManu,safe.name,circle,valid,loc,
				factory.depart factory,depart.depart
				from safe
				left join depart
				on safe.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				where valid <= NOW()
				and takeDpt {$this->authDpt} 
				and (
				".implode(" and ", $_arr)."
				)
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from safe
				 where valid <= NOW()
				 and takeDpt {$this->authDpt} 
				 and (
				 ".implode(" and ", $_arr).")";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function findCheckPaging($paging){
		$arr = $paging->para['para']['data'];
		$name = empty($arr['name']) ? "" : "safe.name like '%{$arr['name']}%'";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$time = empty($arr['time']) ? "" : "safe_check.time='{$arr['time']}'";
		$_arr = array_filter([$status, $name, $codeManu, $takeDpt, $time]);
		$sql1 = "SELECT safe.id,codeManu,safe.name,circle,valid,loc,
				factory.depart factory,depart.depart,
				safe_check.time,res,info,
				user.name
				from safe_check
				left join safe
				on safe.id = safe_check.devid
				left join depart
				on safe.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				left join user
				on user.id = safe_check.user
				where ".implode(" and ", $_arr)."
				order by `safe_check`.id desc
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				from safe_check
				left join safe
				on safe.id = safe_check.devid
				where ".implode(" and ", $_arr);
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}
}