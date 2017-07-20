<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$gaugeService = new gaugeService($sqlHelper);
$checkService = new checkService($sqlHelper);
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
		$res = $gaugeService->addInfo($data);
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
		
		$info = $_POST['info'];
		$info['valid'] = date('Y-m-d',strtotime($chk['time']." +".$info['circle']." month"));

		$chk = $_POST['chk'];
		$chk['res'] = 1; 
		$chk['devid'] = $id;
		$chk['type'] = 1;

		$resInfo = $gaugeService->addBas($info, $id);
		$resChk = $checkService->addCheck($chk);

		if (!in_array(false, [$resInfo, $resChk])) 
			header("location:./../buyCheck.php");
	}

	else if ($flag == "addCheckAset") {
		$pid = $_POST['pid'];
		$path = '-'.$pid;
		$spr = $_POST['spr'];
		for ($i=0; $i < count($spr); $i++) { 
			$info = $spr[$i]['info'];
			$info['valid'] = date('Y-m-d',strtotime($chk['time']." +".$info['circle']." month"));
			$info['pid'] = $pid;
			$info['path'] = $path;
			$id = $gaugeService->cloneCheck($pid, $info['name'], $info['spec'], $info['unit']);
			
			$chk = $spr[$i]['chk'];
			$chk['res'] = 1; 
			$chk['devid'] = $id;
			$chk['type'] = 1;
			$gaugeService->addBas($info, $id);
			$checkService->addCheck($chk);
		}
		$gaugeService->chgStatus($pid);
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