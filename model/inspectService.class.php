 <?php
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
class inspectService{
	public $authWhr="";
	public $authAnd="";
	// public $authDpt="";
	// public $authDptAnd="";
	// public $authUsr="";
	// public $authUsrAnd="";

	function __construct(){
		$sqlHelper=new sqlHelper();
		$upid=$_SESSION['dptid'];
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

	// 获得巡检记录所有信息用于显示在记录表中
	function getPagingInfo($paging){
		$sqlHelper=new sqlHelper();
		$sql1="select insplist.*,device.name
			   from inspList
			   left join device 
			   on inspList.devid=device.id ".$this->authWhr."
			   order by insplist.id desc
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(insplist.id) from insplist left join device 
			   on inspList.devid=device.id ".$this->authWhr;
		
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}
	// 巡检标准相关函数
	// 获取巡检标准记录表
	function getPagingStd($paging){
		$sqlHelper=new sqlHelper();
		$sql1=" select inspstd.*,device.name,device.code,depart.depart
				from inspstd 
				inner join device
				on inspstd.devid=device.id 
				inner join depart
				on depart.id=device.depart".$this->authWhr."
				order by inspstd.id desc
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
			    // aecho "$sql1";
			    // exit();
		$sql2="select count(inspstd.id) from inspstd inner join device
				on inspstd.devid=device.id 
				inner join depart
				on depart.id=device.depart".$this->authWhr;
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
		// where devid='{$devid}' or device.code='{$code}' or (depart.depart='{$depart}' and device.name like '%{$name}%') or (depart.depart='{$depart}') or (device.name like '%{$name}%')
		$sql1="select inspstd.*,device.name,device.code,depart.depart
			   from inspstd inner join device
			   on inspstd.devid=device.id
			   inner join depart
			   on depart.id=device.depart
			   ".$where.$this->authAnd."
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		
		$sql2="select count(*)
			   from inspstd left join device
			   on inspstd.devid=device.id".$this->authAnd; 												   
		$sqlHelper=new sqlHelper();			   										
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 获取所有配置柜名称，编号，id
	function getUsingAll(){
		$sql="select name,device.id,depart.depart,factory.depart as factory from device
			  inner join depart
			  on depart.id=device.depart
			  inner join depart as factory
			  on factory.id=device.factory
			   where device.pid=0".$this->authAnd;
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
		$sql1=" select inspmis.*,device.name,factory.depart as factory,depart.depart
				from inspmis 
				inner join device
				on inspmis.devid=device.id
				inner join depart
				on depart.id=device.depart
				inner join depart as factory
				on factory.id=device.factory".$this->authWhr."
				order by start
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(inspmis.id) from inspmis
				inner join device
				on inspmis.devid=device.id
				inner join depart
				on depart.id=device.depart
				inner join depart as factory
				on factory.id=device.factory".$this->authWhr;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	// 删除巡检任务
	function delMis($arr){
		$sqlHelper=new sqlHelper();
		for ($i=0; $i < count($arr); $i++) { 
			$sql="delete from inspmis where id=$arr[$i]";
			// echo "$sql;";
			$res[]=$sqlHelper->dml($sql);
		}
		// exit();
		$sqlHelper->close_connect();
		return $res;
	}

	function getMis($start){
		$sql="select inspmis.*,device.name 
			  from inspmis
			  left join device 
			  on device.id=inspmis.devid 
			  where start='{$start}'".$this->authAnd;
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	function getMisAll(){
		$sql="select inspmis.*,device.name,factory.depart as factory,depart.depart
			  from inspmis 
    	 	  inner join device
			  on inspmis.devid=device.id
			  inner join depart
			  on depart.id=device.depart
			  inner join depart as factory
			  on factory.id=device.factory".$this->authWhr."
			  order by start
			  limit 0,10";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
        $result=array();
        foreach ($res as $k => $v) {
          $result[$v['start']][]=$v;
        }
        $result=array_values($result);
        $info=array();
        for ($i=0; $i < count($result); $i++) { 
            for ($j=0; $j < count($result[$i]); $j++) { 
                // if ($j!=count($result[$i])-1) {
                    $info[$i]['start']=$result[$i][$j]['start'];
                    @$info[$i]['name'].=$result[$i][$j]['name']."-";
                    @$info[$i]['devid'].=$result[$i][$j]['devid']."-";
                // }else{
                //     $info[$i]['name'].=$result[$i][$j]['name'];
                //     $info[$i]['devid'].=$result[$i][$j]['devid'];
                // }
            }
        }
		$sqlHelper->close_connect();
		$info=json_encode($info,JSON_UNESCAPED_UNICODE);
		return $info;
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
		$sql1=" select inspmis.*,device.name,factory.depart as factory,depart.depart
				from inspmis 
				inner join device
				on inspmis.devid=device.id
				inner join depart
				on depart.id=device.depart
				inner join depart as factory
				on factory.id=device.factory
				where (devid='{$devid}' and start='{$time}') or devid='{$devid}' or start='{$time}'
				   or name like '%{$name}%' or (name like '%{$name}%' and start='{$time}')".$this->authAnd."
				order by start
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(*) from inspmis 
				left join device
				on inspmis.devid=device.id
				where (devid='{$devid}' and start='{$time}') or devid='{$devid}' or start='{$time}'
				   or name like '%{$name}%' or (name like '%{$name}%' and start='{$time}')".$this->authAnd;
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
		$sql1="select insplist.*,device.name,depart.depart
			   from inspList
			   inner join device 
			   on inspList.devid=device.id 
			   inner join depart
			   on depart.id=device.depart
			   where (time between '{$begin}' and '{$end}') or depart.depart='{$depart}'
			   or ((time between '{$begin}' and '{$end}') or depart.depart='{$depart}')".$this->authAnd."
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(device.id)
			   from inspList
			   left join device 
			   on inspList.devid=device.id 
			   where (time between '{$begin}' and '{$end}') or depart.depart='{$depart}'
			   or ((time between '{$begin}' and '{$end}') or depart.depart='{$depart}')".$this->authAnd;
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
		$sql="select insplist.*,device.name
			  from inspList
			  left join device 
			  on inspList.devid=device.id where insplist.id=$id".$this->authAnd;
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

	// 点检任务提示框
	// function getMisByDate($date){
	// 	 // [0] => Array ( [id] => 29 [next] => 2016-06-14 ) 
	// 	 // [1] => Array ( [id] => 33 [next] => 2016-06-14 ) 
	// 	 // [2] => Array ( [id] => 34 [next] => 2016-06-14 ) 
	// 	$date=date("Y-m-d",$date);
	// 	$sql="SELECT id,DATE_ADD(last,INTERVAL `INTERVAL` day) as next from inspmis";
	// 	$sqlHelper=new sqlHelper();
	// 	$res=$sqlHelper->dql_arr($sql);
	// 	for ($i=0; $i < count($res); $i++) { 
	// 		if ($res[$i]['next']==$date) {
	// 			$todayMis[]=$res[$i]['id'];
	// 		}
	// 	}
	// 	print_r($todayMis);
	// }

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



}
?>