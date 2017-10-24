<?php
header("content-type:text/html;charset=utf-8");
class safeService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getPaging($paging){
		$sql1 = "SELECT safe.id,name,codeManu,loc,takeTime,valid,circle,
				depart.depart,takeDpt,depart.comp,
				factory.depart factory
				from safe
				left join depart
				on safe.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				where takeDpt {$this->authDpt}
				order by safe.id desc
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				from safe
				where takeDpt {$this->authDpt}";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function addSafe($arr){
		$_arr = [];
		$sql = "INSERT INTO safe set".CommonService::sqlTgther($_arr, $arr);
		$this->sqlHelper->dml($sql);
	}
	
	public function delSafe($id){
		$sql = "DELETE FROM safe where id=$id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	public function uptSafe($arr, $id){
		$_arr = [];
		$sql = "UPDATE safe set ".CommonService::sqlTgther($_arr, $arr)." where id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	public function findSafe($paging){
		$arr = $paging->para['para']['data'];
		$dptid = $paging->para['para']['dptid'];
		
		$where = $this->findWhere($arr, $dptid);
		$sql1="SELECT safe.id,name,codeManu,loc,takeTime,valid,circle,
			   depart.depart,takeDpt,depart.comp,
			   factory.depart factory
			   from safe
			   left join depart
			   on safe.takeDpt = depart.id
			   left join depart factory
			   on depart.fid = factory.id
			   where {$where['dpt']}
			   and {$where['data']} 
			   order by safe.id desc 
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from safe
				 where {$where['dpt']}
				 and {$where['data']}  ";
			  	// echo "$sql2"; die;
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);	
	}

	private function findWhere($arr, $dptid){
		if (!empty($dptid)) {
			// 搜索里有部门限制 并且 部门在用户的管理范围内
			if (in_array($dptid, $_SESSION['dptid']) || $_SESSION['user'] == 'admin') {
				$whereDpt = "takeDpt = $dptid";	
			}else{
				$whereDpt = "1=0";	
			}
		}else{
			$whereDpt = "takeDpt {$this->authDpt}";
		}
		$_arr = [];
		if (!empty($arr)){
			foreach ($arr as $k => $v) {
				if ($v != "") 
					array_push($_arr, "safe.`$k` like '%{$v}%'");
			}
			$where = implode(" and ", $_arr);
		}else{
			$where = "1 = 1";
		}
		return ['data' => $where, 'dpt' => $whereDpt];
	}
}
