<?php 
header("content-type:text/html;charset=utf-8");
require_once 'sqlHelper.class.php';
require_once 'paging.class.php';
require_once 'classifyBuild.php';
include "./../Classes/PHPExcel.php";
include "./../Classes/PHPExcel/Writer/Excel5.php";
class gaugeService{
	public $authDpt = "";
	public $dataCheck = [];
	function __construct(){
		if ($_SESSION['user'] == 'admin') {
			$this->authDpt = " ";
		}else{
			$arrDpt = implode(",",$_SESSION['dptid']);
			$this->authDpt = " in($arrDpt) ";
		}
	}

	function delBuy($id){
		$sqlHelper = new sqlHelper();
		$sql = "delete from gauge_spr_bsc where id = $id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	// 备件申报入厂检定列表
	function buyCheckHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT buy.id id,checkTime,codeManu,buy.name,spec,wareTime,unit,category.name category,supplier,codeWare
				 FROM buy
				 left join category
				 on buy.category = category.no
				 where status=2 and pid is null
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from buy where status=2 and pid is null ";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function getLeaf($id, $status){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT buy.id id,checkTime,codeManu,buy.name,spec,wareTime,unit,category.name category,supplier,codeWare
			    FROM buy
			    left join category
			    on buy.category = category.no
			    where pid = $id and status = $status";
		$res = $sqlHelper->dql_arr($sql);
		$sqlHelper->close_connect();
		return $res;
	}

		// 备件申报入厂检定列表
	function buyCheck($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT buy.id id,wareTime,buy.name,spec,codeWare,unit,category.name category,codeWare 
				 FROM buy
				 left join category
				 on buy.category = category.no
				 where status=1
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from buy where status=1";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	public function findWhere($code, $name, $spec){
		$where = " 1 = 1 ";
		if (!empty($code)) 
			$where .= " AND codeWare= '{$code}' ";

		if (!empty($name)) 
			$where .= " AND buy.name LIKE '%{$name}%' ";

		if (!empty($spec)) 
			$where .= " AND spec LIKE '%{$spec}%' ";

		return $where;
	}
 
