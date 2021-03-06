<?php  
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$gaugeService = new gaugeService($sqlHelper);
$checkService = new checkService($sqlHelper);
$confirmService = new confirmService($sqlHelper);
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
		$chk = $_POST['chk'];
		$id = $_POST['id'];
		
		$info = $_POST['info'];
		$info['valid'] = date('Y-m-d',strtotime($chk['time']." +".$info['circle']." month - 1 days"));
		
		$chk['res'] = 1; 
		$chk['devid'] = $id;
		$chk['type'] = 1;
		$chk['valid'] = $info['valid'];
		
		if ($chk['track'] == '检定') {
			$chk['res'] = 1;	
		}else{
			$chk['res'] = $chk['correct']['res'];
			$chk['conclu'] = $chk['correct']['conclu'];
		}
		unset($chk['correct'], $chk['check']);
		
		$resInfo = $gaugeService->setBas($info, $id, 2);
		$resChk = $checkService->addCheck($chk);

		header("location:./../buyCheck.php");
	}

	else if ($flag == "uptCheck") {
		$chk = $_POST['chk'];
		$info = $_POST['info'];
		$devid = $_POST['devid'];
		$chkid = $_POST['chkid'];

		$info['valid'] = date('Y-m-d',strtotime($chk['time']." +".$info['circle']." month - 1 days"));

		$chk['devid'] = $devid;
		$chk['type'] = 1;
		$chk['valid'] = $info['valid'];

		if ($chk['track'] == '检定') {
			$chk['res'] = 1;	
		}else{
			$chk['res'] = $chk['correct']['res'];
			$chk['conclu'] = $chk['correct']['conclu'];
		}
		unset($chk['correct'], $chk['check']);
		
		$resInfo = $gaugeService->setBas($info, $devid, 2);
		$resUptChk = $checkService->uptChkById($chk, $chkid);
		header("location:".$_SERVER['HTTP_REFERER']);
	}

	else if ($flag == "addCheckAset") {
		$pid = $_POST['pid'];
		$path = '-'.$pid;
		$spr = $_POST['spr'];
		for ($i=0; $i < count($spr); $i++) { 
			$info = $spr[$i]['info'];
			$chk = $spr[$i]['chk'];

			$info['valid'] = date('Y-m-d',strtotime($chk['time']." +".$info['circle']." month - 1 days"));
			$info['pid'] = $pid;
			$info['path'] = $path;
			$id = $gaugeService->cloneCheck($pid, $info['name'], $info['spec'], $info['unit']);
			
			$chk['res'] = 1; 
			$chk['devid'] = $id;
			$chk['type'] = 1;
			$chk['valid'] = $info['valid'];

			$gaugeService->setBas($info, $id, 2);
			$checkService->addCheck($chk);
		}
		$gaugeService->chgStatus($pid);
		header("location:./../buyCheck.php");
	}

	else if ($flag == "getChk") {
		$devid = $_POST['devid'];
		$chkid = $_POST['chkid'];
		$res = $gaugeService->getChk($devid, $chkid);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	else if ($flag ==  "takeSpr") {
    	$dptid = $_POST['dptId'];
		$sprid = substr($_POST['arrId'], 0, -1);
		$gaugeService->takeSpr($sprid, $dptid);
		header("location:./../buyCheckHis.php");
	}

	else if ($flag == "useSpr") {
		$bas = $_POST['bas'];
		$bas['useTime'] = date("Y-m-d");

		$yesChk = $_POST['yesChk'];
		$devid = $_POST['devid'];
		$yesChk['chkRes'] = '合格';
		$gaugeService->setBas($bas, $devid, $bas['status']);
		$yesChk['time'] = date("Y-m-d");
		$confirmService->addConfirm($yesChk);

		$ins = $_POST['ins'];
		$ins['devid'] = $devid;
 		$gaugeService->addIns($ins);

		header("location:./../buyInstall.php");
	}

	else if ($flag == "storeSpr") {
		$bas = $_POST['bas'];
		$bas['useTime'] = date("Y-m-d");

		$yesChk = $_POST['yesChk'];
		$devid = $_POST['devid'];
		$yesChk['chkRes'] = '合格';
		$gaugeService->setBas($bas, $devid, $bas['status']);
		$yesChk['time'] = date("Y-m-d");
		$confirmService->addConfirm($yesChk);
		header("location:./../buyInstall.php");
	}

	else if ($flag == "uptInstall") {
		$bas = $_POST['bas'];
		$id = $_POST['id'];
		$gaugeService->setBas($bas, $id, 4);

		$ins = $_POST['ins'];
		$ins['devid'] = $id;
 		$gaugeService->addIns($ins);

		header("location: ./../buyInstallHis.php");
	}

	else if ($flag == "getXls") {
		$id = $_GET['id'];
		$bas = $gaugeService->getXls($id);
		$ins = $gaugeService->getIns($id);
		if ($res !== false) {
			$gaugeService->installStyle($bas, $ins);
		}
	}

	else if ($flag == "getLeaf") {
		$id = $_POST['id'];
		$status = $_POST['status'];
		$res = $gaugeService->getLeaf($id, $status);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		die;
	}

	else if ($flag == "xlsFirstCheck") {
		$devid = substr($_GET['devid'], 0, -1);
		$chkid = substr($_GET['chkid'], 0, -1);
		$devService = new devService($sqlHelper);
		$bas = $devService->getXlsDev($devid); 
		$check = $checkService->getXlsFirstChk($chkid);

		$userService = new userService($sqlHelper);
		$userDpt = $userService->getDpt();
		$devService->listStyle($bas, $check, $userDpt);
	}

}
?>