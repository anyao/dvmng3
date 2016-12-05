<?php
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
class repairService{
	public $authWhr="";
	public $authAnd="";

	function __construct(){
		$sqlHelper=new sqlHelper();
		$upid=$_SESSION['dptid'];//用户所在部门id
		$pmt=$_SESSION['permit'];
		switch ($pmt) {
			case '0':
				$this->authWhr="";
				$this->authAnd="";
				break;
			case '1':
				$sql="select id from depart where id=$upid or path in('%-{$upid}','%-{$upid}-%')";
				$upid=$sqlHelper->dql_arr($sql);
				$upid=implode(",",array_column($upid,'id'));
				$this->authWhr=" where device.depart in(".$upid.") ";
				$this->authAnd=" and device.depart in(".$upid.") ";
				break;
			case '2':
				$this->authWhr=" where device.depart=$upid ";
				$this->authAnd=" and device.depart=$upid ";
				break;
		}
		$sqlHelper->close_connect();	
	}



	// 获取维修任务列表并分页显示
	function getPagingMis($paging){
		$uid=$_SESSION['uid'];
		$sqlHelper=new sqlHelper();
		$sql1="SELECT repmis.*,device.name,depart.depart,user.name as fxman
			   FROM	repmis
			   inner JOIN device
			   ON repmis.devid=device.id
			   inner join depart
			   on depart.id=device.depart
			   inner join user
			   on user.id=repmis.liable".$this->authWhr." or repmis.liable=$uid
			   ORDER BY repmis.id desc
			  limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
			  // echo "$sql1";
			  // exit();
		$sql2="select count(repmis.id) from repmis
			   inner JOIN device
			   ON repmis.devid=device.id
			   inner join depart
			   on depart.id=device.depart".$this->authWhr;		
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	// 获取维修记录并分页显示
	function getPagingInfo($paging){

		$sqlHelper=new sqlHelper();
		$sql1="SELECT replist.*,device.name,depart.depart,factory.depart as factory
			   FROM	replist
			   inner JOIN device
			   ON replist.devid=device.id
			   inner join depart 
			   on depart.id=device.depart
			   inner join depart as factory
			   on factory.id=device.factory".$this->authWhr."
			   ORDER BY replist.id desc
			  limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(replist.id) from replist
			   inner JOIN device
			   ON replist.devid=device.id
			   inner join depart 
			   on depart.id=device.depart
			   inner join depart as factory
			   on factory.id=device.factory".$this->authWhr;		
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function setSeen($id){
		$sql="update repmis set seen=1 where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加新的维修任务
	function addMis($devid,$err,$liable){
		$sql="insert into repmis (devid,err,liable,result,infoid,seen,today) values($devid,'{$err}','{$liable}',0,0,0,0)";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得新任务数量
	function getMisCount(){
		$uid=$_SESSION['uid'];
		$sql="select count(repmis.id),repmis.liable from repmis 
			  left join device
			  on repmis.devid=device.id
			  where seen=0 and liable='{$uid}'";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res['count(repmis.id)'];
	}

	// 获取指定日期需执行的维修任务
	function getMisNow($date){
		$start=date("Y-m-d H:i",$date);
		$end=date("Y-m-d",$date)." 23:59:59";
		$sql="select * from repmis where time between '{$start}' and '{$end}'";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 根据id获取任务
	function getMis($id){
		$sql="SELECT repmis.*,device.name,depart.depart,factory.depart as factory
			  from repmis
			  inner join device
			  on repmis.devid=device.id
			  inner join depart
			  on depart.id=device.depart
			  inner join depart as factory
			  on factory.id=device.factory
			  where repmis.id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 今日不再提醒
	function noToday($id){
		$sql="update repmis set today=1 where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得所有根设备的id,name
	function getdevAll(){
		$sql="select device.id,name,depart.depart,factory.depart as factory from device 
			  inner join depart
			  on device.depart=depart.id	
			  inner join depart as factory
			  on device.factory=factory.id		
			  where device.pid=0".$this->authAnd;
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 修改维修任务基本信息
	function updateMis($devid,$err,$liable,$misid){
		$sql="update repmis set devid='{$devid}',err='{$err}',liable='{$liable}' where id='{$misid}'";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 删除维修任务
	function delMis($id){
		$sql="delete from repmis where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 搜索任务
	function findMis($devName,$devid,$result,$paging){
		$sqlHelper=new sqlHelper();
		$sql1="SELECT repmis.*,device.name,depart.depart
			   FROM	repmis
			   inner JOIN device
			   ON repmis.devid=device.id 
			   INNER JOIN depart
			   on device.depart=depart.id ";
		$info="where ";
		if ($devid!="") {
			$info.="devid=$devid ";
			unset($devName);
		}

		if (!empty($result)) {
			$result--;
			if ($devName!="") {
				$info.="and result='{$result}' and devName like'%{$devName}%' ";
			}else{
				$info.="and result='{$result}' ";
			}
		}
		$sql1.=$info.$this->authAnd."limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";

		$sql2="SELECT count(repmis.id)
			  FROM	repmis
			  LEFT JOIN device
			  ON repmis.devid=device.id ".$info.$this->authAnd;

		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}


	// 添加维修记录
	function addInfoByMis($devid,$err,$liable,$reason,$solve,$time,$misid){
		$sql="insert into replist (devid,err,liable,reason,solve,time) values($devid,'{$err}','{$liable}','{$reason}','{$solve}','{$time}')";
		$sqlHelper=new sqlHelper();
		$res[]=$sqlHelper->dml($sql);
		$sql="select id from replist order by id desc limit 0,1";
		$id=$sqlHelper->dql($sql);
		$sql="update repmis set result=1,infoid={$id['id']} where id=$misid";
		$res[]=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获取维修记录
	function getInfo($id){
		$sql="select replist.*,device.name,depart.depart,factory.depart as factory
			  from replist
			  inner join device 
			  on replist.devid=device.id
			  inner join depart
			  on depart.id=device.depart
			  inner join depart as factory
			  on factory.id=device.depart
			  where replist.id = $id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;	  
	}

	// 在维修页面添加维修记录
	function addInfo($devid,$err,$liable,$reason,$solve,$time){
		$sql="INSERT INTO replist (devid,err,liable,reason,solve,`time`) VALUES ($devid,'{$err}','{$liable}','{$reason}','{$solve}','{$time}')";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 在维修页面修改维修记录
	function updateInfo($devid,$err,$id,$liable,$reason,$solve,$time){
		$sql="UPDATE replist SET devid=$devid,err='{$err}',liable='{$liable}',reason='{$reason}',solve='{$solve}',`time`='{$time}' where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 在维修页面中删除维修记录
	function delInfo($id){
		$sql="DELETE FROM replist WHERE id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function findInfo($devid,$name,$time,$liable,$paging){
		$sqlHelper=new sqlHelper();
		$sql1="SELECT replist.*,device.name,depart.depart,factory.depart as factory
			   FROM	replist
			   inner JOIN device
			   ON replist.devid=device.id
			   inner join depart
			   on depart.id=device.depart
			   inner join depart as factory
			   on factory.id=device.factory ";
		$info="where ";
		if ($devid!="") {
			$info.="devid=$devid ";
			unset($name);
		}

		if ($liable!="") {
			$info.="and liable like '%{$liable}%' ";
		}

		if (!empty($time)) {
			if ($name!="") {
				$info.="and time like '{$time}%' and devName like'%{$devName}%' ";
			}else{
				$info.="and time like '{$time}%' ";
			}
		}
		$sql1.=$info.$this->authAnd."limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="SELECT count(replist.id)
			  FROM	replist
			  LEFT JOIN device
			  ON replist.devid=device.id ".$info.$this->authAnd;		
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 设备具体信息页面的维修记录查看
	function getRepByDev($devid){
		$sql="select id,liable,err,time from replist where devid=$devid order by id desc";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 设备具体信息页面获得单个维修记录
	function getRep($id){
		$sql="select * from replist where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;	  
	}

	// 设备具体信息页面修改维修记录
	function updtRepByDev($err,$id,$liable,$reason,$solve,$time,$res){
		$sql="UPDATE replist set err='{$err}',liable='{$liable}',reason='{$reason}',solve='{$solve}',time='{$time}' where id =$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}
}

?>