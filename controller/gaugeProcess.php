<?php  
require_once '../model/gaugeService.class.php';
require_once '../model/userService.class.php';
header("content-type:text/html;charset=utf-8");
$gaugeService=new gaugeService();
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag=="buyAdd") {
		$CLJL=$_POST['CLJL'];
		$applytime=$_POST['applytime'];
		$dptid=$_POST['dptid'];
		$fid=$_POST['fid'];
		$gaugeSpr=$_POST['gaugeSpr'];
		$uid=$_POST['uid'];

		$res=$gaugeService->buyAdd($CLJL,$applytime,$dptid,$fid,$gaugeSpr,$uid);

		if ($res!=0) {
			header("location: ../buyApply.php");
			exit();
		}else{
			echo "添加失败。";
			exit();
		}
	}

	// 根据备件申报的基本信息id获取详细信息，用于展开
	else if($flag=="getBuyDtl"){
		$basic=$_GET['id'];
		$res = $gaugeService->getBuyDtl($basic);
		echo "$res";
		exit();
	}

	// 获取单个申报备件的信息
	else if ($flag=="getSprDtl") {
		$id = $_GET['id'];
		$res = $gaugeService->getSprDtl($id);
		echo "$res";
		exit();
	}

	else if ($flag == "getSprDtlForInstal") {
		$id = $_GET['id'];
		$res = $gaugeService->getSprDtl($id);
		$res = json_decode($res,true);
		$userService = new userService();
		$fct = $userService->getFct($_SESSION['dptid']);
		$res = json_encode(array_merge($res, $fct), JSON_UNESCAPED_UNICODE);
		echo "$res";
		exit();
	}

	// 修改单个备件申报信息
	else if ($flag=="uptSprById") {
		$code = $_POST['code'];
		$id = $_POST['id'];
		$info = $_POST['info'];
		$name = $_POST['name'];
		$no = $_POST['no'];
		$num = $_POST['num'];
		$unit = $_POST['unit'];

		$res = $gaugeService->uptSprById($code,$id,$info,$name,$no,$num,$unit);

		if ($res!=0) {
			header("location: ../buyApply.php");
			exit();
		}else{
			echo "修改失败。";
			exit();
		}
	}

	// 删除单个备件申报信息
	else if ($flag=="delSprById") {
		$id = $_GET['id'];

		$res = $gaugeService->delSprById($id);
		if ($res!=0) {
			header("location: ../buyApply.php");
			exit();
		}else{
			echo "删除失败。";
			exit();
		}
	}

	// 删除某一备件申报列表
	else if ($flag=="delBuy") {
		$id = $_GET['id'];
		$res = $gaugeService->delBuy($id);
		if ($res!=0) {
			header("location: ../buyApply.php");
			exit();
		}else{
			echo "删除失败。";
			exit();
		}
	}

	else if ($flag == "check") {
		$pro = $_POST['pro'];
		$phase = $_POST['phase'];
		$res = $gaugeService->checkAuth($pro, $phase);
		echo "$res";
		exit();
	}

	// 审核备件申报
	else if ($flag == "apvBuy") {
		$apvInfo = $_POST['apvInfo'];	
		$apvRes = $_POST['apvRes'];	
		$id = $_POST['id'];
		$res = $gaugeService->apvBuy($apvInfo,$apvRes,$id);
		if ($res != 0) {
			header("location: ./../buyApvHis.php");
		}else{
			echo "操作失败,请联系管理员";
		}
	}

	// 入厂检定
	else if ($flag == "checkSpr") {
		$checkRes = $_POST['checkRes'];
		$checkTime = date("Y-m-d H:i:s");
		$id = $_POST['id'];
		$res = $gaugeService->checkSpr($id,$checkRes,$checkTime);
		if ($res != 0) {
			header("location: ./../buyCheck.php");
		}else{
			echo "操作失败,请联系管理员";
		}
	}

	// 存库入账
	else if($flag == "storeSpr"){
		$id = $_POST['id']; 
		$storeRes = $_POST['storeRes']; 
		$storeTime = date("Y-m-d H:i:s");
		$num = $_POST['num'];
		$total = $_POST['total'];
		$res = $gaugeService->storeSpr($id, $storeRes, $storeTime, $num,$total);
		if (!in_array(0,$res)) {
			header("location: ./../buyStore.php");
			exit();
		}else{
			echo "操作失败，请联系管理员";
			exit();
		}
	}

	// 安装验收备件，在dtl表中添加devid和installtime
	else if($flag == "installSpr"){
		$devId = $_GET['devId'];
		$sprId = $_GET['sprId'];
		$installTime = date("Y-m-d H:i:s");
		$res = $gaugeService->installSpr($sprId,$devId,$installTime);
		if ($res != 0) {
			header("location: ./../buyInstallHis.php");
			exit();
		}else{
			echo "操作失败,请联系管理员";
			exit();
		}
	}

	else if ($flag == "addSprInCk") {
    	$supplier = $_GET['supplier'];
    	$accuracy = $_GET['accuracy'];
    	$scale = $_GET['scale'];
    	$codeManu = $_GET['codeManu'];
    	$certi = $_GET['certi'];
    	$checkNxt = $_GET['checkNxt'];
    	$circle = $_GET['circle'];
    	$track = $_GET['track'];
    	$sprId = $_GET['sprId'];
    	$dptCk = $_GET['dptCk'];
    	$res[] = $gaugeService->addSprInCk($supplier,$accuracy,$scale,$codeManu,$certi,$checkNxt,$circle,$track,$sprId,$dptCk); 
    	$time = date("Y-m-d H:i:s"); 
		$res[] = $gaugeService->checkSpr($sprId,2,$time);
		$res = !in_array(0,$res);
    	echo $res;
		exit();
	}

	// 获取单个备件的入场检定信息
	else if ($flag == "getCkInfo") {
		$sprId = $_GET['sprId'];
		$res = $gaugeService->getCkInfo($sprId);
		echo "$res";
		exit();
	}





}
?>