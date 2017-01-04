 <?php
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
class inspectService{
	public $authDpt = "";

	function __construct(){
		if ($_SESSION['user'] == 'admin') {
			$this->authDpt = "";
		}else{
			$arrDpt = implode(",",$_SESSION['dptid']);
			$this->authDpt = " in($arrDpt) ";
		}
	}

	// 获得巡检记录所有信息用于显示在记录表中
	function getPagingInfo($paging){
		$sqlHelper=new sqlHelper();
		$sql1="SELECT insplist.*,device.name
			   from inspList
			   left join device 
			   on inspList.devid=device.id 
			   where 1=1 
			   and device.depart $this->authDpt
			   order by insplist.id desc
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="SELECT count(insplist.id) 
			   from insplist 
			   left join device 
			   on inspList.devid=device.id 
			   WHERE 1=1 
			   and device.depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}
	// 巡检标准相关函数
	// 获取巡检标准记录表
	function getPagingStd($paging){
		$sqlHelper=new sqlHelper();
		$sql1="SELECT inspstd.*,device.name,device.code,depart.depart
			   from inspstd 
			   inner join device
			   on inspstd.devid=device.id 
			   inner join depart
			   on depart.id=device.depart
			   WHERE 1=1 
			   and device.depart $this->authDpt
			   order by inspstd.id desc
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="SELECT count(inspstd.id) from inspstd inner join device
			   on inspstd.devid=device.id 
			   inner join depart
			   on depart.id=device.depart
			   WHERE 1=1 AND depart.id ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	function getStd($id){
		$sql="select inspstd.*,device.name,device.code,depart.depart
			  from inspstd inner join device
			  on inspstd.devid=device.id 
			  inner join depart
				on depart.id=device.depart
				where inspstd.id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 更新巡检标准记录
	function updateStd($id,$iden,$info){
		$sql="update inspStd set iden='{$iden}',info='{$info}' where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 删除巡检标准记录
	function delStd($id){
		$sql="delete from inspstd where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function findStd($code,$depart,$devid,$name,$paging){
		$where=" where ";
		if (!empty($devid)) {
			$where=" devid=$devid ";
		}else if (!empty($code)) {
			$where=" device.code='{$code}' ";
		}else if(!empty($name) && !empty($depart)){
			$where=" depart.depart='{$depart}' and device.name like '%{$name}%' ";
		}else if (!empty($name)) {
			$where=" device.name like '%{$name}%' ";
		}else{
			$where=" depart.depart='{$depart}' ";
		}
		$sql1="SELECT inspstd.*,device.name,device.code,depart.depart
			   from inspstd inner join device
			   on inspstd.devid=device.id
			   inner join depart
			   on depart.id=device.depart
			   ".$where."and depart.id ".$this->authDpt."
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		
		$sql2="SELECT count(*)
			   from inspstd left join device
			   on inspstd.devid=device.id
			   WHERE 1=1 AND depart.id ".$this->authDpt; 												   
		$sqlHelper=new sqlHelper();			   										
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 获取所有配置柜名称，编号，id
	function getUsingAll(){
		$sql="SELECT name,device.id,depart.depart,factory.depart as factory from device
			  inner join depart
			  on depart.id=device.depart
			  inner join depart as factory
			  on factory.id=device.factory
			  where device.pid=0
			  and depart.id ".$this->authDpt;
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	// 添加新的巡检标准
	function addStd($devid,$iden,$info){
		$sql="insert into inspstd (devid,iden,info) values ('{$devid}','{$iden}','{$info}')";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}


	function getPagingMis($paging){
		$sqlHelper=new sqlHelper();
		$sql1=" SELECT inspmis.id,inspmis.devid,inspmis.cyc,inspmis.nxt,inspmis.type,inspmis.inspDpt,
				factory.depart as factory,depart.depart,inspdpt.depart as inspdpt,inspfct.depart as inspfct,
				device.name 
				from inspmis 
				inner join device
				on inspmis.devid=device.id
				inner join depart
				on depart.id=device.depart
				inner join depart as factory
				on factory.id=device.factory
				inner join depart as inspdpt
				on inspdpt.id=inspmis.inspDpt
				inner join depart as inspfct
				on inspdpt.fid=inspfct.id
				where device.depart ".$this->authDpt."
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
			    // echo "$sql1";die;
		$sql2 = "SELECT COUNT(*) 
				 from inspmis
				 left join device
				 on device.id=inspmis.devid
				 where 1=1 
				 and device.depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	// 删除巡检任务
	function delMis($arr){
		$sqlHelper=new sqlHelper();
		for ($i=0; $i < count($arr); $i++) { 
			$sql="delete from inspmis where id=$arr[$i]";
			$res[]=$sqlHelper->dml($sql);
		}
		// exit();
		$sqlHelper->close_connect();
		return $res;
	}

	function getMis($id){
		$sqlHelper=new sqlHelper();
		$sql="SELECT inspmis.*,device.name,depart.depart dpt,dateInstall
			  from inspmis
			  left join device 
			  on device.id=inspmis.devid 
			  left join depart
			  on inspmis.inspDpt=depart.id
			  where inspmis.id=$id";
		$res=$sqlHelper->dql($sql);
		$res['cyc'] = $this->transTime($res['cyc']);
		$sqlHelper->close_connect();
		return $res;
	}

	// 修改巡检路线信息
	function updateMis($interval,$liable,$misid,$time){
		$sqlHelper=new sqlHelper();
		$sql="UPDATE inspmis set `liable`='$liable', `interval`='$interval', `time`='$time' where id=$misid";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 搜索点检任务
	function findMis($devid,$name,$time,$paging){
		$sqlHelper=new sqlHelper();
		$sql1=" SELECT inspmis.*,device.name,factory.depart as factory,depart.depart
				from inspmis 
				inner join device
				on inspmis.devid=device.id
				inner join depart
				on depart.id=device.depart
				inner join depart as factory
				on factory.id=device.factory
				where (devid='{$devid}' and start='{$time}') or devid='{$devid}' or start='{$time}'
				or name like '%{$name}%' or (name like '%{$name}%' and start='{$time}')
				AND device.depart ".$this->authDpt."
				order by start
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="SELECT count(*) from inspmis 
			   left join device
			   on inspmis.devid=device.id
			   where (devid='{$devid}' and start='{$time}') or devid='{$devid}' or start='{$time}'
			   or name like '%{$name}%' or (name like '%{$name}%' and start='{$time}')
			   and device.depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function updateCon($misid,$dev){
		$sql="DELETE from inspmiscon where misid=$misid";
		$sqlHelper=new sqlHelper();
		$res[]=$sqlHelper->dml($sql);

		for ($i=0; $i < count($dev); $i++) { 
			$sql="INSERT INTO inspmiscon (misid,devid) values ($misid,{$dev[$i]});";
			$res[]=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加巡检任务
	function addMis($dev,$time){
		$sqlHelper=new sqlHelper();
		$sql="insert into inspmis (devid,start) values";
		for ($i=0; $i < count($dev); $i++) { 
			for ($j=0; $j < count($time); $j++) { 
				if ($i==count($dev)-1 && $j==count($time)-1) {
					$sql.="({$dev[$i]},'{$time[$j]}')";
				}else{
					$sql.="({$dev[$i]},'{$time[$j]}'),";
				}
			}
		}
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;	
	}

	// 搜索巡检记录
	function findInfo($begin,$depart,$end,$paging){
		$sqlHelper=new sqlHelper();
		$sql1="SELECT insplist.*,device.name,depart.depart
			   from inspList
			   inner join device 
			   on inspList.devid=device.id 
			   inner join depart
			   on depart.id=device.depart
			   where (time between '{$begin}' and '{$end}') or depart.depart='{$depart}'
			   or ((time between '{$begin}' and '{$end}') or depart.depart='{$depart}')
			   AND device.depart ".$this->authDpt."
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="SELECT count(device.id)
			   from inspList
			   left join device 
			   on inspList.devid=device.id 
			   where (time between '{$begin}' and '{$end}') or depart.depart='{$depart}'
			   or ((time between '{$begin}' and '{$end}') or depart.depart='{$depart}')
			   and device.depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 添加巡检记录
	function addInfo($inspRes,$liable,$time,$idList,$info){
		$sqlHelper=new sqlHelper();
		for ($i=0; $i < count($idList); $i++) {
			if (!empty($idList[$i])) {
			 	$sql="INSERT INTO insplist (`time`,result,liable,info,devid) VALUES ('{$time}','{$inspRes}','{$liable}','{$info}',{$idList[$i]});";
				$res[$i]=$sqlHelper->dml($sql);
			} 
		}
		$sqlHelper->close_connect();
		if(in_array(0,$res,true)){
			$allRes=0;
			return $allRes;
		}else{
			$allRes=1;
			return $allRes;
		}
	}

	// 获取单个巡检记录
	function getInfo($id){
		$sql="SELECT insplist.*,device.name
			  from inspList
			  left join device 
			  on inspList.devid=device.id 
			  where insplist.id=$id
			  AND device.depart ".$this->authDpt;
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 修改巡检记录信息
	function updateInfo($devid,$id,$info,$liable,$result,$time){
		$sql="UPDATE insplist SET info='{$info}',liable='{$liable}',result='{$result}',`time`='{$time}' where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delInfo($id){
		$sql="DELETE FROM insplist where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}


	// 具体设备页面点检记录相关函数
	function getInspByDev($devid){
		$sql="select * from insplist where devid=$devid order by time desc";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 设备具体信息页面添加巡检记录
	function addInfoByDev($devid,$info,$liable,$result,$time){
		$sql="insert into insplist (time,result,liable,info,devid) values ('{$time}','{$result}','{$liable}','{$info}',$devid)";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 设备具体信息页面获得巡检记录
	function getInfoByDev($id){
		$sql="select * from inspList where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 设备具体信息页面修改巡检记录
	function updateInfoByDev($id,$info,$liable,$result,$time){
		$sql="UPDATE insplist SET time='{$time}', result='{$result}', liable='{$liable}', info='{$info}' where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function transTime($time){
		if ($time / 525600 >= 1) {
			// 大于年
			$t[0] = $time / 525600;
			$t[1] = '年';
		}else if ($time / 43200 >=1) {
			$t[0] = $time / 43200;
			$t[1] = '月';
		}else if ($time / 1440 >= 1) {
			$t[0] = $time / 1440;
			$t[1] = '天';
		}else if ($time / 60 >= 1) {
			$t[0] = $time / 60;
			$t[1] = '小时';
		}else{
			$t[0] = $time;
			$t[1] = '分钟';
		}
		return $t;
	}

	function diffInstl($dateInstall){
		$now = strtotime(date("Y-m-d"));
		$install = strtotime($dateInstall);
		$diff = ($now - $install)*60;
		return $diff;
	}


}
?>