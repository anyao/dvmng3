<?php
header("content-type:text/html;charset=utf-8");
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
require_once 'classifyBuild.php';
class devService{
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

	function getPaging($paging){	
		$sqlHelper=new sqlHelper();
		$sql1="	select id,code,name,state,insp,rep,dateInstall,dateEnd
				from device 
				left join 
				(select time as insp,devid from insplist where id in (select max(id) from insplist group by devid)) as insptime
				on device.id=insptime.devid
				left join 
				(select time as rep,devid from replist where id in (select max(id) from replist group by devid)) as replist
				on device.id=replist.devid
				where device.pid=0 and state in ('正常','停用')".$this->authAnd."
				order by device.id
			  	limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(*) from device where pid='0' and state in ('正常','停用')".$this->authAnd;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	// 搜索所有部门
	function getDptAll($idFct){
		$sqlHelper=new sqlHelper();
		$sql="select depart,id from depart where path like '-{$idFct}-%' or path='-{$idFct}'";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 搜索所有分厂
	function getFctAll(){
		$sqlHelper=new sqlHelper();
		$sql='select depart as factory,id from depart where path is null';
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 修改设备信息
	function updateDev($brand,$class,$code,$dateEnd,$dateInstall,$dateManu,$depart,$factory,$id,$number,$name,$no,$periodVali,$pid,$price,$supplier){

		if ($pid==0) {
			$sql="update device set brand='{$brand}', class='{$class}', code='{$code}', dateEnd='$dateEnd', dateInstall='{$dateInstall}', dateManu='{$dateManu}', depart='{$depart}', factory='{$factory}', number='{$number}', name='{$name}', no='{$no}', periodVali='{$periodVali}', pid='{$pid}', price='{$price}', supplier='{$supplier}' where id='{$id}';";
		}else{
			// 得出新的父节点路径
			$n_path=self::updatePath($id,$pid);
			$sql="update device set brand='{$brand}', class='{$class}', code='{$code}', dateEnd='$dateEnd', dateInstall='{$dateInstall}', dateManu='{$dateManu}', depart='{$depart}', factory='{$factory}', number='{$number}', name='{$name}', no='{$no}', periodVali='{$periodVali}', pid='{$pid}', price='{$price}', supplier='{$supplier}',path='{$n_path}' where id='{$id}';";
		}
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	//更改设备父节点路径 由于修改设备的所属设备时 made it
	function updatePath($id,$n_pid){
		$sql="select path from device where id=(select pid from device where id=$id)";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		$n_path="{$res[path]}"."-".$n_pid;
		return $n_path;
	}

	// 根据id获取设备信息
	function getDevById($id){
		$sql="select a.*,b.name as parent
			  from 
			  (select device.`id`, `name`, `code`, `no`, `class`, `state`, `dateManu`, `dateInstall`, `periodVali`, `dateEnd`, `number`, `brand`, device.`pid`, `price`, `supplier`, device.`path`, `dvdinfo`, `divide`, `tgther`,device.depart as did,device.factory as fid,
			  depart.depart,factory.depart as factory
			  from device
			  inner join depart
			  on device.depart=depart.id
			  inner join depart as factory
			  on device.factory=factory.id)
			  as a
			  left join 
			  (select device.`id`, `name`, `code`, `no`, `class`, `state`, `dateManu`, `dateInstall`, `periodVali`, `dateEnd`, `number`, `brand`, device.`pid`, `price`, `supplier`, device.`path`, `dvdinfo`, `divide`, `tgther`,
			  depart.depart,factory.depart as factory
			  from device
			  inner join depart
			  on device.depart=depart.id
			  inner join depart as factory
			  on device.factory=factory.id)
			  as b
			  on a.pid=b.id
			  where a.id=$id;";
		$sqlHelper=new sqlHelper();
		$arr[]=$sqlHelper->dql($sql);

		if (empty($arr[0]['path'])){
			$idRoot=$arr[0]['id'];
		}else{
			$path=explode("-",$arr[0]['path']);
			$idRoot=$path[1];
		}
		$sql="select name,uid
			  from dev_user
			  left JOIN `user`
			  on `user`.id=dev_user.uid
			  where dev_user.devid=$idRoot
			  and dev_user.end is null";
		$liable=$sqlHelper->dql_arr($sql);
		// print_r($liable);
		// exit();
		$arr[]=$liable;
		$sqlHelper->close_connect();
		return $arr;
	}

	// 获得所有设备的名称用于using页面的父设备
	function getDevAll(){
		$sqlHelper=new sqlHelper();
		$sql="select name,depart.depart,factory.depart as factory,device.id 
			from device
			inner join depart 
			on depart.id=device.depart
			inner join depart as factory
			on factory.id=device.factory".$this->authWhr;
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得设备id
	function getId(){
		$sql="select id from device order by id desc limit 0,1";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		return $res['id'];
	}

	// 添加子设备信息
	function addCld($brand,$class,$code,$dateInstall,$dateManu,$depart,$factory,$number,$name,$no,$periodVali,$pid,$price,$supplier){
		if($number==""){
			$number=1;
		}
		$sql="select path from device where id=$pid";
		$sqlHelper=new sqlHelper();
		$pathPrt=$sqlHelper->dql($sql);
		$path=$pathPrt['path']."-".$pid;

		$sql="insert into device (brand,class,code,dateInstall,dateManu,depart,factory,number,name,no,periodVali,pid,price,supplier,path,state) values('$brand','$class','$code','$dateInstall','$dateManu','$depart','$factory','$number','$name','$no','$periodVali','$pid','$price','$supplier','$path','正常')";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 更换新的设备
	function chgeDev($oid,$n_brand,$n_dateInstall,$n_dateManu,$n_periodVali,$n_price,$n_supplier,$info){
		$sqlHelper=new sqlHelper();
		// 复制原设备信息
		$sql="insert into device 
		(class,code,depart,factory,number,name,no,pid,price,supplier,path,state) select 
		class,code,depart,factory,number,name,no,pid,price,supplier,path,state from device where id=$oid";
		$res=$sqlHelper->dml($sql);
		// 修改原设备的状态为更换
		$sql="update device set state='更换',dateEnd='{$n_dateInstall}' where id=$oid";
		$res=$sqlHelper->dml($sql);
		// 取出新添加设备的id
		$sql="select id from device order by id desc limit 0,1";
		$nid=$sqlHelper->dql($sql);
		// $nid['id']
		// 将新设备其他信息更新
		$sql="update device set brand='{$n_brand}',dateInstall='{$n_dateInstall}',dateManu='{$n_dateManu}',periodVali='{$n_periodVali}',price='{$n_price}',supplier='{$n_supplier}' where id={$nid['id']}";
		$res=$sqlHelper->dml($sql);
		// 设备更换表中新关系添加，先看看旧设备之前是否有设备更换记录
		$sql="select opath from chgdev where nid=$oid";
		$res=$sqlHelper->dql($sql);
		// 添加新的更换记录
		$n_path=$res['opath'].",".$oid;
		$sql="insert into chgdev (opath,oid,nid,info) values ('{$n_path}',$oid,{$nid['id']},'{$info}')";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $nid['id'];
	}

	function chgeDetail($nid,$oid){
		$o_detail=self::getDetail($oid);
		if (empty($o_detail)) {
			$res=1;
		}else{
			$sqlHelper=new sqlHelper();
			$sql="insert into devdetail (devid,paraid,paraval) values ";
			for ($i=0; $i < count($o_detail); $i++) { 
				if ($i!=count($o_detail)-1) {
					$sql.="($nid,'{$o_detail[$i]['paraid']}','{$o_detail[$i]['paraval']}'),";
				}else{
					$sql.="($nid,'{$o_detail[$i]['paraid']}','{$o_detail[$i]['paraval']}')";
				}
			}
			$res=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}



	// 添加父节点
	function addPrt($brand,$class,$code,$dateInstall,$dateManu,$depart,$name,$no,$periodVali,$price,$supplier,$liable,$factory){
		// 将数据表插入进设备表
		$sql="insert into device (brand,class,code,dateInstall,dateManu,depart,name,no,periodVali,price,supplier,factory,pid,state,number) 
			  values('$brand','$class','$code','$dateInstall','$dateManu','$depart','$name','$no','$periodVali','$price','$supplier','$factory','0','正常',1)";
		$sqlHelper=new sqlHelper();
		$res[]=$sqlHelper->dml($sql);
		// 查询到该条记录id
		$sql="select id from device order by id desc limit 1";
		$devid=$sqlHelper->dql($sql);

		// 插入新的管理员和设备管理关系
		$sql="";
		$sql.="insert into dev_user (devid,uid,time) values ";
		$time=date("Y-m-d",time());
		for ($i=0; $i < count($liable); $i++) { 
			if ($i!=count($liable)-1) {
				$sql.="({$devid['id']},$liable[$i],'{$time}'),";
			}else{
				$sql.="({$devid['id']},$liable[$i],'{$time}')";
			}
		}
		$res[]=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function addDev($brand,$code,$dateInstall,$dateManu,$name,$periodVali,$price,$size,$spec,$supplier,$class,$factory,$depart,$liable,$number,$pid,$path,$no){
		$sql="insert into device (brand,code,dateInstall,dateManu,name,periodVali,price,size,spec,supplier,class,factory,depart,liable,number,pid,path,state,no) values('$brand','$code','$dateInstall','$dateManu','$name','$periodVali','$price','$size','$spec','$supplier','$class','$factory','$depart','$liable','$number','$pid','$path','正常','$no')";

		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 删除选中的设备信息记录
	function delDevById($id){
		$sql1="delete from device where id=$id";
		$sql2="delete from devdetail where devid=$id";
		$sqlHelper=new sqlHelper();
		$res[]=$sqlHelper->dml($sql1);
		$res[]=$sqlHelper->dml($sql2);
		$sqlHelper->close_connect();
		return $res;
	}

	// 检验设备下面是否有子元素
	function IfHasSon($id){
		$sql="select count(id) from device where pid=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		// print_r($res);
		$sqlHelper->close_connect();
		return $res;
	}

	// 查找子设备用于树形列表显示
	function addSon($id){
		$sql="select id,pid,no,name
			  from device
			  where (path like '-{$id}' or path like '-{$id}-%') and state!='更换'";
		$sqlHelper=new sqlHelper();
		$arr=$sqlHelper->dql_arr($sql);
		
		$info="";
		for ($i=0; $i < count($arr); $i++) { 
			$sql="select paraval from devdetail where devid={$arr[$i]['id']} limit 0,1";
			$para=$sqlHelper->dql($sql);
			$res[$i]=array("text"=>"{$arr[$i]['name']}_{$arr[$i]['no']}_{$para['paraval']}","href"=>"usingSon.php?id={$arr[$i]['id']}","tags"=>"{$arr[$i]['id']}","pid"=>"{$arr[$i]['pid']}");
			if ($info != "")
				$info .= ",";
				$info.=json_encode($res[$i],JSON_UNESCAPED_UNICODE);
		}
		$sqlHelper->close_connect();
		return "[".$info."]";
	}

	//获取当前页数
	function getPageCount($pageSize){
		$sql="select count(id) from device".$this->authWhr;
		$sqlHelper=new sqlHelper;
		$res=$sqlHelper->dql($sql);

		if ($row=mysql_fetch_row($res)) {
			$pageCount=ceil($row[0]/$pageSize);
		}
		mysql_free_result($res);
		$sqlHelper->close_connect($pageSize);
		return $pageCount;
	}

	//获取当页数据
	// function getDevListByPage($pageNow,$pageSize){
	// 	$sql="select * from device limit ".($pageNow-1)*$pageSize.",$pageSize";
	// 	$sqlHelper=new sqlHelper();
	// 	$res=$sqlHelper->dql_arr($sql);
	// 	$sqlHelper->close_connect();
	// 	return $res;
	// }



	//计算维修次数
	function frequency($id){
		$sqlHelper=new sqlHelper();
		$sql="select count(id) from insplist where devid=$id";
		$res=$sqlHelper->dql($sql);
		return $res;
	}

	// 计算安装到现在一共有多少天（可能还会改成年）
	function timediff($begin_time,$end_time ){
		if (empty($end_time)) {
			$end_time=date('Y-m-d');
		}
		if ( $begin_time < $end_time ) {
			$starttime = strtotime("$begin_time");
			$endtime = strtotime("$end_time");
		} else {
			$starttime = strtotime("$end_time");
			$endtime = strtotime("$begin_time");
		}
		$timediff = $endtime - $starttime;
		$days = intval( $timediff / 86400 );
		if($days>365){
			return array(round($days/365,2),"年");
		}else{	
			return array($days,"天");
		}
	}


	// 计算小时成本
	function hourCost($begin_time,$end_time,$price){
		if (empty($end_time)) {
			$end_time=date('Y-m-d');
		}
		if ( $begin_time < $end_time ) {
			$starttime = strtotime("$begin_time");
			$endtime = strtotime("$end_time");
		} else {
			$starttime = strtotime("$end_time");
			$endtime = strtotime("$begin_time");
		}
		$timediff = $endtime - $starttime;
		$hours = intval( $timediff / 3600 );
		if ($hours==0) {
			return "";
		}
		return round($price/$hours,2);
	}

	// 停用设备
	function endDev($id){
		$sql="update device set state='停用' where id=$id";
		$sqlHelper=new sqlHelper();
		$res[0]=$sqlHelper->dml($sql);
		$sql="select code,class,factory,depart,liable,number,pid,path,no from device where id=$id";
		$res[1]=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得所有管理员
	function getLiable(){
		$sqlHelper=new sqlHelper();
		$sql="select name,id from user limit 0,10";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获取设备类别
	function getType(){
		$sqlHelper=new sqlHelper();
		$sql="(select name,id,pid from devtype where pid is null) union (select name,id,pid from devtype where pid in (select id from devtype where pid is null)) ";
		$res=$sqlHelper->dql_arr($sql);
		$classify = new classifyBuild($res);  
	    $classify->name = false;  
	    $classify->make();  
	 	$res=$classify->getResult();  
		$sqlHelper->close_connect();
		return $res;
	}

	// 获取机柜类别用于添加属性
	function getTypePrt(){
		$sqlHelper=new sqlHelper();
		$sql="select name,id from devtype where pid is null";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 获取设备类别用于添加属性
	function getTypeSon(){
		$sqlHelper=new sqlHelper();
		$sql="select name,id from devtype where pid in (select id from devtype where pid is null)";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	// 删除父类别
	function delTypePa($id){
		$sql="delete from devType where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加根类别
	function addTypePa($name){
		$sql="insert into devtype (name) values ('{$name}')";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加新的类别
	function addType($pid,$name){
		$sql="insert into devType (name,pid,path) values ('{$name}',$pid,'{$pid}-')";
		$sqlHelper=new sqlHelper();
		$res[]=$sqlHelper->dml($sql);
		$sql="select id from devType where name='{$name}' and pid=$pid";
		$res[]=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 查询该类别下是否有子类别用于备件管理，若有则无法删除
	function sonType($id){
		$sql="select count(id) from devtype where pid=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 修改设备类别名称
	function updateTypeName($id,$name){
		$sql="update devtype set name='{$name}' where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 添加新的参数属性
	function addPara($typeid,$name){
		$sqlHelper=new sqlHelper();
		for ($i=0; $i < count($name); $i++) { 
			$sql="insert into devpara (name,typeid) values('{$name[$i]}',$typeid)";
			$res[$i]=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}

	// 设备类型下的属性参数值
	function getPara($id){
		$sql="select name,id from devpara where typeid=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	// 根据设备类别id删除设备属性参数
	function delParaByType($id){
		$sql="delete from devPara where typeid=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 根据属性参数的id删除设备
	function delPara($id){
		$sql="delete from devPara where id=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function updatePara($para){
		// Array ( [0] => 额定电流 [1] => 额定电流 [2] => P )
		$sqlHelper=new sqlHelper();
		foreach ($para as $key => $value) {
			$sql="update devpara set name='{$value}' where id={$key}";
		 	$res[]=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}

	function addDetail($id,$para){
		$sqlHelper=new sqlHelper();
		foreach ($para as $key => $value) {	
			if (!empty($value)) {	
				$sql="insert into devdetail (devid,paraid,paraval) values ($id,$key,'{$value}')";
				$res[]=$sqlHelper->dml($sql);
			}
		}
		$sqlHelper->close_connect();
		return $res;
	}

	function getDetail($id){
		$sql="select paraval,paraid,device.name,devpara.name from devdetail
			  left join device
			  on device.id=devdetail.devid
			  left join devpara
			  on devpara.id=devdetail.paraid
			  where devid=$id";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 删除设备参数
	function delDetail($devid){
		$sql="delete from devdetail where devid=$devid";
		$sqlHelper=new sqlHelper();
		$res[]=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function updateDetail($paraid,$id){
		$sqlHelper=new sqlHelper();
		foreach ($paraid as $key => $value) {
			$sql="update devdetail set paraval='{$value}' where devid=$id and paraid=$key;";
			$res[]=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}

	// 获得dev_user
	function getCon($id){
		$sql="select dev_user.*,user.name
			  from dev_user
			  left join user 
			  on dev_user.uid=user.id
			  where devid=$id
			  order by dev_user.id desc";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	// 获得当前设备管理员
	function getLia($id){
		$sql="select dev_user.uid,user.name,dev_user.id
			  from dev_user
			  left join user 
			  on dev_user.uid=user.id
			  where devid=$id
			  and end is null";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	// 添加新的设备与管理员关系
	function addCon($devid,$uid){
		$sqlHelper=new sqlHelper();
		$sql="insert into dev_user (devid,uid,time) values";
		$time=date("Y-m-d",time());
		for ($i=0; $i < count($uid); $i++) { 
			if ($i!=count($uid)-1) {
				$sql.="($devid,$uid[$i],'{$time}'),";
			}else{
				$sql.="($devid,$uid[$i],'{$time}')";
			}
			$res=$sqlHelper->dml($sql);
			$sqlHelper->close_connect();
			return $res;
		}
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

	function getRootAll(){
		$sql="SELECT device.id,name,code,depart.depart,factory.depart as factory 
			from device 
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

	// 主页搜索功能
	function findDev($depart,$factory,$keyword,$devid,$office,$paging){
		$where="";
		$location="";

		if (!empty($office)) {
			$location.="depart=$office ";
		}else if (!empty($depart)) {
			$location.="depart=$depart ";
		}

		if (!empty($factory)) {
			if (!empty($location)) {
				$location=" and factory=$factory ";
			}else{
				$location="factory=$factory ";
			}
		}

		// 是否明确设备id
		if (!empty($devid)) {
			$where.="id=$devid ";
		}else if (!empty($keyword)) {
			$where.="name like '%{$keyword}%' ";
			if (!empty($location)) {
				$where.="and $location ";
			}
		}else if (!empty($location)) {
			$where.=$location;
		}

		$sql1="select id,code,name,state,insp,rep,dateInstall,dateEnd
		  	  from device 
			  left join 
			  (select time as insp,devid from insplist where id in (select max(id) from insplist group by devid)) as insptime
			  on device.id=insptime.devid
			  left join 
			  (select time as rep,devid from replist where id in (select max(id) from replist group by devid)) as replist
			  on device.id=replist.devid
			  where pid=0 and ".$where.$this->authAnd
			  ."limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(id) from device 
			   where pid=0 and ".$where.$this->authAnd;
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
		return $res;
	}

	// 取出某一具体设备的更换记录
	function getChgInfo($id){
		$sqlHelper=new sqlHelper();
		$sql="select opath,nid from chgdev where nid=$id or opath like '%,$id,%' or opath like '%,$id' order by id desc limit 0,1";

		$res=$sqlHelper->dql($sql);
		if ($res!="") {
			$path=explode(",",$res['opath']);
			$path[]=$res['nid'];
			for ($i=1; $i < count($path); $i++) { 
				$sql="select info,device.id,device.name,device.dateInstall,device.supplier,device.price,device.dateEnd
					  from device
					  left join chgdev
					  on chgdev.nid=device.id
					  where device.id={$path[$i]}";
					  
				$info[]=$sqlHelper->dql($sql);
			}
		}else{
			$info=false;
		}
		return $info;
	}

	// 停用父设备，包括停用其下子设备
	function stopDev($id){
		$sqlHelper=new sqlHelper();
		// $sql="select id from device where path like '-{$id}%'";
		$sql="select id from device where path in('%-{$id}-%','%-{$id}')";
		$res=$sqlHelper->dql_arr($sql);
		$res=implode(",",array_column($res,'id'));
		$sql="update device set state='停用' where id in(".$res.")";
		// for ($i=0; $i < count($res); $i++) { 
		// 	if ($i!=count($res)-1) {	
		// 		$sql.="id={$res[$i]['id']} or ";
		// 	}else{
		// 		$sql.="id={$res[$i]['id']}";
		// 	}
		// }
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	
	function departNavi(){
		$sqlHelper=new sqlHelper();
		for ($i=1; $i <= 3; $i++) {
			$sql="select depart as name,id,pid from depart  where comp=$i";

			$res[$i]=$sqlHelper->dql_arr($sql);
			$classify = new classifyBuild($res[$i]);  
		    $classify->name = false;  
		    $classify->make();  
		 	$res[$i]=$classify->getResult();  
		}
		$sqlHelper->close_connect();
		return $res;
	}
	
	// 根据分厂获得设备列表
	function getDevByFct($id,$paging){
		$sqlHelper=new sqlHelper();
		$sql1="	select id,code,name,state,insp,rep,dateInstall,dateEnd
			from device 
			left join 
			(select time as insp,devid from insplist where id in (select max(id) from insplist group by devid)) as insptime
			on device.id=insptime.devid
			left join 
			(select time as rep,devid from replist where id in (select max(id) from replist group by devid)) as replist
			on device.id=replist.devid
			where device.pid=0 and state in ('正常','停用')
			and device.factory=$id".$this->authAnd."
			order by device.id
		  	limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(*) from device where device.pid=0 and state in ('正常','停用') and device.factory=$id".$this->authAnd;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	// 根据部门获得设备列表
	function getDevByDpt($id,$paging){
		$sqlHelper=new sqlHelper();
		$sql1="	select id,code,name,state,insp,rep,dateInstall,dateEnd
			from device 
			left join 
			(select time as insp,devid from insplist where id in (select max(id) from insplist group by devid)) as insptime
			on device.id=insptime.devid
			left join 
			(select time as rep,devid from replist where id in (select max(id) from replist group by devid)) as replist
			on device.id=replist.devid
			where device.pid=0 and state in ('正常','停用')
			and device.depart=$id".$this->authAnd."
			order by device.id
		  	limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(*) from device where device.pid=0 and state in ('正常','停用') and device.depart=$id".$this->authAnd;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}
}
?>