<?php 
require_once "classifyBuild.php";
header("content-type:text/html;charset=utf-8");
require_once "sqlHelper.class.php";
$sqlHelper=new sqlHelper();
		$sql="select count(*) as num from depart where path is null";
		$num=$sqlHelper->dql($sql);
		$col=ceil($num['num']/3);
		$rem=$num['num']%3;

		for ($i=0; $i < 3; $i++) { 
			if ($i==0) {
				$lmt=$col+$rem;
			}else{
				$lmt=$col;
			}
			$sql="select id,depart,pid from depart limit ".($col*$i+1).",".$lmt;
			echo "$sql<br/>";
			$res[$i]=$sqlHelper->dql_arr($sql);
		}
		$info="";
		for ($i=0; $i < count($res); $i++) { 
        	$info[$i]=array("text"=>"{$res[$i]['depart']}","dptid"=>"{$res[$i]['id']}","pid"=>"{$res[$i]['pid']}");
        }
        $info=json_encode($info,JSON_UNESCAPED_UNICODE);

?>