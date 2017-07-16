<?php  
require_once '../model/gaugeService.class.php';
require_once '../model/userService.class.php';
include "../phpExcel/PHPExcel/IOFactory.php";
header("content-type:text/html;charset=utf-8");
$gaugeService=new gaugeService();
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if($flag == "file2Arr"){
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
		$class = $_POST['class'];

		$res = $gaugeService->addCheck($id, $codeManu, $accuracy, $scale, $certi, $track, $checkNxt, $valid, $circle, $checkDpt, $outComp, $pid, $path, $class);
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