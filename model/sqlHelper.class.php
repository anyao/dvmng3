<?php
class sqlHelper{
	public $conn;
	public $dbname="dvcmng";
	public $username="root";

	public $password="anyao";
	public $host="localhost";
	
	// public $password="puyang2016";
	// public $host="172.20.32.79";


	public function __construct(){
		$this->conn=mysql_connect($this->host, $this->username, $this->password);
		if(!$this->conn){
			die("连接错误".mysql_error());
		}
		mysql_select_db($this->dbname, $this->conn);
	}
	
	// 查询dql
	public function dql($sql){
		$res=mysql_query($sql,$this->conn) or die("dql查询失败".mysql_error());
		$row=mysql_fetch_array ($res,MYSQL_ASSOC);
		return $row;
	}

	//dql返回结果为数组assoc
	public function dql_arr($sql){
		$arr=array();
		$res=mysql_query($sql,$this->conn) or die("数组dql取出错误".mysql_error());
		$i=0;
		// //$res中有数据，说明从数据库中取出数据成功，关键在于怎么把它赋给$arr
		// print_r(mysql_fetch_assoc($res));
		while($row=mysql_fetch_assoc($res)){
			$arr[$i++]=$row;
		}
		mysql_free_result($res);
		// print_r($arr);
		// exit();
		return $arr;
	}

	// sql操作dml：insert update delete
	public function dml($sql){
		$b=mysql_query($sql,$this->conn) or die("dml操作失败".mysql_error());
		// echo "$b";
		// exit();
		if(!$b){
			return 0;//fail
		}else{
			if(mysql_affected_rows($this->conn)>0){
				return 1;//succeed
			}else{
				return 2;//no line is affected
			}
		}
		// echo "$b";
		// exit();
	}

	//paging dql
	public function dqlPaging($sql1,$sql2,$paging){
		$res=mysql_query($sql1) or die("分页查询失败".mysql_error());
		$arr=array();
		while ($row=mysql_fetch_assoc($res)) {
			$arr[]=$row;
		}
		mysql_free_result($res);

		$res2=mysql_query($sql2,$this->conn) or die("分页数据取出失败".mysql_error());


		if ($row=mysql_fetch_row($res2)) {
			$paging->pageCount=ceil($row[0]/$paging->pageSize);
			$paging->rowCount=$row[0];
		}

		mysql_free_result($res2);
		$paging->res_array=$arr;

		$navi="";
		$subnavi="";
		$pageWhole=10;
		$start=floor(($paging->pageNow-1)/$pageWhole)*$pageWhole+1;
		$index=$start;

		// 是否存在搜索条件
		$para = !empty($paging->para) ? http_build_query($paging->para)."&" : "";

		//整体10页翻动
    	//显示上一页
		if ($paging->pageNow>1) {
			$prePage=$paging->pageNow-1;
			$navi.= "<a href='{$paging->gotoUrl}?{$para}pageNow=$prePage' class='glyphicon glyphicon-triangle-left'></a>&nbsp;";
		}


		//显示下一页
		if ($paging->pageNow<$paging->pageCount) {
			$nextPage=$paging->pageNow+1;
			$navi.= "<a href='{$paging->gotoUrl}?{$para}pageNow=$nextPage' class='glyphicon glyphicon-triangle-right'></a>&nbsp;";
		}
		$subnavi=$navi;
		//前翻页
		if ($paging->pageNow>$pageWhole) {
			$navi.= "&nbsp;&nbsp;<a href='{$paging->gotoUrl}?{$para}pageNow=".($start-1)."' class=' glyphicon glyphicon-backward'></a>&nbsp;&nbsp;";
		}

		//显示10页页数
		$count = $paging->pageCount < $pageWhole ? $paging->pageCount+1 : $index+$pageWhole;
		for (; $start < $count; $start++)  
			$navi.= "&nbsp;<a href='{$paging->gotoUrl}?{$para}pageNow=$start' class='badge'>$start</a>&nbsp;&nbsp;";


		// 后翻页
		if (($paging->pageNow+$pageWhole)<$paging->pageCount) {

			$navi.= "&nbsp;<a href='{$paging->gotoUrl}?{$para}pageNow=$start' class='glyphicon glyphicon-forward'></a>&nbsp;&nbsp;";
		}

		// 显示总页数
		$navi.= "&nbsp;&nbsp;&nbsp;<span class='badge'>当前第 {$paging->pageNow} 页 </span> / <span class='badge'> 共 {$paging->pageCount} 页</span>";

		$paging->navi=$navi;
		$paging->subnavi=$subnavi;

	}

	function __destruct(){
		mysql_close($this->conn);
	}
} 
?>