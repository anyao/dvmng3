<?php
class confirmService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	function addConfirm($arr){
		// [scale] => 0～10MPa [error] => 0.5 [interval] => 0.01MPa [chkid] => 2 
		$_arr = ["time='".date("Y-m-d")."'", "user=".$_SESSION['uid']];
		$sql = "INSERT INTO confirm set ".CommonService::sqlTgther($_arr,$arr);
		$this->sqlHelper->dml($sql);
	}
}
?>