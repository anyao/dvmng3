<?php 
require_once '../model/repairService.class.php';
header("content-type:text/html;charset=utf-8");
$repairService=new repairService();
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	// 维修任务----查看新任务置标志位
	if($flag=="seen"){
		$id=$_GET['id'];
		$res=$repairService->setSeen($id);
		if ($res!=0) {
			echo "success";
			exit();
		}else{
			echo "failure";
			exit();
		}
	}

// 通过misid获取任务详细内容
else if($flag=="getMis"){
	$id=$_GET['id'];
	$res=$repairService->getMis($id);
	echo "$res";
	exit();
}

// 今日任务不再提醒
else if($flag=="noToday"){
	$id=$_GET['id'];
	$res=$repairService->noToday($id);
	if ($res!=0) {
		echo "success";
		exit();
	}else{
		echo "failure";
		exit();
	}
}

// 添加维修任务
else if($flag=="addMis"){
	$devid=$_POST['devid'];
	$err=$_POST['err'];
	$liable=$_POST['liable'];
	$time=$_POST['time'];
	$res=$repairService->addMis($devid,$err,$liable,$time);
	if($res!=0){
		header("location:../repMis.php");
		exit();
	}else{
		echo "添加维修任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

else if($flag=="updateMis"){
	$devid=$_POST['devid'];
	$err=$_POST['err'];
	$liable=$_POST['liable'];
	$misid=$_POST['misid'];
	$time=$_POST['time'];
	$res=$repairService->updateMis($devid,$err,$liable,$misid,$time);
	if($res!=0){
		header("location:../repMis.php");
		exit();
	}else{
		echo "修改维修任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 删除维修任务
else if($flag=="delMis"){
	$id=$_GET['id'];
	$res=$repairService->delMis($id);
	if($res!=0){
		header("location:../repMis.php");
		exit();
	}else{
		echo "删除维修任务失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 添加维修记录
else if ($flag=="addInfoByMis"){
	$misid=$_POST['misid'];
	$devid=$_POST['devid'];
	$err=$_POST['err'];
	$liable=$_POST['liable'];
	$reason=$_POST['reason'];
	$solve=$_POST['solve'];
	$time=$_POST['time'];
	$res=$repairService->addInfoByMis($devid,$err,$liable,$reason,$solve,$time,$misid);
	if(!in_array(0,$res)){
		header("location:../repmis.php");
		exit();
	}else{
		echo "添加维修记录并更新维修任务部分字段失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 获取维修记录
else if($flag=="getInfo"){
	$id=$_GET['id'];
	$res=$repairService->getInfo($id);
	echo "$res";
	exit();
}

// 在维修记录中添加维修记录
else if ($flag=="addInfo"){
	$devid=$_POST['devid'];
	$err=$_POST['err'];
	$liable=$_POST['liable'];
	$reason=$_POST['reason'];
	$solve=$_POST['solve'];
	$time=$_POST['time'];
	$res=$repairService->addInfo($devid,$err,$liable,$reason,$solve,$time);
	if($res!=0){
		header("location:../repList.php");
		exit();
	}else{
		echo "添加维修记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 修改维修记录
else if($flag=="updateInfo"){
	if (!empty($_POST['devid'])) {	
		$devid=$_POST['devid'];
	}else{
		$devid=$_POST['o_devid'];
	}
	$err=$_POST['err'];
	$id=$_POST['id'];
	$liable=$_POST['liable'];
	$reason=$_POST['reason'];
	$solve=$_POST['solve'];
	$time=$_POST['time'];
	$res=$repairService->updateInfo($devid,$err,$id,$liable,$reason,$solve,$time);
	if($res!=0){
		header("location:../repList.php");
		exit();
	}else{
		echo "修改维修记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 删除维修记录
else if($flag=="delInfo"){
	$id=$_GET['id'];
	$res=$repairService->delInfo($id);
	if($res!=0){
		header("location:../repList.php");
		exit();
	}else{
		echo "删除维修记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 在设备具体信息页面添加维修记录
else if ($flag=="addRepByDev"){
	$devid=$_POST['devid'];
	$err=$_POST['err'];
	$liable=$_POST['liable'];
	$reason=$_POST['reason'];
	$solve=$_POST['solve'];
	$time=$_POST['time'];
	$res=$repairService->addInfo($devid,$err,$liable,$reason,$solve,$time);
	if($res!=0){
		header("location:../using.php?id=$devid");
		exit();
	}else{
		echo "添加维修记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

// 具体信息页面获得单个维修记录用于修改
else if($flag=="getRep"){
	$id=$_GET['id'];
	$res=$repairService->getRep($id);
	echo "$res";
	exit();
}

// 设备具体信息页面修改维修记录
else if($flag=="updtRepByDev"){	
	$err=$_POST['err'];
	$id=$_POST['id'];
	$liable=$_POST['liable'];
	$reason=$_POST['reason'];
	$solve=$_POST['solve'];
	$time=$_POST['time'];
	$devid=$_POST['devid'];
	$res=$repairService->updtRepByDev($err,$id,$liable,$reason,$solve,$time);
	if($res!=0){
		header("location:../using.php?id=$devid");
		exit();
	}else{
		echo "添加维修记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
	
}

// 设备具体信息页面删除维修记录
else if($flag=="delrepByDev"){
	$id=$_GET['id'];
	$devid=$_GET['devid'];
	$res=$repairService->delInfo($id);
	if($res!=0){
		header("location:../using.php?id=$devid");
		exit();
	}else{
		echo "删除维修记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
		exit();
	}
}

}
?>