<?php  
header("content-type:text/html;charset=utf-8");
class repairService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getMisPaging($paging){
		// -- reason1,reason2,reason3,reason4,reason5,reason6,reason7,reason8,reason9
		// -- left join (
		// -- 	SELECT reason1,reason2,reason3,reason4,reason5,reason6,reason7,reason8,reason9,devid
		// -- 	FROM
		// -- 		`check`
		// -- 	WHERE
		// -- 		id IN (
		// -- 			SELECT
		// -- 				MAX(id)
		// -- 			FROM
		// -- 				`check`
		// -- 			WHERE
		// -- 				res = 2
		// -- 			GROUP BY
		// -- 				devid
		// -- 		)
		// -- ) `check`
		// -- on `check`.devid = buy.id
		$sql1 = "SELECT buy.id,buy.name,spec,codeManu,loc,factory.depart factory
				from buy
				left join depart 
				on depart.id = buy.takeDpt
				left join depart factory
				on factory.id = depart.fid
				where codeManu is not null
				and takeDpt {$this->authDpt}
				and buy.status = 8
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				from buy 
				where codeManu is not null
				and takeDpt {$this->authDpt}
				and buy.status = 8";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function unqualReason($reason){
		switch ($reason) {
			case 1: return "损坏；";
			case 2: return "过载；";
			case 3: return "可能使其预期用途无效的故障；";
			case 4: return "产生不正确的测量结果；";
			case 5: return "超过规定的计量确认间隔；";
			case 6: return "误操作；";
			case 7: return "封印或保护装置损坏或破裂；";
			case 8: return "暴露在已有可能影响其预期用途的影响量中(如电磁场、灰尘)。";
			case 9: return "其它";
		}
	}

	public function addRepair($repair){
		// [device] => 设备状况 [repair] => 维护调整情况 [surface] => 外观腐蚀情况 [time] => 2017-07-28 [devid] => 573
		$_arr = ["user = '{$_SESSION['uid']}'"];
		$sql = "INSERT INTO repair set ".CommonService::sqlTgther($_arr, $repair);
		$this->sqlHelper->dml($sql);
	}

	public function findMisPaging($arr, $paging){
		// [name] => 差压变送器 [codeManu] => 30112S16 [takeDpt] => 1,2,3,198,
		$name = empty($arr['name']) ? "" : "buy.name like '%{$arr['name']}'%";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$name, $codeManu, $takeDpt]);
		$sql1 = "SELECT buy.id,buy.name,spec,codeManu,loc,factory.depart factory
				from buy
				left join depart 
				on depart.id = buy.takeDpt
				left join depart factory
				on factory.id = depart.fid
				where (
						codeManu is not null
					and buy.status = 8
					and takeDpt {$this->authDpt}
				) and (
				".implode(" and ", $_arr)."
				)
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from buy 
				 where (
						codeManu is not null
					and takeDpt {$this->authDpt}
					and status = 8
				) and (
				".implode(" and ", $_arr).")";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}
}
?>