	function buyCheckFind($check_from, $check_to, $codeWare, $name, $spec, $paging){
		$dtl = $this->findWhere($codeWare,$name,$spec);
		$where = " 1 = 1 ";
		if (!empty($check_from) && !empty($check_to)) 
			$where .= " AND checkTime between '{$check_from}' and '{$check_to}' ";
		elseif (empty($check_from) && !empty($check_to)) 
			$where .= " AND checkTime < '{$check_to}' ";
		elseif (!empty($check_from) && empty($check_to)) 
			$where .= " AND checkTime between '{$check_from}' and ".date("Y-m-d");

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT buy.id id,checkTime,codeManu,buy.name,spec,wareTime,unit,category.name category,supplier,codeWare
				 FROM buy
				 left join category
				 on buy.category = category.no
				 where status=2 AND $where AND $dtl
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from buy where status=2  AND $where AND $dtl";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstallFind($install_from, $install_to, $codeWare, $name, $spec, $paging){
		$dtl = $this->findWhere($codeWare,$name,$spec);
		$where = " 1 = 1 ";
		if (!empty($install_from) && !empty($install_to)) 
			$where .= " AND (useTime between '{$install_from}' and '{$install_to}' OR 
							 storeTime between '{$install_from}' and '{$install_to}')";
		elseif (empty($install_from) && !empty($install_to)) 
			$where .= " AND (useTime < '{$install_to}' 
							OR storeTime < '{$install_to}')";
		elseif (!empty($install_from) && empty($install_to)) 
			$where .= " AND (useTime between '{$install_from}' and ".date("Y-m-d")
						."OR storeTime between '{$install_from}' and ".date("Y-m-d").")";

		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT storeTime, useTime, depart.depart, factory.depart factory, name, codeManu, spec, status, loc, buy.id
				 from buy 
				 left join depart
				 on depart.id = buy.takeDpt
				 left join depart factory
				 on depart.fid = factory.id
				 where status in(4,5) AND $where AND $dtl
				 AND takeDpt {$this->authDpt}
				 order by buy.id  desc
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from buy
				 where status in(4,5) AND $where AND $dtl
				 AND takeDpt {$this->authDpt}";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function buyInstall($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT buy.id id,checkTime,codeManu,buy.name,spec,unit,category.name category,codeWare
				 FROM buy
				 left join category
				 on buy.category = category.no
				 where (
					 (
					 	status=3 
					 	and pid is null
					 	AND unit != '套'
					 )
					 OR
						buy.id in (
							SELECT pid from buy where pid is not null and status=3 
					 	) 
				 )
				 AND takeDpt {$this->authDpt}	
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from buy where (
					 (
					 	status=3 
					 	and pid is null
					 	AND unit != '套'
					 )
					 OR
						buy.id in (
							SELECT pid from buy where pid is not null and status=3 
					 	) 
				 )
				 AND takeDpt {$this->authDpt}";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function takeSpr($sprid, $dptid){
		$sqlHelper = new sqlHelper();
		$takeUser = $_SESSION['user'];
		$takeTime = date("Y-m-d");
		$sql = "UPDATE buy set takeUser='{$takeUser}',takeDpt=$dptid,takeTime='{$takeTime}',status=3 where id in($sprid) or pid = $sprid";
		$res = $sqlHelper->dml($sql);
		return $res;
	}

	function addInfo($data){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO  buy(wareTime,codeWare,name,spec,unit,category,supplier,wareId,status) VALUES";
		for ($i=0; $i < count($data); $i++) { 
			$arr = $data[$i];
			for ($k=0; $k <$arr['AH']; $k++) { 
				$sql .= " ('{$arr['C']}','{$arr['V']}','{$arr['W']}','{$arr['X']}','{$arr['Z']}','{$arr['S']}','{$arr['O']}','{$arr['AP']}', 1),";
			}
		}
		$sql = substr($sql, 0, -1); 
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function delInfo($id){
		$sqlHelper = new sqlHelper();
		$sql = "DELETE FROM buy where id = $id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	} 

	function chgStatus($id){
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE buy set status=2 where id = $id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function cloneCheck($id, $name, $spec, $unit){
		$sqlHelper = new sqlHelper();
		$sql = "INSERT INTO buy(wareTime,codeWare,name,spec,unit,category,supplier,wareId,status) 
				SELECT wareTime,codeWare,'{$name}','{$spec}','{$unit}',category,supplier,wareId,status FROM buy where id = $id";
		$res = $sqlHelper->dml($sql);
		return mysql_insert_id();
	}

	function addCheck($id, $codeManu, $accuracy, $scale, $certi, $track, $checkNxt, $valid, $circle, $checkDpt, $outComp, $pid, $path){
		$sqlHelper = new sqlHelper();
		$checkTime = date("Y-m-d");
		$checkUser = $_SESSION['user'];
		if ($checkDpt == "isOut") 
			$dpt = " checkComp = '{$outComp}' ";
		else
			$dpt = " checkDpt = $checkDpt ";
		$sql = "UPDATE buy SET 
				codeManu = '{$codeManu}',
				accuracy = '{$accuracy}',
				scale = '{$scale}',
				certi = '{$certi}',
				track = '{$track}',
				checkNxt = '{$checkNxt}',
				valid = '{$valid}',
				circle = $circle,
				checkTime = '{$checkTime}',
				checkUser = '{$checkUser}',
				status = 2,
				pid = $pid,
				path = '{$path}',
				{$dpt} where id = $id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function getChk($id){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT codeManu,accuracy,scale,certi,track,checkNxt,valid,circle,checkComp,checkDpt FROM buy where id = $id";
		$res = $sqlHelper->dql($sql);
		$sqlHelper->close_connect();
		return $res;
	}

	function useSpr($id, $loc){
		$useTime = date("Y-m-d");
		$sqlHelper = new sqlHelper();
		$sql = "UPDATE buy set loc='{$loc}', useTime='{$useTime}', status=4 where id=$id";
		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res; 
	}
 	
 	function storeSpr($id){
 		$storeTime = date("Y-m-d");
 		$sqlHelper = new sqlHelper();
 		$sql = "UPDATE buy set status=5, storeTime='{$storeTime}' where id=$id";
 		$res = $sqlHelper->dml($sql);
		$sqlHelper->close_connect();
		return $res; 
 	}

	function buyInstallHis($paging){
		$sqlHelper = new sqlHelper();
		$sql1 = "SELECT storeTime, useTime, depart.depart, factory.depart factory, name, codeManu, spec, status, loc, buy.id
				 from buy 
				 left join depart
				 on depart.id = buy.takeDpt
				 left join depart factory
				 on depart.fid = factory.id
				 where status in(4,5) 
				 AND takeDpt {$this->authDpt}
				 order by buy.id  desc
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				 from buy
				 where status in(4,5) 
				 AND takeDpt {$this->authDpt}";
		$res = $sqlHelper->dqlPaging($sql1,$sql2,$paging);
		$sqlHelper->close_connect();
	}

	function getXls($id){
		$sqlHelper = new sqlHelper();
		$sql = "SELECT buy.name, buy.takeDpt dptid, depart.depart, factory.depart, spec, loc, codeManu, scale
				FROM buy
				LEFT JOIN depart
				on depart.id = buy.takeDpt
				LEFT JOIN depart factory 
				on factory.id= depart.fid
				where buy.id=$id";
		$res = $sqlHelper->dql($sql);

		$sql = "SELECT num from dpt_num where depart={$res['dptid']}";
		$cljl = $sqlHelper->dql($sql);
		$res['CLJL'] = $cljl['num'];
		$sqlHelper->close_connect();
		return $res;
	}

	function installStyle(Array $res){
	  $res['date'] =  date('Y-m-d');
	  $objPHPExcel = new PHPExcel();
	  // 列宽
	  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14.6);
	  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14.72);
	  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15.35);
	  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14.72);
	  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18.22);
	  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(22.35);
	  // 行高
	  $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40.5);
	  $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(23.25);
	  $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(39);
	  $objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(39);
	  $objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(39);
	  $objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(150);
	  $objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(120);
	  $objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(144);
	  $objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(84);
	  $objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(27);

	  // 字体
	  $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
	  $objPHPExcel->getActiveSheet()->getStyle('A2:F10')->getFont()->setSize(12);

	  //居中
	  $objPHPExcel->getActiveSheet()->getStyle('A1:F10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	  $objPHPExcel->getActiveSheet()->getStyle('A1:F10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );

	  // 边框
	   $styleArray = [  
	    'borders' => [
	        'allborders' => [
	          'style' => PHPExcel_Style_Border::BORDER_THIN,
	        ],  
	    ],  
	  ];  
	  $objPHPExcel->getActiveSheet()->getStyle('A3:F9')->applyFromArray($styleArray);
	  // 不太管用
	  // $objPHPExcel->getActiveSheet()->getStyle('A3:F10')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

	  // 合并单元格
	  $objPHPExcel->setActiveSheetIndex(0)
	  ->mergeCells('A1:F1')->mergeCells('E2:F2')
	  ->mergeCells('B3:C3')->mergeCells('E3:F3')
	  ->mergeCells('B4:C4')->mergeCells('E4:F4')
	  ->mergeCells('B5:C5')->mergeCells('E5:F5')
	  ->mergeCells('B6:F6')->mergeCells('B7:F7')
	  ->mergeCells('B8:F8')->mergeCells('B9:F9');

	  // 内容
	  $objPHPExcel->setActiveSheetIndex(0)
	  ->setCellValue('A1', '测量设备安装验收单')
	  ->setCellValue('E2', '编号：CLJL-部门号-07')
	  ->setCellValue('A3', '设备名称')
	  ->setCellValue('D3', '使用部门')
	  ->setCellValue('D3', '使用部门')
	  ->setCellValue('A4', '型号规格')
	  ->setCellValue('D4', '安装地点')
	  ->setCellValue('A5', '出厂编号')
	  ->setCellValue('D5', '量程')
	  ->setCellValue('A6', '技术参数')
	  ->setCellValue('A7', '安装情况')
	  ->setCellValue('A8', '运行情况')
	  ->setCellValue('A9', '结论')
	  ->setCellValue('A10', '使用方签字：')
	  ->setCellValue('C10', '安装方签字：')
	  ->setCellValue('E10', '部室签字：')
	  ->setCellValue('F10', '日期:'.$res['date']);

	  // 数据
	  $objPHPExcel->setActiveSheetIndex(0)
	  ->setCellValue('B3', $res['name'])
	  ->setCellValue('B4', $res['spec'])
	  ->setCellValue('E3', $res['depart'])
	  ->setCellValue('E4', $res['loc'])
	  ->setCellValue('B5', $res['codeManu'])
	  ->setCellValue('E5', $res['scale'])
	  ->setCellValue('E2', $res['CLJL']);


	  // Redirect output to a client’s web browser (Excel5)
	  header('Content-Type: application/vnd.ms-excel');
	  header('Content-Disposition: attachment;filename='.$res['date'].".xls");
	  header('Cache-Control: max-age=0');
	  // If you're serving to IE 9, then the following may be needed
	  header('Cache-Control: max-age=1');

	  // If you're serving to IE over SSL, then the following may be needed
	  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	  header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	  header ('Pragma: public'); // HTTP/1.0

	  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	  $objWriter->save('php://output');
	  exit;
	}

}
?>