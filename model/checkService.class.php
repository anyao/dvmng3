<?php  
class checkService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	function getTypeAll(){
		$sql = "SELECT id,name from check_type";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	function addCheck($arr){
		//  [checkTime] => 2017-07-20 [track] => 检定 [res] => 1 [devid] => 592 [type] => 1 
		$_arr = ["user = '{$_SESSION['uid']}'"];
		$sql = "INSERT INTO `check` set ".CommonService::sqlTgther($_arr, $arr);
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function uptCheck($arr, $id){
		$_arr = ["user = '{$_SESSION['uid']}'"];
		$sql = "UPDATE `check` set ".CommonService::sqlTgther($_arr, $arr)."WHERE id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function uptChkById($arr, $chkid){
		$_arr = ["user = '{$_SESSION['uid']}'"];
		$sql = "UPDATE `check` set ".CommonService::sqlTgther($_arr, $arr)."WHERE id = $chkid";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}
	function setValid($devid, $checkTime){
		$sql = "UPDATE buy set 
					valid = date_sub(
								date_add(
									'{$checkTime}',
									interval circle MONTH
								),
								interval 1 DAY
							)
				where id = $devid";
		$res = $this->sqlHelper->dml($sql);
	}

	function getValid($devid){
		$sql = "SELECT valid from buy where id = $devid";
		$res = $this->sqlHelper->dql($sql);
		return $res['valid']; 
	}

	function getCheckByDev($id){
		$sql = "SELECT `check`.id,check_type.name type,res,`check`.time checkTime,valid,track,conclu,
				status.status,chgStatus,downAccu,confirm.scale,confirm.error,confirm.`interval`,chkRes
				from `check`
				left join check_type
				on check_type.id = `check`.type
				left join user
				on user.id = `check`.user
				left join status
				on status.id = `check`.chgStatus
				left join confirm 
				on confirm.chkid = `check`.id
				where `check`.devid=$id
				order by `check`.id desc";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	function getXlsChk($idStr){
		$sql = "SELECT devid,`check`.time checkTime,res,valid,track,chkRes,confirm.time confirmTime,`check`.conclu
				from `check`
				left join confirm 
				on `check`.id = confirm.chkid
				where devid in ($idStr)";
		$res = $this->sqlHelper->dql_arr($sql);
		return $this->trimXls($res);
	}

	function getXlsFirstChk($idStr){
		$sql = "SELECT devid,`check`.time checkTime,res,valid,track,chkRes,confirm.time confirmTime,conclu
				from `check`
				left join confirm 
				on `check`.id = confirm.chkid
				where `check`.id in ({$idStr})";
		$res = $this->sqlHelper->dql_arr($sql);
		return $this->trimXls($res);
	}

	private function trimXls($check){
		$_check = [];
		foreach ($check as $k => $v) 
			$_check[$v['devid']][] = $v;
		return $_check;
	}

	function listStylePlan($res, $userDpt){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		$dat = array_column($res,'valid');
		for ($i=0; $i < count($dat); $i++) { 
			$mon[] = str_replace ("0", "", substr($dat[$i], 5, 2));
 		}
 		// $mon = sort($mon);
 		$mon = array_unique($mon);
 		sort($mon);
 		$mon = implode(",", $mon);
 		
		// 内容
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', '测量设备( '.$mon.'月 )周检计划')
		->setCellValue('N2', 'CLJL-'.$userDpt['num'].'-06')
		->setCellValue('A3', '序号')
		->setCellValue('B3', '管理类别')
		->setCellValue('C3', '设备名称')
		->setCellValue('D3', '规格型号')
		->setCellValue('E3', '精度等级')
		->setCellValue('F3', '量程')
		->setCellValue('G3', '出厂编号')
		->setCellValue('H3', '制造厂')
		->setCellValue('I3', '检测点')
		->setCellValue('J3', '使用单位')
		->setCellValue('K3', '检定周期')
		->setCellValue('L3', '检定单位')
		->setCellValue('M3', '检定日期')
		->setCellValue('N3', '有效日期')
		->setCellValue('O3', '实际完成日期')
		->setCellValue('P3', '溯源方式');

		for ($i=0; $i < count($res); $i++) { 
			$r = $i + 4;
			$rid = $i + 1;
			$row = $res[$i];
			// 检定单位
			switch ($row['checkDpt']) {
				case '199':
					$row['checkDpt'] = '计量室';
					break;
				case 'isUse':
					$row['checkDpt'] = $row['takeFct'];
					break;
				case 'isOut':
					$row['checkDpt'] = $row['checkComp'];
					break;
			}

			// 设备基本信息
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$r, $rid)
				->setCellValue('B'.$r, $row['class']."类")
				->setCellValue('C'.$r, $row['name'])
				->setCellValue('D'.$r, $row['spec'])
				->setCellValue('E'.$r, $row['accuracy'])
				->setCellValue('F'.$r, $row['scale'])
				->setCellValue('G'.$r, $row['codeManu'])
				->setCellValue('H'.$r, $row['supplier'])
				->setCellValue('I'.$r, $row['loc'])
				->setCellValue('J'.$r, $row['takeFct'])
				->setCellValue('K'.$r, $row['circle']."个月")
				->setCellValue('L'.$r, $row['checkDpt'])
				->setCellValue('M'.$r, $row['checkNxt'])
				->setCellValue('N'.$r, $row['valid']);
		}

		$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow();
		$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();

		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(9)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);

		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getAlignment()->setWrapText(true);

