<?php 
require_once '../model/dptService.class.php';
header("content-type:text/html;charset=utf-8");
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	$dptService=new dptService();
	if ($flag=="getSector") {
		$fid=$_GET['fid'];
		$res=$dptService->getSector($fid);
		echo "$res";
		exit();
	}

	else if ($flag=="getOffice") {
		$sid=$_GET['sid'];
		$res=$dptService->getOffice($sid);
		echo "$res";
		exit();
	}

	else if ($flag=="getUser") {
		$idDpt=$_GET['dptid'];
		$res=$dptService->getUser($idDpt);
		echo "$res";
		exit();
	}

	else if ($flag=="addDpt") {
		$name=$_POST['name'];	
		$pid=$_POST['pid'];
		$res=$dptService->addDpt($pid,$name);
		if ($res!=0) {
			header("location:../view/dptUser.php");
		}else{
			echo "添加部门失败，请联系管理员：0310-5178939";
		}
	}

	// 修改部门信息
	else if ($flag=="uptDpt") {
		$id=$_POST['id'];
		$name=$_POST['name'];	
		$pid=$_POST['pid'];	
		$path=$_POST['path'];
		$res=$dptService->uptDpt($id,$name,$pid,$path);

		if ($res!=0) {
			header("location:../view/uptDpt.php?id=".$id);
		}else{
			echo "修改部门信息失败，请联系管理员：0310-5178939";
		}
	}

	else if ($flag=="delDpt") {
		$id=$_GET['id'];
		$res=$dptService->delDpt($id);

	}

	else if ($flag=="findSon") {
		$id=$_GET['id'];
		$res=$dptService->findSon($id);
		echo "{$res['num']}";
		exit();
	}
}
?>