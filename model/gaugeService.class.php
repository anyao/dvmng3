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
				$this->install=" and depart in(".$upid.") ";
				break;
			case '2':
				$this->authWhr=" where gauge_spr_bsc.depart=$upid ";
				$this->authAnd=" and gauge_spr_bsc.depart=$upid ";
				$this->instal = " and depart=$upid ";
				break;
		}
		$sqlHelper->close_connect();	
	}


	// 获取所在部门所有备件申报
	function buyBsc($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "select createtime, factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id,apvtime,apvinfo
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user ".$this->authWhr." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "select count(*)
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


	// 获取测量记录部门编号
	function getCLJL($dptid){
		$sqlHelper = new sqlHelper();
		$sql="select num from gauge_dpt_num where depart = $dptid";
		$res=$sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res['num'];
	}

	function buyAdd($CLJL,$applytime,$dptid,$fid,$gaugeSpr,$uid){
		$sqlHelper=new sqlHelper();
		// basic depart--dptid user--user cljl--CLJL createtime----applytime
		// 将申报表基本信息加到basic表中
		$sql="insert into gauge_spr_bsc (depart, user, cljl, createtime) values ($dptid, $uid, '{$CLJL}', '{$applytime}')";
		$res=$sqlHelper->dml($sql);
		$bscid=mysql_insert_id();
		$sql="insert into gauge_spr_dtl (code,name,no,unit,num,info,basic,res) values ";
		for ($i=1; $i <= count($gaugeSpr); $i++) { 
			// [存货编号 code] => 510740110018 [ name 名称] => 超声波流量计 [规格型号 no ] => TJZ-100B [unit 单位] => 个 [数量 num ] => 3 [备注描述 info] => 无
			if ($i != count($gaugeSpr)) {
				$sql .= "('{$gaugeSpr[$i][0]}', '{$gaugeSpr[$i][1]}', '{$gaugeSpr[$i][2]}', '{$gaugeSpr[$i][3]}', {$gaugeSpr[$i][4]}, '{$gaugeSpr[$i][5]}', $bscid, 0), ";
			}else{
				$sql .= "('{$gaugeSpr[$i][0]}', '{$gaugeSpr[$i][1]}', '{$gaugeSpr[$i][2]}', '{$gaugeSpr[$i][3]}', {$gaugeSpr[$i][4]}, '{$gaugeSpr[$i][5]}', $bscid, 0) ";
			}
		}
		$res=$sqlHelper->dml($sql);
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
				 on user.id=gauge_spr_bsc.user where apvtime is null ".$this->authAnd." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "select count(*)
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user where apvtime is null ".$this->authAnd;
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
		}else{
			$apvInfo = " apvinfo = '{$apvInfo}' ";
		}
		$sql = "update gauge_spr_bsc set apvtime='$time',$apvInfo,see=1 where id=$id";
		$res[] = $sqlHelper->dml($sql);
		$sql = "update gauge_spr_dtl set res=1,see=1 where basic=$id";
		$res[] = $sqlHelper->dml($sql);
		$result = in_array(0,$res);
		$sqlHelper->close_connect();
		return $result; 
	}

	function buyApvHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "select factory.depart as factory, depart.depart, user.name,cljl,see,gauge_spr_bsc.id,apvinfo,apvtime
				 from gauge_spr_bsc
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 left join user
				 on user.id=gauge_spr_bsc.user where apvtime is not null ".$this->authAnd." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "select count(*)
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
				 ".$this->authAnd." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
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
		$sqlHelper->close_connect();
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
				 "." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
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
		$sqlHelper->close_connect();
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

	// 库存备件
	// 备件入账存库历史
	function buyStoreHouse($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,storetime,resnum,takenum
				 from gauge_spr_dtl 
				 left join 
				 (select sum(takenum) as takenum,sprid from gauge_spr_take group by sprid) as take
				 on take.sprid=gauge_spr_dtl.id
				 where res=5 and takenum+resnum != num
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from gauge_spr_dtl 
				 left join 
				 (select sum(takenum) as takenum,sprid from gauge_spr_take group by sprid) as take
				 on take.sprid=gauge_spr_dtl.id
				 where res=5 and takenum+resnum != num";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstall($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,gauge_spr_dtl.code,gauge_spr_dtl.name,no,resnum,unit,storetime
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 where res=5 and resnum!=0 ".$this->install."
				 UNION all
				 select sprid,gauge_spr_dtl.code,gauge_spr_dtl.name,no,takenum,unit,taketime
				 from gauge_spr_take
				 LEFT JOIN gauge_spr_dtl
				 on gauge_spr_take.sprid=gauge_spr_dtl.id
				where gauge_spr_take.res is null ".$this->install."
			    limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT sum(tem) from
				 (
				 SELECT count(*) as tem
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 where res=5 and resnum!=0 ".$this->install."
				 UNION all
				 select count(*) as tem
				 from gauge_spr_take
				 LEFT JOIN gauge_spr_dtl
				 on gauge_spr_take.sprid=gauge_spr_dtl.id
				 where gauge_spr_take.res is null ".$this->install."
				 )
				 as total";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	// 备件安装
	function installSpr($sprId,$devId,$installTime){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE gauge_spr_dtl set devid=$devId, installtime='{$installTime}',see=1,res=6 where id=$sprId";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 备件安装验收历史记录
	function buyInstallHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT gauge_spr_dtl.id,code,name,no,num,unit,info,depart.depart,factory.depart as factory,installtime,res,devid
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where installtime is not null
				 ".$this->authAnd." limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 FROM gauge_spr_dtl
				 left join gauge_spr_bsc
				 on gauge_spr_bsc.id=gauge_spr_dtl.basic
				 left join depart
				 on gauge_spr_bsc.depart=depart.id
				 left join depart as factory
				 on depart.fid=factory.id
				 where installtime is not null".$this->authAnd;
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
		$sql = "INSERT INTO gauge_spr_trsf (sprid,devid) values ($id,$devid)";
		$res = $sqlHelper->dml($sql);

		$sqlHelper->close_connect();
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

}
?>