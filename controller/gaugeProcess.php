<?php  
require_once '../model/gaugeService.class.php';
require_once '../model/userService.class.php';
include "../phpExcel/PHPExcel/IOFactory.php";
header("content-type:text/html;charset=utf-8");
$gaugeService=new gaugeService();
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag=="buyAdd") {
		$CLJL=$_POST['CLJL'];
		$applytime=$_POST['applytime'];
		$dptid=$_POST['dptid'];
		$gaugeSpr=$_POST['gaugeSpr'];
		$uid=$_POST['uid'];

		$res=$gaugeService->buyAdd($CLJL,$applytime,$dptid,$gaugeSpr,$uid);

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
		if ($res == 0) {
			header("location: ./../buyApv.php");
		}else{
			echo "操作失败,请联系管理员";
		}
	}

	// 入厂检定
	else if ($flag == "checkSpr") {
		// 入厂检定全部为不合格
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

	else if ($flag == "addSprInCk") {
		$sprId = $_POST['sprId'];
		$check = $_POST['check'];
    	$time = date("Y-m-d H:i:s"); 
		// 将合格数量的信息添加到check表当中去
    	$res[] = $gaugeService->addSprInCk($sprId,$check,$time); 
		// 将dtl表的sprid修改check信息
		$res[] = $gaugeService->checkSpr($sprId,2,$time);
		if (!in_array(0,$res)) {
			header("location: ./../buyCheckHis.php");
		}else{
			echo "操作失败。";
		}
	}

	// // 获取单个备件的入场检定信息
	// else if ($flag == "getCkInfo") {
	// 	$sprId = $_GET['sprId'];
	// 	$res = $gaugeService->getCkInfo($sprId);
	// 	echo "$res";
	// 	exit();
	// }

	// 查看库存入库、领取、再领取的时间
	else if ($flag == "getStoreInfo") {
		$id = $_GET['id'];
		$res = $gaugeService->getStoreInfo($id);
		echo "$res";
		exit();
	}


	// 如果是成套的设备则需要查询其在入厂检定时的info值用于添加其具体的参数属性
	else if ($flag == "getChkInfo") {
		$id = $_GET['id'];
		$res = $gaugeService->getChkInfo($id);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		die;
	}

	else if ($flag == "useAset") {
		$para = $_POST['para'];
		$dateInstall = $_POST['dateInstall'];
		$number = $_POST['number'];
		$id = $_POST['id'];
		$aSet = array_values($_POST['aSet']);
		// 添加父设备并获取其添加后id用于之后子节点的添加
		$fid = $gaugeService->transSpr($id,$number,'正常',0,$dateInstall);
		for ($i=0; $i < count($aSet); $i++) { 
			$aSet[$i] = json_decode(urldecode($aSet[$i]), true);
			$v = array_column($aSet[$i],'value');
			for ($k=0; $k < count($v); $k++) { 
				$v[$k] = $gaugeService->unescape($v[$k]);
			}
			$aSet[$i] = array_combine(array_column($aSet[$i], 'name'), $v);
			$sid = $gaugeService->asetSon($id,$aSet[$i]['number'],'正常',$fid,$dateInstall,$aSet[$i]['no'],$aSet[$i]['name']);
			$res[] = $gaugeService->useDtl($sid,$para);
		}
		echo '{"url":"using","devid":'.$fid.'}';
		die;
	}

	else if ($flag == "endSpr") {
		$id = $_GET['id'];
		$installtime = date("Y-m-d");
		$res = $gaugeService->endSpr($id,$installtime);
		if ($res != 0) {
			header("location: ./../buyInstall.php");
			exit();
		}else{
			echo "操作失败。";
			exit();
		}
	}

	else if ($flag == "installXls") {
		$devId = $_GET['devid'];
		$res = $gaugeService->installXls($devId);
		echo "$res";
		exit();
	}

	else if ($flag == "installInfo") {
		$conclude = $_POST['conclude'];
		$installInfo = $_POST['installInfo'];
		$location = $_POST['location'];
		$paraInfo = $_POST['paraInfo'];
		$runInfo = $_POST['runInfo'];
		$devId = $_POST['devId'];

		$res = $gaugeService->installInfo($conclude,$installInfo,$location,$paraInfo,$devId,$runInfo);
		if ($res != 0) {
			header("location: ./../buyInstallHis.php");
			exit();
		}else{
			echo "操作失败。";
			exit();
		}
	}

	else if ($flag == "flowInfo") {
		$id =$_GET['id'];
		$res = $gaugeService->getFlowInfo($id);
		echo "$res";
		exit();
	}

	else if($flag == "seeSpr"){
		$sprId = $_GET['sprId'];
		$res = $gaugeService->seeSpr($sprId);
		echo "$res";
		exit();
	}

	else if ($flag == "getCkInfo") {
		$checkTime = $_GET['checktime'];
		$sprid = $_GET['sprid'];
		$res = $gaugeService->getCkInfo($checkTime,$sprid);
		echo "$res";
		exit();
	}

	else if($flag == "file2Arr"){
		$sort = ['C', 'V', 'W', 'X', 'AH', 'Z', 'T', 'S', 'O', 'P', 'R', 'AP'];
		if (empty($_FILES['file'])) {
		    echo json_encode(['error'=>'No files found for upload.']); 
		    die; 
		}
		$tmp = $_FILES['file']['tmp_name'];
		$obj = PHPExcel_IOFactory::load($tmp);
		$sheetData = array_slice($gaugeService->unsetNull($obj->getActiveSheet()->toArray(null,true,true,true)), 17);
		$sheetFilter = $gaugeService->array_columns($sheetData, $sort);
		$gaugeService->dataCheck = $sheetFilter;
		echo json_encode($sheetFilter, JSON_UNESCAPED_UNICODE);
		die;
	}

	else if ($flag == "addInfo") {
		$data = json_decode($_POST['data'], true);
		$res = $gaugeService->addCheck($data);
		header("location: ./../buyCheck.php");
		die;
	}

	else if ($flag == "delInfo") {
		$id = $_POST['id'];
		$res = $gaugeService->delInfo($id);
		echo $res !== false ? true: false;
		die;
	}

	else if ($flag == "addCheck") {
		$id = $_POST['id'];
		$codeManu = $_POST['codeManu'];
		$accuracy = $_POST['accuracy'];
		$scale = $_POST['scale'];
		$certi = $_POST['certi'];
		$track = $_POST['track'];
		$checkNxt = $_POST['checkNxt'];
		$valid = $_POST['valid'];
		$circle = $_POST['circle'];
		$checkDpt = $_POST['checkDpt'];
		$outComp = $_POST['outComp'];

		$res = $gaugeService->addCheck($id, $codeManu, $accuracy, $scale, $certi, $track, $checkNxt, $valid, $circle, $checkDpt, $outComp, $pid, $path);
		if ($res !== false) 
			header("location:./../buyCheck.php");
	}

	else if ($flag == "addCheckAset") {
		$spr = $_POST['spr'];
		$pid = $_POST['pid'];
		for ($i=0; $i < count($spr); $i++) { 
			$id = $gaugeService->cloneCheck($pid, $spr[$i]['name'], $spr[$i]['spec'], $spr[$i]['unit']);
			$res = $gaugeService->addCheck($id, $spr[$i]['codeManu'], $spr[$i]['accuracy'], $spr[$i]['scale'], $spr[$i]['certi'], $spr[$i]['track'], $spr[$i]['checkNxt'], $spr[$i]['valid'], $spr[$i]['circle'], $spr[$i]['checkDpt'], $spr[$i]['outComp'], $pid, '-'.$pid);
		}
		$pStatus = $gaugeService->chgStatus($pid);
		if (!in_array(0, $spr) && $pStatus)
			header("location:./../buyCheck.php");
	}

	else if ($flag == "getChk") {
		$id = $_POST['id'];
		$res = $gaugeService->getChk($id);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	else if ($flag ==  "takeSpr") {
    	$dptid = $_POST['dptId'];
		$sprid = substr($_POST['arrId'], 0, -1);
		$res = $gaugeService->takeSpr($sprid, $dptid);
		if ($res !== false) 
			header("location:./../buyCheckHis.php");
	}

	else if ($flag == "useSpr") {
		$loc = $_POST['loc'];
		$id = $_POST['id'];
		$res = $gaugeService->useSpr($id, $loc);
		if ($res !== false) 
			header("location: ./../buyInstall.php");
	}


	else if ($flag == "storeSpr") {
		$id = $_POST['id'];
		$res = $gaugeService->storeSpr($id);
		if ($res !== false) 
			header("location: ./../buyInstall.php");
	}

	else if ($flag == "uptInstall") {
		$id = $_POST['id'];
		$status = $_POST['status'];
		$loc = $status == 4 ? $_POST['loc'] : "";
		if ($status == 4)
			$res = $gaugeService->useSpr($id, $loc);
		else
			$res = $gaugeService->storeSpr($id);

		if ($res !== false) 
			header("location: ./../buyInstallHis.php");
	}

	else if ($flag == "getXls") {
		$id = $_GET['id'];
		$res = $gaugeService->getXls($id);
		if ($res !== false) {
			$gaugeService->installStyle($res);
		}
	}

	else if ($flag == "getLeaf") {
		$id = $_POST['id'];
		$status = $_POST['status'];
		$res = $gaugeService->getLeaf($id, $status);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		die;
	}

}
?>