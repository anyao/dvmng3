<?php
require_once "sqlHelper.class.php";
require_once 'paging.class.php';
header("content-type:text/html;charset=utf-8");
class spareService{
	public $authDpt = "";

	function __construct(){
		if ($_SESSION['user'] == 'admin') {
			$this->authDpt = "";
		}else{
			$arrDpt = implode(",",$_SESSION['dptid']);
			$this->authDpt = " in($arrDpt) ";
		}
	}

	// 搜索备件
	function findSpare($brand,$devid,$name,$no,$paging){
		$sqlHelper=new sqlHelper();
		$sql1="select id,code,name,no,brand,depart,factory from device ";
		$where="where state= '备用' ";
		if (!empty($devid)) {
			$where.="and id='{$devid}' ";

		}else{
			if (!empty($brand)) {
				$where.=" and brand like '%{$brand}%' ";
			}
			if (!empty($name)) {
				$where.="and name like '%{$name}%' ";
			}
		}
		$sql1.=$where." and depart ".$this->authDpt." limit ".($paging->pageNow-1)*$paging->pageSize.",".$paging->pageSize;
		$sql2="select count(id) from device ".$where." and depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 根据左侧导航显示搜索结果
	function getByClass($typeId,$typeName,$paging){
		$sqlHelper=new sqlHelper();
		$sql="select name from devtype where id=$typeId or path like '{$typeId}-%' or path like '%-{$typeId}-%'";
		$type=$sqlHelper->dql_arr($sql);
		$sql1="select device.id,code,name,no,brand,depart.depart,factory.depart as factory from device 
			   left join depart
			   on depart.id=device.depart
			   left join depart as factory
			   on factory.id=device.factory
			   where state='备用' and (";
		$where="";
		for ($i=0; $i < count($type); $i++) { 
			if ($i!=count($type)-1) {
				$where.=" class='{$type[$i]['name']}' or ";
			}else{
				$where.=" class='{$type[$i]['name']}' ) ";
			}
		}
		$sql1.=$where." and device.depart ".$this->authDpt." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2="select count(id) from device where state='备用' and (".$where." and device.depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 根据页数限制取设备记录
	function getPaging($paging){
		$sqlHelper=new sqlHelper();
		$sql1="SELECT device.id,code,name,no,brand,depart.depart,factory.depart as factory from device
			   left join depart
			   on device.depart=depart.id
			   left join depart as factory
			   on device.factory=factory.id
			   where state= '备用'
			   AND device.depart ".$this->authDpt." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";	
		$sql2="SELECT count(device.id) from device
			   where state='备用' 
			   and device.depart ".$this->authDpt;
		$sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();	
	}

	function addSpare($brand,$class,$code,$dateEnd,$dateManu,$depart,$factory,$name,$no,$number,$price,$supplier){
		if($number==""){
			$number=1;
		}
		$sql="INSERT into device (brand,class,code,dateEnd,dateManu,depart,factory,name,no,number,price,supplier,state) 
			  values ('{$brand}','{$class}','{$code}','{$dateEnd}','{$dateManu}','{$depart}','{$factory}','{$name}','{$no}','{$number}','{$price}','{$supplier}','备用')";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delSpare($id){
		$sqlHelper=new sqlHelper();
		$sql="delete from device where id=$id";
		$res[]=$sqlHelper->dml($sql);
		$sql="delete from devdetail where devid=$id";
		$res[]=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 根据id获取设备信息
	function getSprById($id){
		$sqlHelper=new sqlHelper();
		$sql="select * from device where id=$id";
		$arr[0]=$sqlHelper->dql($sql);

		$sql="select devdetail.id as paraid,name,paraval  
			  from devdetail 
			  left join devpara
			  on devdetail.paraid=devpara.id
			  where devid=$id";
		$arr[1]=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $arr;
	}


	// 计算到现在过期还有多少天（可能还会改成年）
	function timediff($end_time ){
		$begin_time=date("Y-m-d");
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

	// 将备用设备转到在用设备列表
	function toUsing($id,$pid){
		$sqlHelper=new sqlHelper();
		$dateInstall=date("Y-m-d");
		$sql="select path from device where id=$pid";
		$f_path=$sqlHelper->dql($sql);
		$res[]=$f_path['path'];
		$path=$f_path['path']."-".$pid;
		$sql="update device set state='正常', dateInstall='{$dateInstall}',pid=$pid,path='$path' where id=$id";
		$res[]=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	//获得所选择父设备的path，并组成新的path
	function newPath($pid){
		$sql="select path from device where id=$pid";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		$str=$res[0];
		$n_path="$str".$pid."-";
		return $n_path;
	}

	// 拆分设备
	function dvd($dvd,$id){
		$liable=$dvd[0][0];
		$time=$dvd[0][1];
		$info=$dvd[0][2];
		$count=count($dvd);
		$sqlHelper=new sqlHelper();
		for ($i=1; $i < $count; $i++) { 
			$divide.=$dvd[$i][0]." ".$dvd[$i][1]." ".$dvd[$i][2].",";
		}
		$sql="update device set dvdinfo ='{$liable} {$time} {$info}',divide='{$divide}' where id=$id";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function updateSpare($brand,$class,$code,$dateEnd,$dateInstall,$dateManu,$factory,$id,$name,$no,$number,$para,$periodVali,$price,$supplier){
		$sqlHelper=new sqlHelper();
		$sql="update device set brand='{$brand}',class='{$class}',code='{$code}',dateEnd='{$dateEnd}',dateInstall='{$dateInstall}',dateManu='{$dateManu}',factory='{$factory}',name='{$name}',no='{$no}',number='{$number}',periodVali='{$periodVali}',price='{$price}',supplier='{$supplier}' where id=$id";
		$res[]=$sqlHelper->dml($sql);
		foreach ($para as $key => $value) {
			// [131] => AC220V [132] => 绿 ) Array ( [131] => AC220V [132] => 绿 
			$sql="update devdetail set paraval='{$value}' where id=$key";
			$res[]=$sqlHelper->dml($sql);
		}
		$sqlHelper->close_connect();
		return $res;
	}

	function getTypeDad(){
		$sql="select name,id from devtype where pid is null";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getTypePa($id){
		$sqlHelper=new sqlHelper();
		$sql="select path from devtype where id=$id";
		$res=$sqlHelper->dql($sql);
		$path=explode("-",$res['path']);
		for ($i=0; $i < count($path)-1; $i++) { 
			$sql="select name,id from devtype where id=$path[$i]";
			$info[]=$sqlHelper->dql($sql);
		}
		$sqlHelper->close_connect();
		return $info;
	}

	function getType($pid){
		$sql="select name,id from devtype where pid=$pid";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getTypeAll(){
		$sql="select name from devtype";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res= json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	function addType($pname,$sname){
		$sql="select id,path from devtype where name='{$pname}'";
		$sqlHelper=new sqlHelper();
		$parent=$sqlHelper->dql($sql);
		$pid=$parent['id'];
		$ppath=$parent['path'];
		$path=$path.$pid."-";
		$sql="insert into devtype (name,pid,path) values('{$sname}','{$pid}','{$path}')";
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delType($name){
		$sql="delete from devtype where name='{$name}'";
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getDev(){
		$sql="SELECT name,id from device where state='正常' AND depart ".$this->authDpt;
		$sqlHelper=new sqlHelper();
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res= json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	// 拼装设备
	function tgther($class,$code,$depart,$devid,$factory,$info,$liable,$name,$no,$tgther,$time){
		$sqlHelper=new sqlHelper();
		$tgtherInfo=$liable.",".$time.",".$info.",";
		for ($i=0; $i < count($tgther); $i++) { 
			$tgtherInfo.=$tgther[$i].",";
		}
		$sql="insert into device (class,code,depart,factory,name,no,tgther,state) values('{$class}','{$code}','{$depart}','{$factory}','{$name}','{$no}','{$tgtherInfo}','备用')";
		$res[]=$sqlHelper->dml($sql);

		// 获得最新拼装得到的设备id
		$sql="select id from device order by id desc limit 0,1";
		$n_id=$sqlHelper->dql($sql);

		$o_tgtherInfo=$liable.",".$time.",".$info.",".$n_id['id'];


		$sql="update device set tgther='{$o_tgtherInfo}' where id=$devid";
		$res[]=$sqlHelper->dml($sql);

		$sqlHelper->close_connect();
		return $res;
	}

	// 获得所有备件的名称
	function getNameAll(){
		$sqlHelper=new sqlHelper();
		$sql="SELECT name,id from device where state='备用' and depart ".$this->authDpt;
		$res=$sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res= json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}


}
?>