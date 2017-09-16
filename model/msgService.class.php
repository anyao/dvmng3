<?php  
class msgService{
	private $authDpt;
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getCountValid(){
		$day = CommonService::getTheMonth(date("Y-m-d"));
		$sql = "SELECT count(*) count
				from buy 
				where codeManu is not null
				and valid between '{$day['first']}' AND '{$day['last']}'
				and takeDpt {$this->authDpt}
				and status > 3 and status != 13";
		// echo "$sql";die;
		$res = $this->sqlHelper->dql($sql);
		return $res['count'];
	}

	public function schedule(){
		$day = CommonService::getTheMonth(date("Y-m-d"));
		$sql = "INSERT INTO check_plan(devid, month)
				SELECT id, DATE_FORMAT(valid, '%Y-%m')
				from buy
				where codeManu is not null
				and valid between '{$day['first']}' AND '{$day['last']}'
				and status > 3 and status != 13";
		$this->sqlHelper->dml($sql);
	}
}
?>
