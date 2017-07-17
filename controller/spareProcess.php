<?php
require_once "../model/sqlHelper.class.php";
require_once "../model/spareService.class.php";
header("content-type:text/html;charset=utf-8");
$sqlHelper = new sqlHelper;
$spareService=new spareService($sqlHelper);
if(!empty($_REQUEST['flag'])){
	$flag=$_REQUEST['flag'];
	// 添加备用设备
	if($flag=="addSpare"){
		$brand=$_POST['brand'];
		$class=$_POST['class'];
		$code=$_POST['code'];
		$dateEnd=$_POST['dateEnd'];
		$dateManu=$_POST['dateManu'];
		$depart=$_POST['depart'];
		$factory=$_POST['factory'];
		$name=$_POST['name'];
		$no=$_POST['no'];
		$number=$_POST['number'];
		$price=$_POST['price'];
		$supplier=$_POST['supplier'];

		$res=$spareService->addSpare($brand,$class,$code,$dateEnd,$dateManu,$depart,$factory,$name,$no,$number,$price,$supplier);
		if($res==1){
			header("location:../spareList.php");
			exit();
		}else{
			echo "添加设备信息失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}
	// 删除备用设备
	else if($flag=="delSpare"){
		$id=$_GET['id'];
		$res=$spareService->delSpare($id);
		if(!in_array(0,$res)){
			header("location:../spareList.php");
			exit();
		}else{
			echo "删除设备信息失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	else if($flag=="toUsing"){
		$id=$_POST['id'];
		$pid=$_POST['pid'];	

		$res=$spareService->toUsing($id,$pid);
		if($res[1]!=0){
			if (empty($res[0])) {
				header("location:../using.php?id=$id");
				exit();
			}else{
				header("location:../usingSon.php?id=$id");
				exit();
			}	
		}else{
			echo "启用失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 拆分备用设备
	else if($flag=="dvdSpare"){ 
		$id=$_POST['id'];

		$dvd=$_POST['dvd'];
		$res=$spareService->dvd($dvd,$id);
		if ($res!=0) {
			header("location:../spare.php?id=$id");
			exit();
		}else{
			echo "拆解失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
		
	}

    // 修改备用信息
    else if($flag=="updateSpare"){
		$brand=$_POST['brand'];
		$class=$_POST['class'];
		$code=$_POST['code'];
		$dateEnd=$_POST['dateEnd'];
		$dateInstall=$_POST['dateInstall'];
		$dateManu=$_POST['dateManu'];
		$factory=$_POST['factory'];
		$id=$_POST['id'];
		$name=$_POST['name'];
		$no=$_POST['no'];
		$number=$_POST['number'];
		$para=$_POST['paraid'];
		$periodVali=$_POST['periodVali'];
		$price=$_POST['price'];
		$supplier=$_POST['supplier'];

		$res=$spareService->updateSpare($brand,$class,$code,$dateEnd,$dateInstall,$dateManu,$factory,$id,$name,$no,$number,$para,$periodVali,$price,$supplier);
		if (!in_array(0,$res)) {
			header("location:../spare.php?id=$id");
		}else{
			echo "更新设备信息失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
    }

    // 获取全部备用设备类别
    else if($flag=="getTypeDad"){
    	$res=$spareService->getTypeDad();
    	$res= json_encode($res,JSON_UNESCAPED_UNICODE);
    	echo $res;
    	exit();
    }

    // 获取所点击类别的子类别
    else if($flag=="getType"){
    	$pid=$_GET['pid'];
    	$res=$spareService->getType($pid);
    	$res= json_encode($res,JSON_UNESCAPED_UNICODE);
    	echo $res;
    	exit();
    }

    // 添加类别
    else if($flag=="addType"){
		$pname=$_POST['nameDad'];
		$sname=$_POST['typeNew'];
		$res=$spareService->addType($pname,$sname);
		if ($res!=0) {
			header("location:../spareList.php");
			exit();
		}else{
			echo "添加备用设备类型失败请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
    }

    else if($flag=="delType"){
		$name=$_POST['nameDel'];
		$res=$spareService->delType($name);
		if ($res!=0) {
			header("location:../spareList.php");
			exit();
		}else{
			echo "删除备用设备类别失败请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}	

    }

    // 拼装该设备
    else if($flag=="tgther"){
		$class=$_POST['class'];
		$code=$_POST['code'];
		$depart=$_POST['depart'];
		$devid=$_POST['devid'];
		$factory=$_POST['factory'];
		$info=$_POST['info'];
		$liable=$_POST['liable'];
		$name=$_POST['name'];
		$no=$_POST['no'];	
		$tgther=$_POST['tgther'];
		$time=$_POST['time'];

		$res=$spareService->tgther($class,$code,$depart,$devid,$factory,$info,$liable,$name,$no,$tgther,$time);
		if(!in_array(0,$res)){
			header("location:../spare.php?id=$devid");
			exit();
		}else{
			echo "拼装失败请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}	
    }

    // 按照类别显示备件
    // else if($flag=="getSpareByType"){
    // 	$typeName=$_GET['type'];
    // 	$res=$spareService->getSpareByType($typeName,$paging);
    // }
}
?>