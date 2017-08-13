<?php  
class msgService{
	private $authDpt;
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}
	public function getCountValid(){
		$sql = "SELECT count(*) count
				from buy 
				where codeManu is not null
				and valid <= NOW()
				and takeDpt {$this->authDpt}
				and status > 3 and status != 13";
		$res = $this->sqlHelper->dql($sql);
		return $res['count'];
	}
}
?>
