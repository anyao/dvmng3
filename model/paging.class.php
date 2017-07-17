<?php
class paging{
	public $pageSize = 20;   //规定显示页数
	public $res_array; //所显示的数据
	public $rowCount; //从数据库中获得
	public $pageNow; //用户指定当前页码
	public $pageCount; //计算得到总页码
	public $navi; //分页导航
	public $subnavi;
	public $gotoUrl; //把分页请求提交给哪个网页
}
?>