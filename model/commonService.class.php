<?php  
class CommonService{
	public static function getAuth(){
		if ($_SESSION['user'] == 'admin') 
			$authDpt = "";
		else{
			$arrDpt = implode(",",$_SESSION['dptid']);
			$authDpt = " in($arrDpt) ";
		}
		return $authDpt;
	}
}
?>