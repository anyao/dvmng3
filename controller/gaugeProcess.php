<?php  
require_once '../model/gaugeService.class.php';
header("content-type:text/html;charset=utf-8");
$gaugeService=new gaugeService();
if (!empty($_REQUEST['flag'])) {
	$flag=$_REQUEST['flag'];
	if ($flag=="buyAdd") {
		$applytime=$_POST['applytime'];
		$depart=$_POST['depart'];
		$CLJL=$_POST['CLJL'];
		$depart=$_POST['depart'];
		$dptid=$_POST['dptid'];
		$factory=$_POST['factory'];
		$fid=$_POST['fid'];
		$gaugeSpr=$_POST['gaugeSpr'];
		$sprNum=$_POST['sprNum'];
		$user=$_POST['user'];

	}
}
?>