<?php 
require_once "sqlHelper.class.php";
// SUBSTRING_INDEX(path,'-',-1) 
class dptService{
	function getFctAll($comp){
		$sqlHelper=new sqlHelper();
		$sql="select * from depart where path is null and comp=$comp";
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获取所有所有的部门和分厂
	function getDptAllByJson($comp){
		$sqlHelper=new sqlHelper();
		$sql="select id,depart,pid from depart where comp=$comp";
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$info="";
		for ($i=0; $i < count($res); $i++) { 
        	$info[$i]=array("text"=>"{$res[$i]['depart']}","dptid"=>"{$res[$i]['id']}","pid"=>"{$res[$i]['pid']}");
        }
        $info=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $info;
	}


	// 将所取数据转化为所要求的格式
	function getFctByJson($comp){
		$sqlHelper=new sqlHelper();
		$sql="select depart.*,`user`.num
			  from depart
			  left join 
			  (select departid,count(*) as num from user group by departid) as `user`
			  on depart.id=`user`.departid
			  where comp=$comp";
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
        $info="";
        for ($i=0; $i < count($res); $i++) { 
        	$info[$i]=array("text"=>"{$res[$i]['depart']}","href"=>"javascript:getUser({$res[$i]['id']});","num"=>"{$res[$i]['num']}","tags"=>"{$res[$i]['id']}","pid"=>"{$res[$i]['pid']}");
        }
        $info=json_encode($info,JSON_UNESCAPED_UNICODE);
        return $info;
	}

	function getSecSome($comp,$fct){
		$sqlHelper=new sqlHelper();
		if ($fct=="") {
			$sql="select a.depart,a.id,b.depart as factory,b.id as fid
				  from (select * from depart where path REGEXP '^-[0-9]{1,}$' and comp=$comp ORDER BY path) as a
				  left join depart as b
				  on  SUBSTRING_INDEX(a.path,'-',-1) =b.id
				  GROUP BY a.path limit 0,12";
			$res=$sqlHelper->dql_arr($sql);
			$sqlHelper->close_connect();
			return $res;
		}
	}

	function getSector($fid){
		$sqlHelper=new sqlHelper();
		$sql="select * from depart where path like '-{$fid}'";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function getOfcSome($comp,$sec){
		$sqlHelper=new sqlHelper();
		if ($sec=="") {
			$sql="select a.depart,a.id,b.depart as sector,c.depart as factory
				  from (select * from depart where path REGEXP '^-[0-9]{1,}-[0-9]{1,}$' and comp=$comp ORDER BY path) as a
				  left join depart as b
				  on SUBSTRING_INDEX(a.path,'-',-1) =b.id
				  left join depart as c
				  on SUBSTRING_INDEX(a.path,'-',-2) =c.id
				  GROUP BY a.path limit 0,12";
			$res=$sqlHelper->dql_arr($sql);
			
			$sqlHelper->close_connect();
			return $res;
		}
	}

	function getOffice($sid){
		$sqlHelper=new sqlHelper();
		$sql="select * from depart where path like '%-{$sid}'";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function getUser($dptid){
		$sqlHelper=new sqlHelper();
		$sql="select * from user where departid=$dptid";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加部门
	function addDpt($pid,$name){
		$sqlHelper=new sqlHelper();
		$sql="insert into depart (depart,path,comp,pid) select 
			'{$name}',
			(select CONCAT(IFNULL(path,''),'-',id) from depart where id=$pid),
			(select comp from depart where id=$pid),
			$pid";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得单个的部门信息用于修改
	function getDptOne($id){
		$sqlHelper=new sqlHelper();
		$sql="select depart.*
			  from depart 
			  where id=$id";
		$res=$sqlHelper->dql($sql);
		$path=explode("-",$res['path']);
		for ($j=1; $j < count($path); $j++) { 
			$sql="select depart from depart where id={$path[$j]}";
			$dpt=$sqlHelper->dql($sql);
			$path[$j]=$dpt['depart'];
		}
		$res['pathName']=implode("->",$path);
		$sqlHelper->close_connect();
		return $res;
	}

	function uptDpt($id,$name,$pid,$path){
		// 需要改两个地方，pid path
		$path=explode("-",$path);
		$i=count($path);
		$path[$i-1]=$pid;
		$path=implode("-",$path);

		$sqlHelper=new sqlHelper();
		$sql="update depart set depart='{$name}',pid=$pid,path='{$path}' where id=$id";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function findSon($id){
		$sqlHelper=new sqlHelper();
		$sql="select count(*) as num from depart where path like '-{$id}' or path like '%-{$id}-%' 
			  union select count(*) as num from user where departid=$id
			  union select count(*) as num from device where depart=$id or factory =$id";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function delDpt($id){
		$sqlHelper=new sqlHelper();
		$sql="delete from depart where id=$id";

	}
}
?>