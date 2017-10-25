<?php  
class safeMsgService{
	private $authDpt;
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getCountValid(){
		$day = CommonService::getTheMonth(date("Y-m-d"));
		$sql = "SELECT count(*) count
				from safe
				where valid <= NOW()
				and takeDpt {$this->authDpt}";
		$res = $this->sqlHelper->dql($sql);
		return $res['count'];
	}

	// public function schedule(){
	// 	$day = CommonService::getTheMonth(date("Y-m-d"));
	// 	$sql = "INSERT INTO check_plan(devid, month)
	// 			SELECT id, DATE_FORMAT(valid, '%Y-%m')
	// 			from buy
	// 			where codeManu is not null
	// 			and valid between '{$day['first']}' AND '{$day['last']}'
	// 			and status > 3 and status != 13";
	// 	$this->sqlHelper->dml($sql);
	// }
}
?>
