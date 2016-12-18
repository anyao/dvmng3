<?php 
require_once "sqlHelper.class.php";
// SUBSTRING_INDEX(path,'-',-1) 
class dptService{
	// 获取部门及其分厂	后期具体添加他们的部门结构限制
	function getDpt(){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT depart.depart,IFNULL(factory.depart,'分厂') as factory,depart.id 
				from depart 
				left join depart as factory
				on depart.fid = factory.id";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		return $res;
	}

	function getFctAll($comp = 0){
		$sqlHelper=new sqlHelper();
		if ($comp != 0) {
			$sql = "SELECT * FROM depart where path is null and comp=$comp";
		}else{
			$sql = "SELECT depart,id FROM depart where path is null";
		}
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
		$sql="SELECT depart.*,`user`.num
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

	function getDptForRole($comp){
		$sqlHelper=new sqlHelper();
		$sql="SELECT depart as name,id,pid as pId
			  from depart
			  where comp=$comp";
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
        $res=json_encode($res,JSON_UNESCAPED_UNICODE);
        return $res;
	}

	function getRoleFunc(){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT staff_func.title func,fid,staff_role.title role,rid
				from staff_func
				inner join staff_role_func
				on staff_role_func.fid = staff_func.id
				inner join staff_role
				on staff_role.id = staff_role_func.rid";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getRoleAll(){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT * from staff_role";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
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

	function getSector($fid,$ext = 0){
		$sqlHelper=new sqlHelper();
		if ($ext == 0) {
			$sql="SELECT * from depart where fid = $fid";
		}else{
			$sql = "SELECT depart,id from depart where fid = $fid";
		}
		$res=$sqlHelper->dql_arr($sql);
		
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
		$sql = "SELECT user.id,user.name,user.code,staff_role.title as role
				from user
				left join staff_user_role
				on staff_user_role.uid=user.id
				left join staff_role
				on staff_user_role.rid=staff_role.id
				where user.departid=$dptid";
		$res=$sqlHelper->dql_arr($sql);
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
	function addUser($code,$name,$psw,$dptid,$node,$role){
		$sqlHelper=new sqlHelper();
		// user表添加用户基本信息
		$sql = "INSERT INTO user (code,name,psw,departid) values('{$code}','{$name}','{$psw}',$dptid)";
		$res[] = $sqlHelper->dml($sql);
		$uid = mysql_insert_id();

		// 用户可操作的功能
		$res[] = $this->addUserRole($uid,$role);

		// 用户可操作的部门范围
		$res[] = $this->addUserDpt($uid,$node);

		$sqlHelper->close_connect();
		return !in_array(0,$res);
	}

	// 获得用户信息用于修改
	function getUserBsc($id){
		$sqlHelper=new sqlHelper();
		$sql = "SELECT name,code,CONCAT(depart.depart,'-',IFNULL(factory.depart,'分厂级')) as depart,departid dptid,psw
			    from user
			    left join depart
			    on depart.id=user.departid
			    left join depart as factory
			    on depart.fid=factory.id
			    where user.id=$id";
		$res=$sqlHelper->dql($sql);
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

	function uptUserBsc($code,$dptid,$name,$psw,$id){
		$sqlHelper=new sqlHelper();
		$sql="UPDATE user set code='{$code}',departid=$dptid,name='{$name}',psw='{$psw}' where id=$id";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
	}

	function delUser($id){
		$sqlHelper=new sqlHelper();
		$sql="DELETE from user where id=$id";
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

	function addRole($func,$roleName){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO staff_role (title) values ('{$roleName}')";
		$res[] = $sqlHelper->dml($sql);
		$rid =  mysql_insert_id();

		$sql = "INSERT INTO staff_role_func (rid,fid) values ";
		for ($i=0; $i < count($func); $i++) { 
			if ($i != count($func)-1) {
				$sql .= "($rid, {$func[$i]}),";
			}else{
				$sql .= "($rid,{$func[$i]})";
			}
		}
		$res[] = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return !in_array(0,$res);
	}

	function delRole($rid){
		$sqlHelper = new sqlHelper();
		$sql = "DELETE FROM staff_role where id = $rid";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function uptRole($rid,$func,$roleName){
		$sqlHelper = new sqlHelper();
		$sql = "DELETE FROM staff_role_func where rid=$rid";
		$res[] = $sqlHelper->dml($sql);

		$sql = "INSERT INTO staff_role_func(rid,fid) values ";
		for ($i=0; $i < count($func); $i++) { 
			if ($i != count($func)-1) {
				$sql .= "($rid, {$func[$i]}),";
			}else{
				$sql .= "($rid,{$func[$i]})";
			}
		}
		$res[] = $sqlHelper->dml($sql);

		$sql = "UPDATE staff_role set title='{$roleName}' where id=$rid";
		$res[] = $sqlHelper->dml($sql);
		return !in_array(0,$res);
	}

	function getFct($id){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT depart.depart,depart.id,factory.id,factory.depart as factory
				from depart
				left join depart as factory
				on depart.fid=factory.id
				where depart.id=$id";
		$res = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	function getUserRole($uid){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT * from staff_user_role where uid=$uid";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delUserRole($uid){
		$sqlHelper = new sqlHelper();
		$sql = "DELETE FROM staff_user_role where uid=$uid";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function addUserRole($uid,$rid){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO staff_user_role (uid,rid) values ";
		for ($i=0; $i < count($rid)-1; $i++) { 
				$sql .= "($uid,{$rid[$i]}),";
		}
		$sql = substr($sql,0,-1);
		$res = $sqlHelper->dml($sql);
		return $res;
	}

	function getUserDpt($uid){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT depart as name,id,pid as pId,uid,IF(uid,'true','false') as checked
				from depart
				left join 
				(
				SELECT * FROM staff_user_dpt where uid = $uid
				) user_dpt
				on depart.id=user_dpt.dptid";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delUserDpt($uid){
		$sqlHelper = new sqlHelper();
		$sql = "DELETE from staff_user_dpt where uid = $uid";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function addUserDpt($uid,$dptid){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO staff_user_dpt (uid,dptid) values ";
		for ($i=0; $i < count($dptid)-1; $i++) { 
			$sql .= "($uid,{$dptid[$i]}),";
		}
		$sql = substr($sql,0,-1);
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

}
?>