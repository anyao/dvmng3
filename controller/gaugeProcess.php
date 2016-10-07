<?php  
require_once '../model/gaugeService.class.php';
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
}
?>