<?php 
require_once "sqlHelper.class.php";
// SUBSTRING_INDEX(path,'-',-1) 
class dptService{
	// 获取部门及其分厂	后期具体添加他们的部门结构限制
	function getDpt(){
		$sqlHelper = new sqlHelper();
		$sql = "select depart.depart,IFNULL(factory.depart,'分厂') as factory,depart.id from depart 
				left join depart as factory
				on depart.fid = factory.id";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		return $res;
	}

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
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function checkUser($code){
		$sqlHelper=new sqlHelper();
		$sql="select count(*) as num from user where code='{$code}'";
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加用户
	function addUser($code,$name,$permit,$dptid,$psw){
		$sqlHelper=new sqlHelper();
		$sql="insert into user (code,name,permit,departid,psw) values('{$code}','{$name}','{$permit}',$dptid,'{$psw}')";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得用户信息用于修改
	function getUserForUpt($id){
		$sqlHelper=new sqlHelper();
		$sql="select user.*,depart.depart from user
			  left join depart
			  on depart.id=user.departid
			  where user.id=$id";
		$res=$sqlHelper->dql($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获取所有部门分厂用于用户修改信息
	function getDptForUser(){
		$sqlHelper=new sqlHelper();
		$sql="select b.depart,b.id,depart.depart as fct from depart
			  right join (
			  select SUBSTRING(SUBSTRING_INDEX(path,'-',2),2) as a,id,depart from depart 
			  ) as b
			  on depart.id=b.a";
		$res=$sqlHelper->dql_arr($sql);
		for ($i=0; $i < count($res); $i++) { 
			if ($res[$i]['fct']=="") {
				$res[$i]['fct']="分厂级";
			}
		}
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function uptUser($code,$dptid,$name,$permit,$psw,$id){
		$sqlHelper=new sqlHelper();
		$sql="update user set code='{$code}',departid=$dptid,name='{$name}',permit=$permit,psw='{$psw}' where id=$id";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delUser($id){
		$sqlHelper=new sqlHelper();
		$sql="delete from user where id=$id";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getConById($uid){
		$sqlHelper=new sqlHelper();
		$sql="select dev_user.id,devid,name from dev_user 
			  left join `device`
			  on dev_user.devid=device.id
			  where end is null and uid=$uid";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function getUsingAll(){
		$sql="select name,device.id,depart.depart,factory.depart as factory from device
			  inner join depart
			  on depart.id=device.depart
			  inner join depart as factory
			  on factory.id=device.factory
			   where device.pid=0";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	// function setCon($uid,$dev){
	// 	$time=date("Y-m-d",time());
	// 	$sqlHelper=new sqlHelper();
	// 	$sql="insert into dev_user (uid,devid,time,info) values ";
	// 	for ($i=0; $i < count($dev); $i++) { 
	// 		// $sql.=""
	// 		if ($i==count($dev)-1) {
	// 			$sql.="($uid,{$dev[$i]},$time,'开始管理')"
	// 		}
	// 	}
	// }

	// 添加新的设备与管理员关系
	function addCon($devid,$uid){
		$sqlHelper=new sqlHelper();
		$sql="insert into dev_user (devid,uid,time) values";
		$time=date("Y-m-d",time());
		for ($i=0; $i < count($devid); $i++) { 
			if ($i!=count($devid)-1) {
				$sql.="($devid[$i],$uid,'{$time}'),";
			}else{
				$sql.="($devid[$i],$uid,'{$time}')";
			}
		}
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delCon($id){
		$sqlHelper=new sqlHelper();
		$time=date("Y-m-d",time());
		for ($i=0; $i < count($id); $i++) { 
			$sql="update dev_user set end ='{$time}' where id={$id[$i]};";
			$res[]=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}

	function findUser($kword){
		$sqlHelper=new sqlHelper();
		$sql="select * from user where concat(name,',',code) like '%{$kword}%'";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

}
?>