		// 行高
		// foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) 
		//     $rd->setRowHeight(-1); 
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(47.25);
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(69);

		// 字体
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(12);

		// 字体颜色
		$A1FontStyle = [
			'bold' => true,
			'color' => [
				'argb' => PHPExcel_Style_Color::COLOR_RED
			],
			'size' => 18,
		];
		$A3FontStyle = [
			'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'D8E4BC'),
			],
		];

		$K3FontStyle = [
			'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'CCFFFF'),
			],
		];

		$P3FontStyle = [
			'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FFCC99'),
			],
		];

		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->applyFromArray($A1FontStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($A3FontStyle);
		$objPHPExcel->getActiveSheet()->getStyle('K3:O3')->applyFromArray($K3FontStyle);
		$objPHPExcel->getActiveSheet()->getStyle('P3')->applyFromArray($P3FontStyle);

		//居中
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:'.$lastColumn.$lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

		// 边框
		$styleArray = [  
			'borders' => [
			    'allborders' => [
			    	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    ],  
			],  
		];  
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->applyFromArray($styleArray);

		// 合并单元格
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A1:P1')->mergeCells('N2:O2');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="周检计划.xls"');
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

	function getXlsPlan($idStr){
		$sql = "SELECT buy.id,class,buy.name,spec,accuracy,scale,codeManu,supplier,loc,circle,valid,
				date_add(valid, interval 1 day) checkNxt,factory.depart takeFct,checkComp,checkDpt
				from buy 
				left join depart
				on buy.takeDpt = depart.id
				left join depart factory
				on factory.id = depart.fid
				where buy.id in ({$idStr})";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	private function groupClass($dev){
		return implode("、", array_unique(array_column($dev, 'class')));
	}

	public function getMisPaging($paging){
		$sql1 = "SELECT buy.id,codeManu,buy.name,spec,circle,valid,loc,accuracy,checkDpt,
				factory.depart factory,depart.depart,
				status.status
				from buy
				left join depart
				on buy.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				left join status
				on status.id = buy.status
				where codeManu is not null
				and (valid <= NOW() or buy.status = 11 )
				and takeDpt {$this->authDpt}
				and buy.status > 3 and buy.status != 13
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from buy 
				 where codeManu is not null
				 and valid <= NOW()
				 and takeDpt {$this->authDpt}
				 and buy.status > 3 and buy.status != 13";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function findCheckPaging($paging){
		$arr = $paging->para['para']['data'];
		$status = empty($arr['status']) ? "" : "buy.status = {$arr['status']}";
		$name = empty($arr['name']) ? "" : "buy.name like '%{$arr['name']}'%";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$status, $name, $codeManu, $takeDpt]);
		$sql1 = "SELECT `check`.id,`check`.time,codeManu,loc,`check`.res,`check`.devid,`check`.track.`check`.conclu,`check`.`when`,
				status.status,buy.name,factory.depart takeFct,
				confirm.scale,confirm.error,confirm.`interval`,chkRes
				from `check`
				left join buy
				on buy.id = `check`.devid
				left join status
				on buy.status = status.id
				left join depart
				on depart.id = buy.takeDpt
				left join depart factory
				on factory.id = depart.fid
				left join confirm
				on confirm.chkid = `check`.id
				where (
					codeManu is not null
					takeDpt {$this->authDpt}
					and buy.status > 3 and buy.status != 13
				) and (
					".implode(" and ", $_arr)."
				)
				order by `check`.id desc
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				from buy
				where (
					codeManu is not null
					takeDpt {$this->authDpt}
					and buy.status > 3
				) and (
					".implode(" and ", $_arr)."
				)";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function findMisPaging($paging){
		// [status] => 4 [name] => 差压变送器 [codeManu] => 30112S16 [takeDpt] => 1,2,3,198,
		$arr = $paging->para['para']['data'];

		$status = empty($arr['status']) ? "" : "buy.status = {$arr['status']}";
		$name = empty($arr['name']) ? "" : "buy.name like '%{$arr['name']}%'";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$status, $name, $codeManu, $takeDpt]);
		$sql1 = "SELECT buy.id,codeManu,buy.name,spec,circle,valid,loc,accuracy,checkDpt,
				factory.depart factory,depart.depart,
				status.status
				from buy
				left join depart
				on buy.takeDpt = depart.id
				left join depart factory
				on depart.fid = factory.id
				left join status
				on status.id = buy.status
				where (
						codeManu is not null
					and valid <= NOW()
					and takeDpt {$this->authDpt}
					and buy.status > 3
				) and (
				".implode(" and ", $_arr)."
				)
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
			// echo "$sql1"; die;
		$sql2 = "SELECT count(*) 
				 from buy 
				 where (
						codeManu is not null
					and valid <= NOW()
					and takeDpt {$this->authDpt}
					and buy.status > 3
				) and (
				".implode(" and ", $_arr).")";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function getChkPaging($paging){
		$sql1 = "SELECT `check`.id,`check`.time,codeManu,loc,`check`.res,`check`.devid,`check`.track,`check`.conclu,`check`.`when`,
				status.status,buy.name,factory.depart takeFct,
				confirm.scale,confirm.error,confirm.`interval`,chkRes
				from `check`
				left join buy
				on buy.id = `check`.devid
				left join status
				on buy.status = status.id
				left join depart
				on depart.id = buy.takeDpt
				left join depart factory
				on factory.id = depart.fid
				left join confirm
				on confirm.chkid = `check`.id
				where takeDpt {$this->authDpt}
				and buy.status > 3
				order by `check`.id desc
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				from `check`
				left join buy
				on buy.id = `check`.devid
				where takeDpt {$this->authDpt}
				and buy.status > 3";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function downXls($filename){
		$file_name = $filename.".xlsx";     //下载文件名    
		$file_dir = dirname(__file__)."/xls/"; //下载文件存放目录    
		//检查文件是否存在    
		if (!file_exists ($file_dir.$file_name)) {    
		    echo "文件找不到";    
		    exit ();    
		} else {    
		    header("Content-Type: application/force-download");//强制下载
			//给下载的内容指定一个名字
			header("Content-Disposition: attachment; filename=".$this->getXlsName($filename).".xlsx"); 
			readfile($file_dir.$file_name); 
		}    
	}

	private function getXlsName($filename){
		switch ($filename) {
			case 1:
				return "弹性元件式一般压力表检定记录"; 
			case 2:
				return "电流电压表检定记录"; 
			case 3:
				return "流量积算仪检定记录"; 
			case 4:
				return "数字指示仪检定记录"; 
			case 5:
				return "压力（差压）变送器检定记录"; 
		}
	}

	public function getChgStatus($res){
		switch ($res) {
			case 1: return 4;
			case 2: return 8;
			case 3: return 13;
		}
	}

	private function monthToCapital($month){
		return chr((int) substr($month, 5, 2) + 65);
	}

	public function listStyleFinish($year, $dptnum, $arr){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// 合并单元格
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A1:M1')->mergeCells('B10:G10')->mergeCells('H10:M10')
		->mergeCells('B9:C9')->mergeCells('D9:E9')->mergeCells('F9:G9')
		->mergeCells('H9:I9')->mergeCells('J9:K9')->mergeCells('L9:M9');
		// 内容
		// 表头
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', $year.'年计量目标完成情况')
			->setCellValue('J2', '编号：CLJL-'.$dptnum['num'].'-21')
			->setCellValue('A4', '计划送检')
			->setCellValue('A5', '实际送检')
			->setCellValue('A6', '计量确认率')
			->setCellValue('A7', '设备周检率')
			->setCellValue('A8', '计量确认合格率')
			->setCellValue('A9', '人员培训完成情况')
			->setCellValue('A10', '人员培训完成情况')
			->setCellValue('A11', ' 备注：');

		$col = 'B';
		for ($i=1; $i <= 12 ; $i++) { 
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($col++.'3', $i.'月份');
		}

		foreach ($arr as $k => $v) {
			$cap = $this->monthToCapital($k);
			$objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($cap.'4', $v['countPlan'])->setCellValue($cap.'5', $v['countChecked'])
                ->setCellValue($cap.'6', $v['perConfirm'])->setCellValue($cap.'7', $v['perChecked'])
                ->setCellValue($cap.'8', $v['perPass']);
		}
		

		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(10)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(22);

		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A3:M11')->getAlignment()->setWrapText(true);

		// 行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(40);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(84);
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(22);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(54);

		// 字体
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$objPHPExcel->getActiveSheet()->getStyle('A3:M11')->getFont()->setSize(11);

		//居中
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:M11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );

		// 边框
		$styleArray = [  
			'borders' => [
			    'allborders' => [
			    	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    ],  
			],  
		];  
		$objPHPExcel->getActiveSheet()->getStyle('A3:M10')->applyFromArray($styleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$year.'-'.$dptnum['num'].'计量目标完成情况.xls');
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

	public function getCountPlan($dptid, $month){
		$dptid = $dptid != 0 ? " and buy.takeDpt = $dptid " : "";
		$sql = "SELECT count(check_plan.devid) count, month
				from check_plan
				left join buy
				on buy.id = check_plan.devid
				where month between '{$month['first']}' and '{$month['last']}'
				$dptid
				group by month";
		$res = $this->sqlHelper->dql_arr($sql);
		return CommonService::mergeCountAndMonth($res);
	}

	public function getCountChecked($dptid, $month){
		$dptid =  $dptid != 0 ? " and buy.takeDpt = $dptid " : "";
		$sql = "SELECT count(devid) count, left(`check`.time, 7) month
				from `check`
				left join buy
				on buy.id = `check`.devid
				where `check`.time between '{$month['first']}' and '{$month['last']}'
				$dptid
				group by left(`check`.time, 7)";
		$res = $this->sqlHelper->dql_arr($sql);
		return CommonService::mergeCountAndMonth($res);
	}

	public function mergeCount($plan, $checked, $confirm){
		$_arr = [];
		foreach ($plan as $k => $v) {
			$_arr[$k]['countPlan'] = $countPlan = $v ;
			$_arr[$k]['countChecked'] = $countChecked = isset($checked[$k]) ? $checked[$k] : 0;
			$_arr[$k]['countConfirm'] = $countConfirm = isset($confirm[$k]) ? $confirm[$k] : 0;
			$countPass = $countConfirm;

			// 计量确认率
			$_arr[$k]['perConfirm'] = $this->percentAndRound($countConfirm, $countPlan);
			// 设备周检率
			$_arr[$k]['perChecked'] = $this->percentAndRound($countChecked, $countPlan);
			$_arr[$k]['perPass'] = $_arr[$k]['perConfirm'];
		}
		return $_arr;
	}

	public function percentAndRound($numer, $deno){
		$per = $deno ? round($numer / $deno * 100, 2) : 0;
		return $per." %";
	}

	public function getPlanComming($dpt){
		$where = $dpt==0 ? '' : 'where takeDpt = '.$dpt;
		$sql = "SELECT buy.id,circle,valid
				from buy ".$where;
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	public function getValidComming($arr, $month){
		$id = $arr['id'];
		$circle = $arr['circle'];
		$valid = date("Y-m-d",strtotime("+ 1 day",strtotime($arr['valid'])));
		$ifIn = strpos($valid, $month) !== false;
		if ($ifIn) 
			return $id;
		
		$before = strtotime($month.'-01') - strtotime($valid) > 0;
		if ($before) {
			$count = $circle <= 24 ? 24 / $circle : 0;
			for ($i=1; $i <= $count; $i++) { 
			 	$next = date("Y-m-d",strtotime("+ ".$circle * $i." month",strtotime($valid)));
			 	if (strpos($next, $month) !== false) 
			 		return $id;
			 } 
		}
		return false;
	}

	public function getValidYear($arr, $year){
		$id = $arr['id'];
		$circle = $arr['circle'];
		$valid = date("Y-m-d",strtotime("+ 1 day",strtotime($arr['valid'])));
		$ifIn = strpos($valid, $year) !== false;
		if ($ifIn) 
			return $id;

		$before = strtotime($year.'-01-01') - strtotime($valid) > 0;
		if ($before) {
			$count = $circle <= 24 ? 24 / $circle : 0;
			for ($i=1; $i <= $count; $i++) { 
			 	$next = date("Y-m-d",strtotime("+ ".$circle * $i." month",strtotime($valid)));
			 	if (strpos($next, $year) !== false) 
			 		return $id;
			 } 
		}
		return false;
	}
}
?>