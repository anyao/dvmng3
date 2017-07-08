<?php
require_once '../model/devService.class.php';
header("content-type:text/html;charset=utf-8");
$devService=new devService();
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if($flag=="addDev"){ 
		$name = $_POST['name'];
		$spec = $_POST['spec'];
		$codeManu = $_POST['codeManu'];
		$accuracy = $_POST['accuracy'];
		$status = $_POST['status'];
		$scale = $_POST['scale'];
		$certi = $_POST['certi'];
		$unit = $_POST['unit'];
		$checkDpt = $_POST['checkDpt'];
		$outComp = $_POST['outComp'];
		$checkNxt = $_POST['checkNxt'];
		$valid = $_POST['valid'];
		$circle = $_POST['circle'];
		$track = $_POST['track'];
		$depart = $_POST['depart'];
		$pid = $_POST['pid'];
		$path = $pid ? "-".$pid : $pid;
	}

	// 修改设备信息 made it
	else if($flag=="updateDev"){
		$brand=$_POST['brand'];
		$class=$_POST['class'];
		$code=$_POST['code'];
		$dateEnd=$_POST['dateEnd'];
		$dateInstall=$_POST['dateInstall'];
		$dateManu=$_POST['dateManu'];
		$depart=$_POST['depart'];
		$factory=$_POST['factory'];
		$id=$_POST['id'];
		$number=$_POST['number'];
		$name=$_POST['name'];
		$no=$_POST['no'];
		$periodVali=$_POST['periodVali'];
		$pid=$_POST['pid'];
		$price=$_POST['price'];
		$supplier=$_POST['supplier'];

		$res=$devService->updateDev($brand,$class,$code,$dateEnd,$dateInstall,$dateManu,$depart,$factory,$id,$number,$name,$no,$periodVali,$pid,$price,$supplier);

		if ($res!=0) {
			if (!empty($_POST['paraid'])) {
				$paraid=$_POST['paraid'];
				$res=$devService->updateDetail($paraid,$id);
				if(!in_array(0,$res)){
					if ($pid==0) {
						header("location:../using.php?id=$id");
						exit();
					}else{
						header("location:../usingSon.php?id=$id");
						exit();
					}
				}else{
					echo "更新设备属性参数失败，请联系管理员。<br/>联系电话：0310-5178939。";
				exit();
				}
			}else{
				header("location:../using.php?id=$id");
				exit();
			}
		}else{
			echo "更新设备信息失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 添加新的父节点 made it
	else if($flag=="addPrt"){
		$brand=$_POST['brand'];
		$class=$_POST['class'];
		$code=$_POST['code'];
		$dateInstall=$_POST['dateInstall'];
		$dateManu=$_POST['dateManu'];
		$depart=$_POST['depart'];
		$factory=$_POST['factory'];
		$liable=$_POST['liable'];
		$name=$_POST['name'];
		$no=$_POST['no'];
		$price=$_POST['price'];
		$supplier=$_POST['supplier'];
		$periodVali=$_POST['periodVali'];

		$res=$devService->addPrt($brand,$class,$code,$dateInstall,$dateManu,$depart,$name,$no,$periodVali,$price,$supplier,$liable,$factory);
		if(!in_array(0,$res)){
			header("location:../usingList.php");
			exit();
		}else{
			echo "添加根节点失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 删除设备节点 made it
	else if($flag=="delDev"){
		$id=$_GET['id'];
		$res=$devService->delDevById($id);
		if ($res!=0) {
				header("location:../usingList.php");
				exit();
		}else{
				echo "删除失败，请联系管理员。<br/>联系电话：0310-5178939。";
				exit();
		}
	}

	// 需要替换的旧设备是否有子设备 made it 
	else if ($flag=="findSon") {
		$pid=$_GET['pid'];
		if($pid){
			$res=$devService->IfHasSon($pid);
			echo $res['count(id)'];		
			exit();
		}
	}

	// 在父节点下面添加子节点,用于展开树形子设备列表时的$.get()通过pid查到其下子节点 made it
	else if($flag=="addSon"){
		$pid=$_GET['pid'];
		$res=$devService->addSon($pid);
		echo "$res";
		// file_put_contents('mylog.log',$res,FILE_APPEND);
		exit();
	}

	// 更换设备
	else if($flag=="exchg"){ 
		// brand code dateInstall dateManu id name periodVali price size spec supplier
		$brand=$_POST['brand'];
		$code=$_POST['code'];
		$dateInstall=$_POST['dateInstall'];
		$dateManu=$_POST['dateManu'];
		$id=$_POST['id'];
		$name=$_POST['name'];
		$periodVali=$_POST['periodVali'];
		$price=$_POST['price'];
		$size=$_POST['size'];
		$spec=$_POST['spec'];
		$supplier=$_POST['supplier'];
	
		$endOld=$devService->endDev($id);
		if($endOld[0]==1){
			$class=$endOld[1][0]['class'];
			$factory=$endOld[1][0]['factory'];
			$depart=$endOld[1][0]['depart'];
			$liable=$endOld[1][0]['liable'];
			$number=$endOld[1][0]['number'];
			$pid=$endOld[1][0]['pid'];
			$path=$endOld[1][0]['path'];
			$no=$endOld[1][0]['no'];

			$res=$devService->addDev($brand,$code,$dateInstall,$dateManu,$name,$periodVali,$price,$size,$spec,$supplier,$class,$factory,$depart,$liable,$number,$pid,$path,$no);
			if($res==1){
				header("location:../usingList.php");
				exit();
			}else{
				echo "添加更换记录失败，请联系管理员。<br/>联系电话：0310-5178939。";
				exit();
			}
		}else{
			echo "停用原设备失败，请联系管理员。<br/>联系电话：0310-5178939。";
		}	
	}

	// 设备类别页面删除父类别
	else if($flag=="delTypePa"){
		$id=$_GET['id'];
		$res=$devService->delTypePa($id);
		if($res==1){
			header("location:../devPara.php");
			exit();
		}else{
			echo "删除设备根类别失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 删除设备类别及其属性
	else if($flag=="delType"){
		$id=$_GET['id'];
		$res=$devService->delTypePa($id);
		if($res==1){
			$res=$devService->delParaByType($id);
			if ($res!=0) {	
				header("location:../devPara.php");
				exit();
			}else{
				echo "删除设备属性参数失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
			}
		}else{
			echo "删除设备类别失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}
	
	// 查询设备类别下是否有子设备
	else if($flag=="getTypeSon"){
		$id=$_GET['id'];
		$son=$devService->sonType($id);
		echo $son['count(id)'];
		exit();
	}

	// 添加新的设备类别及其属性
	else if($flag=="addType"){
		$pid=$_POST['pid'];
		$typeName=$_POST['typeName'];
		$para=$_POST['para'];
		$res=$devService->addType($pid,$typeName);
		if($res[0]==1){
			$res=$devService->addPara($res[1]['id'],$para);
			foreach ($res as $key => $value) {
				if(!$value){
					echo "添加类型参数失败，请联系管理员。<br/>联系电话：0310-5178939。";
					exit();
				}
			}
			header("location:../devPara.php");
			exit();
		}else{
			echo "添加新类别失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 添加新的根设备类别
	else if($flag=="addTypePa"){
		$name=$_POST['typeName'];
		$res=$devService->addTypePa($name);
		if($res!=0){
			header("location:../devPara.php");
			exit();
		}else{
			echo "添加新根类别失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 查询类别下的参数
	else if ($flag=="getPara"){
		$typeid=$_GET['id'];
		// echo "$typeid";
		// exit();
		$res=$devService->getPara($typeid);
		echo "$res";
		exit();
	}

	else if($flag=="updateTypePara"){
		$id=$_POST['id'];
		$para=$_POST['para'];
		$paraNew=$_POST['paraNew'];
		$typeName=$_POST['typeName'];
		$updateType=$devService->updateTypeName($id,$typeName);
		if ($updateType!=0) {
			$res=$devService->updatePara($para);
			if (!in_array(0,$res)) {
				$resAddPara=$devService->addPara($id,$paraNew);
				if (!in_array(0,$resAddPara)) {
					header("location:../devPara.php");
					exit();
				}else{
					echo "添加新的属性参数失败，请联系管理员。<br/>联系电话：0310-5178939。";
					exit();
				}
			}else{
				echo "修改原有属性参数失败，请联系管理员。<br/>联系电话：0310-5178939。";
				exit();
			}	
		}else{
			echo "添加修改类别名称失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 添加不同设备类别的具体属性参数
	else if($flag=="getParaOne"){
		$idType=$_POST['idType'];
		$res=$devService->getPara;
	}

	else if($flag=="delPara"){
		$id=$_GET['id'];
		$res=$devService->delPara($id);
		if($res!=0){
			echo "success";
			exit();
		}else{
			echo "删除属性类别失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 在用设备具体信息页面，获得dev_user
	else if($flag=="getCon"){
		$id=$_GET['id'];
		$res=$devService->getCon($id);
		echo "$res";
		exit();
	}

	// 获得当前设备管理员
	else if($flag=="getLia"){
		$id=$_GET['id'];
		$res=$devService->getLia($id);
		echo "$res";
		exit();
	}

	// 修改设备具体信息页面当前管理员
	else if($flag=="updateLia"){
		$devid=$_POST['devid']; 
		if (!empty($_POST['lia'])) {
			$liaAdd=$_POST['lia'];
			$addNew=$devService->addCon($devid,$liaAdd);
		}

		$oid=explode(",",$_POST['oid']);
		$rem=$_POST['rem'];
		$dif=array_diff($oid, $rem);
		if (!empty($dif)) {
			$dif=array_values($dif);
			$delOld=$devService->delCon($dif);
		}
		header("location:../using.php?id=$devid");
		exit();
	}

	// 子设备的更换
	else if($flag=="chgeDev"){
		$info=$_POST['info'];
		$n_brand=$_POST['n_brand'];
		$n_dateInstall=$_POST['n_dateInstall'];
		$n_dateManu=$_POST['n_dateManu'];
		$n_periodVali=$_POST['n_periodVali'];
		$n_price=$_POST['n_price'];
		$n_supplier=$_POST['n_supplier'];
		$oid=$_POST['oid'];
		$nid=$devService->chgeDev($oid,$n_brand,$n_dateInstall,$n_dateManu,$n_periodVali,$n_price,$n_supplier,$info);
	
		$res=$devService->chgeDetail($nid,$oid);
		if($res!=0){
			header("location:../usingSon.php?id=$nid");
			exit();
		}else{
			echo "更换失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 停用父设备
	else if ($flag=="stopDev"){
		$id=$_GET['id'];	
		$res=$devService->stopDev($id);
		if($res!=0){
			header("location:../using.php?id=$id");
			exit();
		}else{
			echo "停用失败，请联系管理员。<br/>联系电话：0310-5178939。";
			exit();
		}
	}

	// 根据所选分厂加载其下部门
	else if($flag=="getDptAll"){
		$idFct=$_GET['idFct'];
		$res=$devService->getDptAll($idFct);
		echo "$res";
		exit();
	}

	else if ($flag == "checkCon") {
		$id = $_POST['id'];
		$con = $_POST['con'];
		if (empty($_POST['ext'])) {
			$ext ="";
		}else{
			$ext = $_POST['ext'];
		}
		$res = $devService->checkCon($con,$id,$ext);
		echo "$res";
	}

	else if ($flag == 'getLeaf') {
		$pid = $_POST['id'];
		$res = $devService->getLeaf($pid);
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		die;
	}

}
?>