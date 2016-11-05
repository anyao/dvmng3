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
				$this->install=" where device.depart in(".$upid.") ";
				break;
			case '2':
				$this->authWhr=" where gauge_spr_bsc.depart=$upid ";
				$this->authAnd=" and gauge_spr_bsc.depart=$upid ";
				$this->instal = " where device.depart=$upid ";
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
			$bsc .= " createtime='{$createTime}%' ";
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
			$bsc .= " apvtime='{$apvTime}%' ";
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
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,info,depart.depart,cljl,factory.depart as factory
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where res in(1,3)
				 ".$this->authAnd." order by gauge_spr_dtl.id desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where res in(1,3)".$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 入厂检定
	function checkSpr($id,$checkRes,$checkTime){
		$sqlHelper = new sqlHelper();
		// 入厂检定不合格
		$sql = "update gauge_spr_dtl set checktime='{$checkTime}', res=$checkRes, see=1 where id=$id";
		$res = $sqlHelper->dml($sql);
		$logRes = $checkRes + 2;
		$sqlHelper->close_connect();
		$this->flowLog($checkTime,$logRes,$id);
		$this->bscSee($id);
		return $res;
	}

	// 备件申报入厂检定列表
	function buyCheckHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,depart.depart,cljl,factory.depart as factory,checktime,res
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where checktime is not null 
				 "." order by checktime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where checktime is not null ";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyCheckFind($checkTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$where = "";
		if (!empty($checkTime)) {
			if ($dtl != "") {
				$where .= " and checktime='{$checkTime}%' ";
			}else{
				$where .= " checktime='{$checkTime}%' ";
			}
		}
		if (!empty($depart)) {
			if ($where != "") {
				$where .= " and gauge_spr_bsc.depart=$depart ";
			}else{
				$where .= " gauge_spr_bsc.depart=$depart ";
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,depart.depart,cljl,factory.depart as factory,checktime,res
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where 
				 ".$where." order by checktime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where".$where;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyStore($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,info,depart.depart,cljl,factory.depart as factory
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where res=2 
				 ".$this->authAnd." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where res=2".$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 备件入账存库
	function storeSpr($id, $storeRes, $storeTime, $num){
		$sqlHelper = new sqlHelper();
		$sql = "update gauge_spr_dtl set storetime='{$storeTime}',res=$storeRes,see=1,resnum=$num where id=$id"; 
		$res = $sqlHelper->dml($sql);
		$this->flowLog($storeTime,6,$id);
		$sqlHelper->close_connect();
		$this->bscSee($id);
		return $res;
	}

	// 备件入账存库历史
	function buyStoreHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,depart.depart,factory.depart as factory,storetime,res
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where storetime is not null
				 ".$this->authAnd." order by storetime desc limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_dtl
				 where storetime is not null".$this->authAnd;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyStoreFind($storeTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$where = "";
		if (!empty($storeTime)) {
			if ($dtl != "") {
				$where .= " and storetime='{$storeTime}%' ";
			}else{
				$where .= " storetime='{$storeTime}%' ";
			}
		}
		if (!empty($depart)) {
			if ($where != "") {
				$where .= " and gauge_spr_bsc.depart=$depart ";
			}else{
				$where .= " gauge_spr_bsc.depart=$depart ";
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,storetime,IFNULL(resnum,0) as resnum,IFNULL(takenum,0)  as takenum
				 from gauge_spr_dtl 
				 left join (
				 select sum(takenum) as takenum,sprid from gauge_spr_take group by sprid
				 ) as take 
				 on take.sprid=gauge_spr_dtl.id 
				 where".$where."
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) FROM
				(
				SELECT gauge_spr_dtl.id,code,name,no,num,unit,storetime,IFNULL(resnum,0) as resnum,IFNULL(takenum,0)  as takenum
				from gauge_spr_dtl 
				left join (
				select sum(takenum) as takenum,sprid from gauge_spr_take group by sprid
				) as take 
				on take.sprid=gauge_spr_dtl.id 
				where ".$where."
				) as a";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 库存备件
	// 备件入账存库历史
	function buyStoreHouse($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,storetime,IFNULL(resnum,0) as resnum,IFNULL(takenum,0)  as takenum
				 from gauge_spr_dtl 
				 left join (
				 select sum(takenum) as takenum,sprid from gauge_spr_take group by sprid
				 ) as take 
				 on take.sprid=gauge_spr_dtl.id 
				 where res=5 HAVING resnum+takenum !=num
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		// echo "$sql1";
		// exit();
		$sql2 = "SELECT count(*) FROM
				(
				SELECT gauge_spr_dtl.id,code,name,no,num,unit,storetime,IFNULL(resnum,0) as resnum,IFNULL(takenum,0)  as takenum
				from gauge_spr_dtl 
				left join (
				select sum(takenum) as takenum,sprid from gauge_spr_take group by sprid
				) as take 
				on take.sprid=gauge_spr_dtl.id 
				where res=5 HAVING resnum+takenum !=num
				) as a";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstall($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,gauge_spr_dtl.code,gauge_spr_dtl.name,no,resnum,unit,storetime
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 where res=5 and resnum!=0 ".$this->authAnd."
				 UNION all
				 select sprid,gauge_spr_dtl.code,gauge_spr_dtl.name,no,takenum,unit,taketime
				 from gauge_spr_take
				 LEFT JOIN gauge_spr_dtl
				 on gauge_spr_take.sprid=gauge_spr_dtl.id
				where gauge_spr_take.res is null ".$this->authAnd."
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT sum(tem) from
				 (
				 SELECT count(*) as tem
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 where res=5 and resnum!=0 ".$this->authAnd."
				 UNION all
				 select count(*) as tem
				 from gauge_spr_take
				 LEFT JOIN gauge_spr_dtl
				 on gauge_spr_take.sprid=gauge_spr_dtl.id
				 where gauge_spr_take.res is null ".$this->authAnd."
				 )
				 as total";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 备件安装验收历史记录
	function buyInstallHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT sprid,devid,no,trsftime,name,code,`number`,state,depart.depart,factory.depart as factory
				 from gauge_spr_trsf
				 left join device
				 on gauge_spr_trsf.devid = device.id
				 left join depart
				 on depart.id=device.depart
				 left join depart as factory
				 on factory.id = device.factory
				 ".$this->install." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_trsf
				 left join device
				 on gauge_spr_trsf.devid = device.id
				 left join depart
				 on depart.id=device.depart
				 left join depart as factory
				 on factory.id = device.factory
				 ".$this->install;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstallFind($installTime,$depart,$code,$name,$no,$paging){
		$dtl = $this->findWhere($code,$name,$no);
		$where = "";
		if (!empty($installTime)) {
			if ($dtl != "") {
				$where .= " and installtime='{$installTime}%' ";
			}else{
				$where .= " installtime='{$installTime}%' ";
			}
		}
		if (!empty($depart)) {
			if ($where != "") {
				$where .= " and gauge_spr_bsc.depart=$depart ";
			}else{
				$where .= " gauge_spr_bsc.depart=$depart ";
			}
		}

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT sprid,devid,no,trsftime,name,code,`number`,state,depart.depart,factory.depart as factory
				 from gauge_spr_trsf
				 left join device
				 on gauge_spr_trsf.devid = device.id
				 left join depart
				 on depart.id=device.depart
				 left join depart as factory
				 on factory.id = device.factory
				 ".$this->install.$where." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_trsf
				 left join device
				 on gauge_spr_trsf.devid = device.id
				 left join depart
				 on depart.id=device.depart
				 left join depart as factory
				 on factory.id = device.factory
				 ".$where.$this->install;
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function addSprInCk($supplier,$accuracy,$scale,$codeManu,$certi,$checkNxt,$circle,$track,$sprId,$dptCk){
		$sqlHelper = new sqlHelper();
		$sql = "insert into gauge_spr_check (sprid,supplier,accuracy,scale,codeManu,circle,checkDpt,checkNxt,track,certi) 
				values($sprId,'{$supplier}',$accuracy,'{$scale}','{$codeManu}',$circle,'{$dptCk}','{$checkNxt}','{$track}','{$certi}')";
		$res[] = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getCkInfo($sprId){
		$sqlHelper = new sqlHelper();
		$sql = "select supplier,accuracy,scale,codeManu,circle,depart.depart,checkNxt,track,certi from gauge_spr_check
				left join depart
				on checkDpt=depart.id
				where sprid=$sprId";
		$res = $sqlHelper->dql($sql);
		$res = json_encode($res, JSON_UNESCAPED_UNICODE);
		$sqlHelper->close_connect();
		return $res;
	}


	function takeSpr($id, $takeTime, $num,$depart){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO gauge_spr_take (sprid, depart, takenum, taketime) values ($id, $depart, $num,'{$takeTime}')";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		$this->flowLog($takeTime,7,$id);
		return $res;
	}

	// 获取库存的入账、领取、再领取时间等信息
	function getStoreInfo($sprId){
		$sqlHelper = new sqlHelper();
		// 获取备件入库时间和第一次领取时间和数量
		$sql = "SELECT storetime,num,resnum,unit from  gauge_spr_dtl where id=$sprId";
		$res = $sqlHelper->dql($sql);
		$sql = "SELECT taketime,takenum,depart.depart,factory.depart as factory 
				from gauge_spr_take
				left join depart
				on depart.id=gauge_spr_take.depart
				left join depart as factory
				on depart.fid=factory.id
				where sprid=$sprId";
		$res['take'] = $sqlHelper->dql_arr($sql);
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
			$sql="select path from device where id=$pid";
			$pathPrt=$sqlHelper->dql($sql);
			$path=$pathPrt['path']."-".$pid;
			$sql = "INSERT INTO device 
					(name,code,no,class,factory,depart,state,`number`,supplier,`path`,dateInstall,pid)
					select name,code,no,'仪表',{$fct['fid']},$depart,'{$state}',$num,supplier,'{$path}','{$installTime}',$pid
					from gauge_spr_dtl
					left join gauge_spr_check
					on gauge_spr_check.sprid=gauge_spr_dtl.id
					where gauge_spr_dtl.id=$id";
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
					where gauge_spr_dtl.id=$id";
			$logRes = 9;
			
		}
		$res = $sqlHelper->dml($sql);
		$devid = mysql_insert_id();

		$sql = "select * from gauge_spr_check where sprid=$id";
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

		// 添加到原备件dtl表中
		$sql = "INSERT INTO gauge_spr_trsf (sprid,devid,trsftime) values ($id,$devid,'{$installTime}')";
		$res = $sqlHelper->dml($sql);

		$sqlHelper->close_connect();
		$this->flowLog($installTime,$logRes,$id);
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

	function endSpr($id, $installtime){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE gauge_spr_dtl set installtime='{$installtime}',res=6 where id=$id";
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
		$sql = "SELECT if(a.num is null,b.num,a.num) as cljl,name,no,location,parainfo,installinfo,runinfo,conclude,
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
		$sql = "SELECT time,user,gauge_spr_log.res,IFNULL(resnum,0) as resnum,IFNULL(takenum,0) as takenum,IFNULL(devid,0) as devid,unit
				from gauge_spr_log 
				left join gauge_spr_dtl
				on gauge_spr_dtl.id=gauge_spr_log.sprid
				left join gauge_spr_take
				on gauge_spr_take.taketime=gauge_spr_log.time
				left join gauge_spr_trsf
				on gauge_spr_trsf.trsftime=gauge_spr_log.time
				where gauge_spr_log.sprid=$id order by time asc";
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

}
?>