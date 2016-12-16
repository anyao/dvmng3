<?php 
require_once '../model/dptService.class.php';
header("content-type:text/html;charset=utf-8");
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	$dptService=new dptService();
	if ($flag=="getSector") {
		$fid=$_GET['fid'];
		$res=$dptService->getSector($fid);
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
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
		foreach ($res as $v) {
			$arr[$v['id']]['name'] = $v['name'];
			$arr[$v['id']]['code'] = $v['code'];
			$arr[$v['id']]['role'][] = $v['role'];
		}
		$arr=json_encode($arr,JSON_UNESCAPED_UNICODE);
		echo "$arr";
		exit();
	}

	else if ($flag=="addDpt") {
		$name=$_POST['name'];	
		$pid=$_POST['pid'];
		$res=$dptService->addDpt($pid,$name);
		if ($res!=0) {
			header("location:../dptUser.php");
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
			header("location:../uptDpt.php?id=".$id);
		}else{
			echo "修改部门信息失败，请联系管理员：0310-5178939";
		}
	}

	else if ($flag=="delDpt") {
		$id=$_GET['id'];
		$res=$dptService->delDpt($id);
		if ($res!=0) {
			header("location:../dptUser.php");
		}else{
			echo "删除部门失败，请联系管理员：0310-5178939";
		}
	}

	else if ($flag=="findSon") {
		$id=$_GET['id'];
		$res=$dptService->findSon($id);
		echo $res;
		exit();
	}

	else if ($flag=="addUser") {
		$code = $_GET['code'];
	    $name = $_GET['name'];
	    $psw = $_GET['psw'];
	    $dptid = $_GET['dptid'];
	    $node = explode(",",$_GET['node']);
	    $role = explode(",",$_GET['role']);

		$res=$dptService->checkUser($code);
		if ($res['num']!=0) {
			echo "error";
			exit();
		}else{
			$res=$dptService->addUser($code,$name,$psw,$dptid,$node,$role);
			if ($res != 0) {
				echo "success";
				exit();
			}else{
				echo "failure";
				exit();
			}
		}
	}

	else if ($flag=="getUserBsc") {
		$id=$_GET['id'];
		$res=$dptService->getUserBsc($id);	
		$res=json_encode($res,JSON_UNESCAPED_UNICODE);
		echo "$res";
		exit();
	}

	else if($flag=="uptUserBsc"){
		$code = $_GET['code'];
		$name = $_GET['name'];
		$psw = $_GET['psw'];
		$uid = $_GET['id'];
		$dptid = $_GET['dptid'];
		$dptService->uptUserBsc($code,$dptid,$name,$psw,$uid);
		exit();
	}

	else if($flag=="delUser"){
		$uid=$_GET['uid'];
		$res=$dptService->delUser($uid);
		if ($res!=0) {
			echo "success";
			exit();
		}else{
			echo "fail";
			exit();
		}
	}

	else if ($flag=="getCon") {
		$uid=$_GET['uid'];
		$res=$dptService->getConById($uid);
		echo "$res";
		exit();
	}

	else if($flag=="setCon"){
		$con=$_POST['con'];
		$oCon=explode(",",$_POST['oCon']);
		$uid=$_POST['uid'];
		// 删掉的部分也就是停止管理
		$diff=array_diff($oCon,$con);
		
		if (!empty($diff)) {
			$diff=array_values($diff);
			$delOld=$dptService->delCon($diff);
			
		}

		// 开始新的管理关系
		if (!empty($_POST['dev'])) {
			$dev=$_POST['dev'];
			$addNew=$dptService->addCon($dev,$uid);
		}

		if ($delOld!=0) {
			if ($addNew!=0) {
				echo "success";
				exit();
			}else{
				echo "failAdd";
				exit();
			}
		}else{
			echo "failDel";
			exit();
		}	
	}

	else if($flag=="findUser"){
		$kword=$_GET['kword'];
		$res=$dptService->findUser($kword);
		echo "$res";
		exit();
	}

	else if($flag == "addRole"){
		$func = $_GET['func'];
		$roleName = $_GET['roleName'];
		$res = $dptService->addRole($func,$roleName);
		if ($res != 0) {
			header("location:./../dptRole.php");
			exit();
		}else{
			echo "操作失败，请联系管理员：0310-5178939";
			exit();
		}
	}

	else if ($flag == "delRole") {
		$rid = $_GET['rid'];
		$res = $dptService->delRole($rid);
		echo "$res";
		exit();
	}

	else if ($flag == "uptRole") {
		$func = $_GET['func'];
		$rid = $_GET['rid'];
		$roleName = $_GET['roleName'];
		$res = $dptService->uptRole($rid,$func,$roleName);
		if ($res != 0) {
			header("location:./../dptRole.php");
			exit();
		}else{
			echo "操作失败，请联系管理员：0310-5178939";
			exit();
		}
	}

	else if ($flag == "getFct") {
		$id = $_GET['id'];
		$res = $dptService->getFct($id);
		echo "$res";
	}

	else if ($flag == "getDptByFct") {
		$fid = $_GET['fid'];
		$res = $dptService->getSector($fid,1);
		$res = json_encode($res,JSON_UNESCAPED_UNICODE);
		echo "$res";
		exit();
	}

	else if ($flag == "getUserRole") {
		$uid = $_GET['uid'];
		$res = $dptService->getUserRole($uid);
		$res = json_encode($res,JSON_UNESCAPED_UNICODE);
		echo "$res";
		exit();
	}

	else if ($flag == "uptUserRole") {
		$uid = $_GET['uid'];
		$res[] = $dptService->delUserRole($uid);
		$rid = $_GET['rid'];
		if (!empty($rid)) {
			$rid = explode(",",$_GET['rid']);
			$res[] = $dptService->addUserRole($uid,$rid);
		}
		if (!in_array(0,$res)) {
			echo "success";
			exit();
		}else{
			echo "fail";
			exit();
		}
	}

	else if ($flag == "getUserDpt") {
		$uid = $_GET['uid'];
		$res = $dptService->getUserDpt($uid);
		$res = json_encode($res,JSON_UNESCAPED_UNICODE);
		echo "$res";
		exit(); 
	}

	else if ($flag == "uptUserDpt") {
		$uid = $_GET['uid'];
		$dptid = explode(",",$_GET['dptid']);
		$res[] = $dptService->delUserDpt($uid);
		$res[] = $dptService->addUserDpt($uid,$dptid);
		if (!in_array(0,$res)) {
			echo "success";
			exit();
		}else{
			echo "fail";
			exit();
		}
	}



}
?>