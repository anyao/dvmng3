<?php
require_once "../model/commonService.class.php";
CommonService::autoloadController();
$sqlHelper = new sqlHelper;
$devService=new devService($sqlHelper);
$inspectService=new inspectService($sqlHelper);
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];

	// 修改巡检标准弹出框显示
	if($flag=="getStd"){
		$id=$_GET['id'];
		$res=$inspectService->getStd($id);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		echo "$res";
	}

	// 修改巡检标准
	else if($flag=="updateStd"){
		$id=$_POST['id'];
		$iden=$_POST['iden'];
		$info=$_POST['info'];
		$res=$inspectService->updateStd($id,$iden,$info);
		if ($res!=0) {
			header("location:../inspStd.php");
			exit();
		}else{
			echo "修改巡检标准失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 删除巡检标准记录
	else if($flag=="delStd"){
		$id=$_GET['id'];
		$res=$inspectService->delStd($id);
		if ($res!=0) {
			header("location:../inspStd.php");
			exit();
		}else{
			echo "删除巡检标准失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 添加新的巡检标准
	else if ($flag=="addStd"){
		$devid=$_POST['devid'];
		$iden=$_POST['iden'];
		$info=$_POST['info'];
		$res=$inspectService->addStd($devid,$iden,$info);
		if ($res!=0) {
			header("location:../inspStd.php");
			exit();
		}else{
			echo "添加巡检标准失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 删除巡检任务
	else if($flag=="delMis"){
		$misid=$_GET['misid'];
		// $misid=json_decode($misid);
		$misid=explode(",",$misid);
		$res=$inspectService->delMis($misid);
		if (!in_array(0,$res)) {
			header("location:../inspMis.php");
			exit();
		}else{
			echo "删除巡检任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 获得单个巡检任务信息
	else if($flag=="getMis"){
		$misid = $_GET['misid'];
		$res=$inspectService->getMis($misid);
		$res['dateInstall'] = $inspectService->diffInstl($res['dateInstall']);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		echo "$res";
		exit();
	}

	// 修改巡检任务信息
	else if($flag=="updateMis"){	
		$start=array($_POST['start']);
		$dev=$_POST['dev'];
		$oid=explode(",",$_POST['oid']);
		
		// 删除原来的任务
		$resDel=$inspectService->delMis($oid);
		if (!in_array(0,$resDel)) {
			// 添加新的点检任务
			$resAdd=$inspectService->addMis($dev,$start);
			if ($resAdd!=0) {
				header("location:../inspMis.php");
				exit();
			}else{
				echo "添加巡检任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
				exit();
			}
		}else{
			echo "删除巡检任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	else if($flag=="addMis"){
		$dev=$_POST['dev'];
		$time=$_POST['time'];
		$res=$inspectService->addMis($dev,$time);
		if ($res!=0) {
			header("location:../inspMis.php");
			exit();
		}else{
			echo "添加巡检任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 添加巡检记录
	else if($flag=="addInfo"){
		$result=$_POST['result'];
		$liable=$_POST['liable'];
		$time=$_POST['time'];
	
		if ($result=="正常") {
			$idList=$_POST['idList'];
			$idList=explode("-",$idList);
			$info='无';			
		// }
		// else if($result=="保养"){
		// 	if (!empty($_POST['devid'])) {
		// 		$info=$_POST['info'];
		// 		$devid=$_POST['devid'];
		// 		$idList[0]=$devid;
		// 	}else{
		// 		if(!empty($_POST['idErr'])){
		// 			$idList=$_POST['idErr'];
		// 			$info='无';
		// 			$result="正常";
		// 		}else{
		// 			header("location:../inspList.php");
		// 			exit();
		// 		}
		// 	}
		}else if($result=="需维修"){
			if (!empty($_POST['devid'])) {
				$info=$_POST['info'];
				$devid=$_POST['devid'];
				$idList[0]=$devid;

				$repairMan=$_POST['repairMan'];
				// $repairTime=$_POST['repairTime'];
				$repairService=new repairService($sqlHelper);
				$repMis=$repairService->addMis($devid,$info,$repairMan);
				if ($repMis==0) {
					echo "fail";
					exit();
				}
			}else{
				if(!empty($_POST['idErr'])){
					$idList=$_POST['idErr'];
					$info='无';
					$result="正常";
				}else{
					header("location:../inspList.php");
					exit();
				}
			}
		}

		$res=$inspectService->addInfo($result,$liable,$time,$idList,$info);
		if ($res!=0) {
			header("location:../inspList.php");
			exit();
		}else{
			echo "fail";
			exit();
		}
	}

	// 获取单个巡检记录用于修改
	else if($flag=="getInfo"){
		$id=$_GET['id'];
		$res=$inspectService->getInfo($id);
		echo "$res";
	}

	// 更新巡检记录信息
	else if($flag=="updateInfo"){
		$devid=$_POST['devid'];
		$id=$_POST['id'];
		$info=$_POST['info'];
		$liable=$_POST['liable'];
		$result=$_POST['result'];
		$time=$_POST['time'];

		$res=$inspectService->updateInfo($devid,$id,$info,$liable,$result,$time);
		if ($res!=0) {
			header("location:../inspList.php");
			exit();
		}else{
			echo "修改巡检内容失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}	
	}

	else if($flag=="delInfo"){
		$id=$_GET['id'];
		$res=$inspectService->delInfo($id);
		if ($res!=0) {
			header("location:../inspList.php");
			exit();
		}else{
			echo "删除巡检记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}	
	}

	// 设备具体信息页面添加巡检记录
	else if($flag=="addInfoByDev"){
		if (!empty($_POST['info'])) {
			$info=$_POST['info'];	
		}else{
			$info="无";
		}
		$devid=$_POST['devid'];
		$liable=$_POST['liable'];
		$result=$_POST['result'];
		$time=$_POST['time'];
		$res=$inspectService->addInfoByDev($devid,$info,$liable,$result,$time);
		if ($res!=0) {
			header("location:../using.php?id=$devid");
			exit(); 
		}
	}

	// 设备具体信息页面获得单个巡检记录用于修改删除
	else if ($flag=="getInfoByDev") {
		$id=$_GET['id'];
		$res=$inspectService->getInfoByDev($id);
		echo "$res";
		exit();
	}

	// 设备具体信息页面修改巡检记录
	else if($flag=="updtInfoByDev"){
		$devid=$_POST['devid'];
		$id=$_POST['id'];
		$idLia=$_POST['idLia'];
		$info=$_POST['info'];
		$liable=$_POST['liable'];
		$result=$_POST['result'];
		$time=$_POST['time'];
		$res=$inspectService->updateInfoByDev($id,$info,$liable,$result,$time);
		if ($res!=0) {
			header("location:../using.php?id=$devid");
			exit();
		}else{
			echo "修改巡检记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	else if ($flag=="delInfoByDev") {
		$devid=$_GET['devid'];
		$id=$_GET['id'];
		$res=$inspectService->delInfo($id);
		if ($res!=0) {
			header("location:../using.php?id=$devid");
			exit();
		}else{
			echo "删除巡检记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}	
	}



}
?>