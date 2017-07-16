<?php  
require_once 'commonService.class.php';
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
				where (
			    (
			    	unit != '套' and buy.pid is null and buy.status > 3 ) or
					buy.id in (
					 	SELECT pid from buy where pid is not null and buy.status > 3 
				  	) 
			    ) and
			    takeDpt {$this->authDpt} and
			    valid <= NOW()";
		$res = $this->sqlHelper->dql($sql);
		return $res['count'];
	}
}
?>
