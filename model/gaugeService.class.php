<?php 
header("content-type:text/html;charset=utf-8");
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
require_once 'classifyBuild.php';
class gaugeService{
	public $authWhr = "";
	public $authAnd = "";
	public $install = "";
	function __construct(){
		$sqlHelper=new sqlHelper();
		$upid=$_SESSION['dptid'];
		$pmt=$_SESSION['permit'];
		switch ($pmt) {
			case '0':
			case 'a':
			case 'b':
				$this->authWhr="";
				$this->authAnd="";
				$this->instal="";
				break;
			case '1':
				$sql="select id from depart where id=$upid or path in('%-{$upid}','%-{$upid}-%')";
				$upid=$sqlHelper->dql_arr($sql);
				$upid=implode(",",array_column($upid,'id'));
				$this->authWhr=" where gauge_spr_bsc.depart in(".$upid.") ";
				$this->authAnd=" and gauge_spr_bsc.depart in(".$upid.") ";
				$this->install=" ";
				break;
			case '2':
				$this->authWhr=" where gauge_spr_bsc.depart=$upid ";
				$this->authAnd=" and gauge_spr_bsc.depart=$upid ";
				$this->instal = " trsf=$upid ";
				break;
		}
		$sqlHelper->close_connect();	
	}


	// 获取所在部门所有备件申报
	function buyBsc($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT createtime, factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id,apvtime,apvinfo
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user ".$this->authWhr." order by gauge_spr_bsc.see desc,id desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user ".$this->authWhr;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyBscFind($createTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$bsc = "";
		if (!empty($createTime)) {
			$bsc .= " createtime like '{$createTime}%' ";
		}
		if (!empty($depart)) {
			if ($bsc != "") {
				$bsc .= " and gauge_spr_bsc.depart=$depart ";
			}else{
				$bsc .= " gauge_spr_bsc.depart=$depart ";
			}
		}

		// dtl为空
		if ($dtl == "") {
			$where = $bsc;
		}else{
			if ($bsc == "") {
				$where = " gauge_spr_bsc.id in (SELECT basic from gauge_spr_dtl where $dtl) "; 
			}else{
				$where = " ( gauge_spr_bsc.id in (SELECT basic from gauge_spr_dtl where $dtl ) or (".$bsc.")) ";
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT createtime, factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id,apvtime,apvinfo
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user 
				 WHERE ".$where.$this->authAnd." order by gauge_spr_bsc.see desc,id desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user WHERE ".$where.$this->authAnd;
		
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	public function findWhere($code,$name,$no){
		$where = "";
		if (!empty($code)) {
			$where .= " code=$code ";
		}
		if (!empty($name)) {
			if ($where != "") {
				$where .= " and name='{$name}' ";
			}else{
				$where .= " name='{$name}' ";
			}
		}
		if (!empty($no)) {
			if ($where != "") {
				$where .= " and no='{$no}' ";
			}else{
				$where .= " no='{$no}' ";
			}
		}
		return $where;
	}


	// 获取测量记录部门编号
	function getCLJL($dptid){
		$sqlHelper = new sqlHelper();
		$sql="select num from gauge_dpt_num where depart = $dptid";

		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res['num'];
	}

	function buyAdd($CLJL,$applytime,$dptid,$gaugeSpr,$uid){
		//  [code] => 123456 [name] => test1 [no] => 125kkk [unit] => 个 [num] => 4 [info] => 无
		$sqlHelper=new sqlHelper();
		// 将申报表基本信息加到basic表中
		$sql="insert into gauge_spr_bsc (depart, user, cljl, createtime) values ($dptid, $uid, '{$CLJL}', '{$applytime}')";
		$res=$sqlHelper->dml($sql);
		$bscid=mysql_insert_id();
		$sql="insert into gauge_spr_dtl (code,name,no,unit,num,info,basic,res) values ";
		for ($i=1; $i <= count($gaugeSpr); $i++) { 
			if ($i != count($gaugeSpr)) {
				$sql .= "('{$gaugeSpr[$i]['code']}', '{$gaugeSpr[$i]['name']}', '{$gaugeSpr[$i]['no']}', '{$gaugeSpr[$i]['unit']}', {$gaugeSpr[$i]['num']}, '{$gaugeSpr[$i]['info']}', $bscid, 0), ";
			}else{
				$sql .= "('{$gaugeSpr[$i]['code']}', '{$gaugeSpr[$i]['name']}', '{$gaugeSpr[$i]['no']}', '{$gaugeSpr[$i]['unit']}', {$gaugeSpr[$i]['num']}, '{$gaugeSpr[$i]['info']}', $bscid, 0) ";
			}
		}
		$res=$sqlHelper->dml($sql);
		$this->flowLog($applytime,1,$bscid);
		$sqlHelper->close_connect();
		return $res;
	}
	// 根据备件申报的基本信息id获取详细信息，用于展开
	function getBuyDtl($basic){
		$sqlHelper = new sqlHelper();
		$sql = "select code,id,info,name,no,num,unit,see from gauge_spr_dtl where basic = $basic";
		$res=$sqlHelper->dql_arr($sql);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function getSprDtl($id){
		$sqlHelper = new sqlHelper();
		$sql="select * from gauge_spr_dtl where id = $id";
		$res = $sqlHelper->dql($sql);
		$res = json_encode($res,JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function uptSprById($code,$id,$info,$name,$no,$num,$unit){
		$sqlHelper = new sqlHelper();
		$sql = "update gauge_spr_dtl set code='{$code}',info='{$info}',name='{$name}',no='{$no}',num='{$num}',unit='{$unit}' where id=$id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 删除单个备件申报信息
	function delSprById($id){
		$sqlHelper = new sqlHelper();
		$sql = "delete from gauge_spr_dtl where id = $id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delBuy($id){
		$sqlHelper = new sqlHelper();
		$sql = "delete from gauge_spr_bsc where id = $id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 仪表申报审核
	function buyApv($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "select createtime, factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user 
				 where apvtime is null 
				 or (apvtime is not null and apvinfo is not null)
				 ".$this->authAnd." order by id desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "select count(*)
				 from gauge_spr_bsc
				 where apvtime is null 
				 or (apvtime is not null and apvinfo is not null)".$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 仪表流程权限
	function checkAuth($pro,$phase){
		switch ($_SESSION['permit']) {
			case 0:
				$res = 1;
				break;
			case 'a':
				break;
			default:
				$sqlHelper = new sqlHelper();
				$sql = "select count(id) from gauge_pro where pro = '{$pro}' and phase = '{$phase}' and user = '{$_SESSION['uid']}'";
				$res = $sqlHelper->dql($sql);
				$sqlHelper->close_connect();
				$res = $res['count(id)'];
				break;
		}	
		return $res;
	}

	// 备件申报审核
	function apvBuy($apvInfo,$apvRes,$id){
		$sqlHelper = new sqlHelper();
		$time = date("Y-m-d H:i:s");
		if ($apvRes == "同意") {
			$apvInfo = " apvinfo = null ";
			$logRes = 2;
		}else{
			$apvInfo = " apvinfo = '{$apvInfo}' ";
			$logRes = 3;
		}
		$sql = "update gauge_spr_bsc set apvtime='$time',$apvInfo,see=1 where id=$id";
		$res[] = $sqlHelper->dml($sql);
		$sql = "update gauge_spr_dtl set res=1,see=1 where basic=$id";
		$res[] = $sqlHelper->dml($sql);
		$result = in_array(0,$res);
		$sqlHelper->close_connect();
		$this->flowLog($time,$logRes,$id);
		return $result; 
	}

	function buyApvHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id,apvinfo,apvtime
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user where apvtime is not null ".$this->authAnd." order by apvtime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user where apvtime is not null ".$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyApvFind($apvTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$bsc = "";
		if (!empty($apvTime)) {
			$bsc .= " apvtime like '{$apvTime}%' ";
		}
		if (!empty($depart)) {
			if ($bsc != "") {
				$bsc .= " and gauge_spr_bsc.depart=$depart ";
			}else{
				$bsc .= " gauge_spr_bsc.depart=$depart ";
			}
		}

		// dtl为空
		if ($dtl == "") {
			$where = $bsc;
		}else{
			if ($bsc == "") {
				$where = " gauge_spr_bsc.id in (SELECT basic from gauge_spr_dtl where $dtl) "; 
			}else{
				$where = " ( gauge_spr_bsc.id in (SELECT basic from gauge_spr_dtl where $dtl ) or (".$bsc.")) ";
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id,apvinfo,apvtime
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user where ".$where.$this->authAnd." order by apvtime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user where ".$where.$this->authAnd;
		
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 备件申报入厂检定列表
	function buyCheck($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id as id,code,name,no,num,unit,info,depart.depart,factory.depart as factory,0 as checknum
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where res in(1,3) 
				 union
				 SELECT * from 
				 (
				 SELECT gauge_spr_dtl.id as id,code,name,no,num,unit,info,depart.depart,factory.depart as factory,COUNT(sprid) as checkNum
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 GROUP BY id
				 having num!= checknum
				 ) as a
				 order by id desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) FROM
				(
				SELECT gauge_spr_dtl.id,num
				FROM gauge_spr_dtl
				left join gauge_spr_bsc
				on gauge_spr_bsc.id=gauge_spr_dtl.basic
				left join depart
				on gauge_spr_bsc.depart=depart.id
				left join depart as factory
				on depart.fid=factory.id
				where res = 1 
				union
				SELECT gauge_spr_dtl.id,num
				FROM gauge_spr_dtl
				left join gauge_spr_bsc
				on gauge_spr_bsc.id=gauge_spr_dtl.basic
				left join depart
				on gauge_spr_bsc.depart=depart.id
				left join depart as factory
				on depart.fid=factory.id
				right join gauge_spr_check
				on gauge_spr_dtl.id=gauge_spr_check.sprid  where gauge_spr_check.res=2
				GROUP BY sprid
				HAVING count(gauge_spr_check.id)!=num
				) as a";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}


	// 备件申报入厂检定列表
	function buyCheckHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id as id,code,name,no,num,unit,depart.depart,factory.depart as factory,
				 gauge_spr_check.checkTime,count(gauge_spr_check.id) as total
				 FROM gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_check.sprid=gauge_spr_dtl.id
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 group by sprid,checkTime
				 "." order by checkTime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_check";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyCheckFind($checkTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$where = "";
		if (!empty($checkTime)) {
			$where .= " checkTime like '{$checkTime}%' ";
		}

		if (!empty($depart)) {
			if ($where != "") {
				$where .= " and dptid=$depart ";
			}else{
				$where .= " dptid=$depart ";
			}
		}

		if ($dtl != "") {
			if ($where != "") {
				$where .= " and $dtl ";
			}else{
				$where .= $dtl;
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT * FROM (
				 SELECT gauge_spr_dtl.id as id,code,name,no,num,unit,depart.depart,factory.depart as factory,
				 gauge_spr_check.checkTime,count(gauge_spr_check.id) as total,gauge_spr_bsc.depart as dptid
				 FROM gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_check.sprid=gauge_spr_dtl.id
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 group by sprid,checkTime
				 ) a
				 where 
				 ".$where." order by checkTime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";

		$sql2 = "SELECT count(*) FROM (
				 SELECT gauge_spr_dtl.id as id,code,name,no,num,unit,depart.depart,factory.depart as factory,
				 gauge_spr_check.checkTime,count(gauge_spr_check.id) as total,gauge_spr_bsc.depart as dptid
				 FROM gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_check.sprid=gauge_spr_dtl.id
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 group by sprid,checkTime
				 ) a
				 where ".$where;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyStore($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_check.id,codeManu,code,name,no,depart.depart,factory.depart as factory,gauge_spr_check.checkTime,sprid
				 FROM gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_check.sprid=gauge_spr_dtl.id
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where gauge_spr_check.res = 1
				 ".$this->authAnd."order by id desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_check
				 where gauge_spr_check.res = 1";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}


	function storeSpr($storeId,$storeUser,$storeTime){
		$in = implode(",",$storeId);
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE gauge_spr_check set storeTime='{$storeTime}',storeUser='{$storeUser}',res=2 where id in($in)";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 备件入账存库历史
	function buyStoreHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,sprid,code,name,no,codeManu,storeTime,storeUser
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 where gauge_spr_check.res=2
				 order by storeTime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_check
				 where gauge_spr_check.res=2";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyStoreFind($storeTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$where = "";
		if (!empty($storeTime)) {
			$where .= " storetime like '{$storeTime}%' ";
		}
		if (!empty($depart)) {
			if ($where != "") {
				$where .= " and gauge_spr_bsc.depart=$depart ";
			}else{
				$where .= " gauge_spr_bsc.depart=$depart ";
			}
		}

		if ($dtl != "") {
			if ($where != "") {
				$where .= " and $dtl ";
			}else{
				$where .= $dtl;
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_check.id,code,codeManu,name,no,storeTime,sprid
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 left join gauge_spr_bsc
				 on gauge_spr_dtl.basic=gauge_spr_bsc.id
				 where gauge_spr_check.res=2 and ".$where."
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
				 // echo "$sql1";
				 // exit();
		$sql2 = "SELECT count(*) FROM gauge_spr_check 
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 left join gauge_spr_bsc
				 on gauge_spr_dtl.basic=gauge_spr_bsc.id
				 where gauge_spr_check.res=2 and ".$where;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 库存备件
	function buyStoreHouse($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_check.id,code,codeManu,name,no,storeTime,sprid
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 where gauge_spr_check.res=2
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) FROM gauge_spr_check where gauge_spr_check.res=2";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstall($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_check.id,takeTime,codeManu,name,code,no,takeUser,depart.depart,factory.depart factory, gauge_spr_check.sprid
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_check.sprid=gauge_spr_dtl.id
				 left join depart
				 on gauge_spr_check.takeDpt=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where gauge_spr_check.res=3
				".$this->authAnd."
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from gauge_spr_check where res=3 ".$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 入厂检定
	function checkSpr($id,$checkRes,$checkTime){
		if ($checkRes == 3) {
			// 所有检定不合格
			$logRes = 5;
			$res = 1;
			// $sql = "update gauge_spr_dtl set checkTime='{$checkTime}',res=$checkRes,see=1 where id=$id";
		}else{
			// 有检定合格的
			$logRes = 4;
			$sql = "update gauge_spr_dtl set res=$checkRes,see=1 where id=$id";
			$sqlHelper = new sqlHelper();
			$res = $sqlHelper->dml($sql);
			$sqlHelper->close_connect();
		}
		$this->flowLog($checkTime,$logRes,$id);
		$this->bscSee($id);
		return $res;
	}


	// 备件安装验收历史记录
	function buyInstallHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_check.id as id,trsfTime,name,code,codeManu,no,trsfUser,depart.depart,factory.depart factory,gauge_spr_check.res,devid,gauge_spr_check.sprid
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 left join depart
				 on depart.id=gauge_spr_check.trsfDpt
				 left join depart as factory
				 on factory.id=depart.fid
				 where gauge_spr_check.res in(4,5) "
				 .$this->authAnd."order by trsfTime desc  limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_check
				 left join depart
				 on depart.id=gauge_spr_check.trsfDpt
				 left join depart as factory
				 on factory.id=depart.fid
				 where gauge_spr_check.res in(4,5) "
				 .$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstallFind($installTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$where = "";
		if (!empty($installTime)) {
			// if ($dtl != "") {
			// 	$where .= " and installtime='{$installTime}%' ";
			// }else{
				$where .= " trsfTime like '{$installTime}%' ";
			// }
		}
		if (!empty($depart)) {
			if ($where != "") {
				$where .= " and trsfDpt=$depart ";
			}else{
				$where .= " trsfDpt=$depart ";
			}
		}

		if ($dtl != "") {
			if ($where != "") {
				$where .= " and $dtl ";
			}else{
				$where .= $dtl;
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_check.id as id,trsfTime,name,code,codeManu,no,trsfUser,depart.depart,factory.depart factory,gauge_spr_check.res,devid,gauge_spr_check.sprid
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 left join depart
				 on depart.id=gauge_spr_check.trsfDpt
				 left join depart as factory
				 on factory.id=depart.fid 
				 where gauge_spr_check.res in(4,5) and
				 ".$this->install.$where." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_check
				 left join gauge_spr_dtl
				 on gauge_spr_dtl.id=gauge_spr_check.sprid
				 left join depart
				 on depart.id=gauge_spr_check.trsfDpt
				 where gauge_spr_check.res in(4,5) and
				 ".$where.$this->install;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function addSprInCk($sprId,$check,$time){
		$sqlHelper = new sqlHelper();
		$sql = "insert into gauge_spr_check (sprid,supplier,accuracy,scale,codeManu,circle,checkDpt,checkComp,checkNxt,track,certi,checkTime,checkUser,res) 
				values ";
		for ($i=1; $i <= count($check); $i++) { 
			if ($check[$i]['who'] == 'out') {
				$check[$i]['dptCk'] = 'null';
			}
			if ($i != count($check)) {
				$sql .= "($sprId,'{$check[$i]['supplier']}',{$check[$i]['accuracy']},'{$check[$i]['scale']}','{$check[$i]['codeManu']}',{$check[$i]['circle']},{$check[$i]['dptCk']},'{$check[$i]['checkComp']}','{$check[$i]['checkNxt']}','{$check[$i]['track']}','{$check[$i]['certi']}','{$time}','{$check[$i]['checkUser']}',1), ";
			}else{
				$sql .= "($sprId,'{$check[$i]['supplier']}',{$check[$i]['accuracy']},'{$check[$i]['scale']}','{$check[$i]['codeManu']}',{$check[$i]['circle']},{$check[$i]['dptCk']},'{$check[$i]['checkComp']}','{$check[$i]['checkNxt']}','{$check[$i]['track']}','{$check[$i]['certi']}','{$time}','{$check[$i]['checkUser']}',1) ";
			}
		}
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		$this->flowLog($time,10,$sprId);
		return $res;
	}

	function getCkInfo($checkTime,$sprId){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT name,no,accuracy,scale,codeManu,supplier,circle,depart.depart,checkNxt,track,certi 
				from gauge_spr_check
				left join depart
				on checkDpt=depart.id
				left join gauge_spr_dtl
				on gauge_spr_check.sprid=gauge_spr_dtl.id
				where sprid=$sprId and checkTime = '{$checkTime}'";
		$res = $sqlHelper->dql_arr($sql);
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}


	function takeSpr($takeUser,$id,$dptId,$takeAdmin,$takeTime){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE gauge_spr_check set takeUser='{$takeUser}',takeDpt=$dptId,takeAdmin='{$takeAdmin}',takeTime='{$takeTime}',res=3 where id in($id)";
		$res = $sqlHelper->dml($sql);

		$sql = "SELECT sprid from gauge_spr_check from id in ($id)";
		$sprId = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		foreach ($sprId as $k => $v) {
			$this->flowLog($takeTime,7,$v['sprid']);
		}
		return $res;
	}

	// 获取库存的入账、领取、再领取时间等信息
	function getStoreInfo($id){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT supplier,accuracy,scale,`circle`,checkNxt,depart.depart,factory.depart as factory,track,certi,storeUser 
				from  gauge_spr_check 
				left join depart
				on depart.id=gauge_spr_check.checkDpt
				left join depart as factory
				on depart.fid=factory.id	
				where gauge_spr_check.id=$id";
		$res = $sqlHelper->dql($sql);
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}

	function transSpr($id,$num,$state,$pid,$installTime){
		$sqlHelper = new sqlHelper();
		$depart = $_SESSION['dptid'];
		$sql = "select fid from depart where id=$depart";
		$fct = $sqlHelper->dql($sql);

		if ($state == "正常") {
			$sql = "INSERT INTO device 
					(name,code,no,class,factory,depart,state,`number`,supplier,dateInstall,pid)
					select name,code,no,'仪表',{$fct['fid']},$depart,'{$state}',$num,supplier,'{$installTime}',0
					from gauge_spr_dtl
					left join gauge_spr_check
					on gauge_spr_check.sprid=gauge_spr_dtl.id
					where gauge_spr_check.id=$id";
					// echo "$sql";
					// exit();

			$result = 4;
			$logRes  = 8;
		}else{
			// 获取备件所在分厂 device基本信息表
			// ,accuracy,scale,codeManu,`circle`,checkDpt,checkNxt,track,certi
			$sql = "INSERT INTO device 
					(name,code,no,class,factory,depart,state,`number`,supplier)
					select name,code,no,'仪表',{$fct['fid']},$depart,'{$state}',$num,supplier
					from gauge_spr_dtl
					left join gauge_spr_check
					on gauge_spr_check.sprid=gauge_spr_dtl.id
					where gauge_spr_check.id=$id";
			$result = 5;
			$logRes = 9;
			
		}
		$res = $sqlHelper->dml($sql);
		$devid = mysql_insert_id();

		// check表里原来填写好的detail
		$sql = "select sprid,accuracy,scale,codeManu,circle,checkDpt,checkNxt,track,certi from gauge_spr_check where id=$id";
		$r = $sqlHelper->dql($sql);

		// 属性参数表
		$sql = "INSERT INTO devdetail (devid,paraid,paraval) values 
				($devid, 79, '{$r['accuracy']}'),
				($devid, 80, '{$r['scale']}'),
				($devid, 81, '{$r['codeManu']}'),
				($devid, 82, '{$r['circle']}'),
				($devid, 83, '{$r['checkDpt']}'),
				($devid, 84, '{$r['checkNxt']}'),
				($devid, 86, '{$r['track']}'),
				($devid, 87, '{$r['certi']}')";
		$res = $sqlHelper->dml($sql);

		// 添加到原备件check表中
		$trsfDpt = $_SESSION['dptid'];
		$trsfUser = $_SESSION['user'];
		$trsfTime = date("Y-m-d H:i:s");
		$sql = "UPDATE gauge_spr_check set devid=$devid,res=$result,trsfUser='{$trsfUser}',trsfTime='{$trsfTime}',trsfDpt=$trsfDpt where id=$id";
		$res = $sqlHelper->dml($sql);

		$sqlHelper->close_connect();
		$this->flowLog($installTime,$logRes,$r['sprid']);
		return $devid;
	}

	function useDtl($devId,$para){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO devdetail (devid,paraid,paraval) values ";
		foreach ($para as $k => $v) {
			if ($v == end($para)) {
				$sql .= " ($devId,$k,'{$v}') ";
			}else{
				$sql .= "($devId,$k,'{$v}'),";
			}
		}
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}


	function installXls($devId){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT count(*) from gauge_spr_install where devid=$devId";
		$res = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res['count(*)'];
	}

	function installInfo($conclude,$installInfo,$location,$paraInfo,$devId,$runInfo){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO gauge_spr_install (devid,conclude,installInfo,location,paraInfo,runInfo) values
			  	($devId,'{$conclude}','{$installInfo}','{$location}','{$paraInfo}','{$runInfo}')";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function installDown($devId){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT if(a.num is null,b.num,a.num) as cljl,name,no,location,parainfo,installInfo,runinfo,conclude,
				c.paraval as scale,d.paraval as codeManu,
				depart.depart,factory.depart as factory
				from gauge_spr_install
				left join device
				on gauge_spr_install.devid=device.id
				left join gauge_dpt_num as a
				on a.depart=device.depart
				left join gauge_dpt_num as b
				on b.depart=device.factory
				left join devdetail as c
				on c.devid=device.id
				left join depart
				on device.depart=depart.id
				left join devdetail as d
				on d.devid=device.id
				left join depart as factory
				on factory.id=device.factory
				where c.paraid=80 and d.paraid=81 and device.id=$devId
				";
		$res = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function flowLog($time,$res,$id){
		$user = $_SESSION['user'];
		$logHelper = new sqlHelper();
		if ($res >= 1 && $res <= 3) {
			$sql = "INSERT INTO gauge_spr_log (`time`,user,res,sprid) 
					select '{$time}' as time,'{$user}' as user, $res as res, id from (
					select id from gauge_spr_dtl where basic =$id
					) as a";
		}else{
			$sql = "INSERT into gauge_spr_log (`time`,user,res,sprid) values ('{$time}','{$user}',$res,$id)";
		}
		$res = $logHelper-> dml($sql);
		$logHelper->close_connect();
	}

	function getFlowInfo($id){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT time,user,gauge_spr_log.res,gauge_spr_check.id,IF(gauge_spr_log.res=10,codeManu,NULL) as codeManu,
				IF(gauge_spr_log.res=8 or gauge_spr_log.res = 9,devid,NULL) as devid
				from gauge_spr_log
				left join gauge_spr_check
				on gauge_spr_check.sprid = gauge_spr_log.sprid
				where gauge_spr_log.sprid = $id";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		$res = json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	function seeSpr($sprId){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE gauge_spr_dtl set see = 0 where id=$sprId";
		$res = $sqlHelper->dml($sql);

		// 判断同意申报单下是否还有未读，若没有则更改bscid的see值
		$sql = "CALL seeAll($sprId)";
		$res = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		$res = json_encode($res,JSON_UNESCAPED_UNICODE);
		return $res;
	}

	function bscSee($sprId){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE gauge_spr_bsc set see=1 where id 
				= (SELECT basic from gauge_spr_dtl where id=$sprId)";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
	}

	function sprDown($dev){
		$sqlHelper = new sqlHelper();
		$devIn = implode(",",$dev);
		$sql = "SELECT device.name,device.no,device.supplier,depart.depart,factory.depart as factory,device.state,device.dateInstall,device.id,pdev.name as pname
				from device
				left join depart 
				on depart.id=device.depart
				left join depart as factory
				on depart.fid=factory.id
				left join device as pdev
				on device.pid=pdev.id
				where device.id in($devIn) ";
		$info = $sqlHelper->dql_arr($sql);
		$res = array_combine($dev,$info);
		for ($i=0; $i < count($dev); $i++) { 
			$sql = "SELECT paraval,paraid as para
					from devdetail
					where devid=$dev[$i]";
			// $res[$dev[$i]]['para'] = $sqlHelper->dql_arr($sql);	
			$detail = $sqlHelper->dql_arr($sql);
			for ($j=0; $j < count($detail); $j++) { 
				$para[$detail[$j]['para']] = $detail[$j]['paraval'];
			}
			$res[$dev[$i]]['para'] = $para;
		}
		$sqlHelper->close_connect();
		return $res;
	}

	function getCLJLByDev($devid){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT gauge_dpt_num.num from gauge_dpt_num
				left join device
				on gauge_dpt_num.depart=device.factory
				where device.id=$devid";
		$res = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res['num'];
	}

	function array_iconv($arr, $in_charset="utf-8", $out_charset="gb2312")
	{
	  $ret = eval('return '.iconv($in_charset,$out_charset,var_export($arr,true).';'));
	  return $ret;
	  // 这里转码之后可以输出json
	  //  return json_encode($ret);
	}

}
?>