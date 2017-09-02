<?php
include_once "Classes/PHPExcel.php";
include_once "Classes/PHPExcel/Writer/Excel5.php";
header("content-type:text/html;charset=utf-8");
class devService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getPaging($paging){	
			  // --  where(
			  // --  		(unit != '套' and buy.pid is null and buy.status > 3 ) or
					// -- buy.id in (
					// -- 	SELECT pid from buy where pid is not null and buy.status > 3 
			 	// -- 	) 
			  // --  ) and
		$sql1="SELECT buy.id,codeManu,name,spec,status.status,depart.depart,factory.depart factory,loc,unit
			   from buy
			   left join depart
			   on depart.id=buy.takeDpt
			   left join depart factory
			   on depart.fid=factory.id
			   left join status
			   on status.id=buy.status
			   where 
			   	( (unit != '套' and buy.pid is null) or unit = '套' ) 
			   	and buy.status > 3
			   	and takeDpt {$this->authDpt}
			   order by buy.id desc
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
			   // echo "$sql1"; die;
		$sql2 = "SELECT count(*) 
				 from buy 
				 where(
				 (
				  unit != '套' and buy.pid is null and buy.status > 3 ) or
				  buy.id in (
					SELECT pid from buy where pid is not null and buy.status > 3 
			 	  ) 
			     ) and
			     takeDpt {$this->authDpt}";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);	
	}

	public function getLeaf($pid){
		$sql = "SELECT buy.id,codeManu,name,spec,status.status,depart.depart,factory.depart factory,loc,unit
			    from buy
			    left join depart
			    on depart.id=buy.takeDpt
			    left join depart factory
			    on depart.fid=factory.id
			    left join status
			    on status.id=buy.status
			    where buy.status > 3 
			    and buy.pid=$pid";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	public function addDev($arr){
		$_arr = [];
		$sql = "INSERT INTO buy set ".CommonService::sqlTgther($_arr, $arr);
		$this->sqlHelper->dml($sql);
	}

	public function getCategory(){
		$sql = "SELECT name,no from category";
		$res = $this->sqlHelper->dql_arr($sql);	
		return json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	public function getStatus(){
		$sql = "SELECT id,status from status where id > 3";
		$res = $this->sqlHelper->dql_arr($sql);	
		return $res;
	}

	public function findDev($arr, $dptid, $paging){
		// name,status,spec,codeManu
		// $whereDpt = !is_null($dptid) && (in_array($dptid, $_SESSION['dptid']) || $_SESSION['user'] == 'admin') ? "takeDpt = $dptid" : "1=1";
		if (!is_null($dptid)) {
			// 搜索里有部门限制 并且 部门在用户的管理范围内
			if (in_array($dptid, $_SESSION['dptid']) || $_SESSION['user'] == 'admin') {
				$whereDpt = "takeDpt = $dptid";	
			}else{
				$whereDpt = "1=0";	
			}
		}else{
			$whereDpt = "takeDpt {$this->authDpt}";
		}
		$_arr = [];
		if (!empty($arr)){
			foreach ($arr as $k => $v) {
				if ($v != "") 
					array_push($_arr, "buy.`$k` like '%{$v}%'");
			}
			$where = implode(" and ", $_arr);
		}else{
			$where = "1 = 1";
		}

		$sql1="SELECT buy.id,codeManu,name,spec,status.status,depart.depart,factory.depart factory,loc,unit
			   from buy
			   left join depart
			   on depart.id=buy.takeDpt
			   left join depart factory
			   on depart.fid=factory.id
			   left join status
			   on status.id=buy.status
			   where $where 
			   and buy.status > 3
			   and $whereDpt
			   order by buy.id desc 
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
			  // echo "$sql1"; die;
		$sql2 = "SELECT count(*) 
				 from buy 
				 where $where and (
				 (
				  unit != '套' and buy.pid is null) or
				  buy.id in (
					SELECT pid from buy where pid is not null
			 	  ) 
			     ) and $whereDpt ";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);	
	}

	// 根据id获取设备信息
	public function getDevById($id){
		$sql = "SELECT buy.name,spec,accuracy,scale,codeManu,supplier,loc,circle,valid,unit,
				status.status, status.id statusid,stopTime,category.name catename,useTime,class,`usage`,equip,
				CONCAT(IFNULL(tkFct.depart,''),tkDpt.depart) take,takeDpt,checkComp,
				CONCAT(chkFct.depart,chkDpt.depart) `check`,checkDpt
				from buy
				left join status
				on status.id = buy.status
				left join category
				on category.no = buy.category
				left join depart tkDpt
				on buy.takeDpt = tkDpt.id
				left join depart tkFct
				on tkDpt.fid = tkFct.id
				left join depart chkDpt
				on buy.checkDpt = chkDpt.id
				left join depart chkFct
				on chkDpt.fid = chkFct.id
				where buy.id = $id";
				// echo "$sql";die;
		$res = $this->sqlHelper->dql($sql);
		return $res;
	}

	public function uptDev($arr, $id){
		$_arr = [];
		$sql = "UPDATE buy set ".CommonService::sqlTgther($_arr, $arr)." where id = $id";
		$this->sqlHelper->dml($sql);
	}

	public function logStatus($status, $devid){
		$time = date("Y-m-d");
		$user = $_SESSION['uid'];
		$sql = "INSERT INTO status_log(status, created_at, user, devid)  values ($status, '{$time}', $user, $devid)";
		$this->sqlHelper->dml($sql);
	}

	public function getStatusLogById($devid){
		$sql = "SELECT status.status,created_at,user.name user
				from status_log 
				left join status
				on status_log.status = status.id
				left join user
				on user.id = status_log.user
				where devid=$devid
				order by status_log.id desc";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	public function delDevById($id){
		$sql = "DELETE from buy where id=$id or pid=$id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	public function getDuration($end_time){
		$begin_time = date('Y-m-d');
		if ( $begin_time <= $end_time ) {
			$starttime = strtotime("$begin_time");
			$endtime = strtotime("$end_time");
		} else {
			return ['需检修', '推迟'];
		}
		$timediff = $endtime - $starttime;
		$days = intval( $timediff / 86400 );
		if($days>365)
			return [round($days/365,2), "年"];
		else
			return [$days, "天"];
	}

	public function listStyle($res, $check, $uDpt){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// 合并单元格
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A1:AC1')->mergeCells('X2:Y2')->mergeCells('O3:T3');
		// A34:N34合并单元格
		for ($i='A'; $i !='O' ; $i++) { 
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells($i.'3:'.$i.'4');
		}

		// 内容
		// 表头
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '测量设备管理台账')->setCellValue('X2', 'CLJL-'.$uDpt['num'].'-05')->setCellValue('A3', '序号')
			->setCellValue('B3', '管理类别')->setCellValue('C3', '设备名称')->setCellValue('D3', '规格型号')
			->setCellValue('E3', '精度等级')->setCellValue('F3', '量程')->setCellValue('G3', '出厂编号')
			->setCellValue('H3', '制造厂')->setCellValue('I3', '安装地点')->setCellValue('J3', '使用单位')
			->setCellValue('K3', '现状')->setCellValue('L3', '启(停)用日期')->setCellValue('M3', '新增时间')
			->setCellValue('N3', '测量装置名称及编号')->setCellValue('O3', '用途')->setCellValue('U3', '检定周期(月)')
			->setCellValue('V3', '检定单位')->setCellValue('W3', '检定日期')->setCellValue('X3', '有效日期')
			->setCellValue('Y3', '实际完成日期')->setCellValue('Z3', '溯源方式')->setCellValue('AA3', '证书结论')
			->setCellValue('AB3', '确认日期')->setCellValue('AC3', '确认结论')->setCellValue('O4', '质检')
			->setCellValue('P4', '经营')->setCellValue('Q4', '控制')->setCellValue('R4', '安全')
			->setCellValue('S4', '环保')->setCellValue('T4', '能源');

		for ($i=0; $i < count($res); $i++) { 
			$r = $i + 5;
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

			switch ($row['usage']) {
				case '质检':
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$r, '*');
					break;
				case '经营':
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$r, '*');
					break;
				case '控制':
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$r, '*');
					break;
				case '安全':
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$r, '*');
					break;
				case '环保':
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$r, '*');
					break;
				case '能源':
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$r, '*');
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
				->setCellValue('K'.$r, $row['status'])
				->setCellValue('L'.$r, $row['useTime'])
				->setCellValue('M'.$r, $row['takeTime'])
				->setCellValue('N'.$r, $row['equip'])
				->setCellValue('U'.$r, $row['circle']."个月")
				->setCellValue('V'.$r, $row['checkDpt']);


			// 检定历史
			$col = 'W';
			if(isset($check[$row['id']])){
				for ($k=0; $k < count($check[$row['id']]); $k++) { 
					$chk = $check[$row['id']][$k];
					// CommonService::dump($chk);
					switch ($chk['res']) {
						case 1:
							$chk['res'] = '合格'; break;
						case 2:
							$chk['res'] = '维修'; break;
						case 3:
							$chk['res'] = '降级'; break;
						case 4:
							$chk['res']	= '封存'; break;
						default:
							$chk['res'] = $chk['conclu']; break;				
					}
					$checkNxt = date("Y-m-d",strtotime("+1 day - ".$row['circle']." month",strtotime($chk['valid'])));
					$objPHPExcel->setActiveSheetIndex(0)
					    ->setCellValue($col++.$r, $checkNxt)->setCellValue($col++.$r, $chk['valid'])
					    ->setCellValue($col++.$r, $chk['checkTime'])->setCellValue($col++.$r, $chk['track'])
					    ->setCellValue($col++.$r, $chk['res'])->setCellValue($col++.$r, $chk['confirmTime'])
					    ->setCellValue($col++.$r, $chk['chkRes']);
				}
			}else{
				$checkNxt = date("Y-m-d",strtotime("+1 day - ".$row['circle']." month",strtotime($row['valid'])));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("W".$r, $checkNxt)->setCellValue("X".$r, $row['valid']);
			}
		}

		$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow();
		$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
		
		$indexLastColumn = PHPExcel_Cell::columnIndexFromString($lastColumn);
		$UIndex = PHPExcel_Cell::columnIndexFromString('T');
		for ($i=$UIndex; $i <= $indexLastColumn; $i++) { 
			$objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($i, 3, $i, 4);
		}

		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(10)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
		for ($i='C'; $i != 'J' ; $i++) { 
			if ($i != 'E') 
				$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setWidth(14);
		}
		for ($i='O'; $i != 'U' ; $i++) { 
			$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setWidth(5);
		}

		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getAlignment()->setWrapText(true);

		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastColumn.$lastRow)->getAlignment()->setWrapText(true);

		// 行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(47.25);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(41.25);
		$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(27.75);


		// 字体
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$lastColumn.$lastRow)->getFont()->setSize(10);

		// 字体颜色
		$A1FontStyle = [
			'bold' => true,
			'color' => [
				'argb' => PHPExcel_Style_Color::COLOR_RED
			],
			'size' => 18,
		];
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->applyFromArray($A1FontStyle);
		$objPHPExcel->getActiveSheet()->getStyle('X2')->getFont()->setBold(true);
		// $objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.'3')->getFont()->applyFromArray($fontArray);

		$A3FontStyle = [
			'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'D8E4BC'),
			],
		];
		$U3FontStyle = [
			'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'CCFFFF'),
			],
		];
		$Z3FontStyle = [
			'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FFCC99'),
			],
		];

		$objPHPExcel->getActiveSheet()->getStyle('A3:T4')->applyFromArray($A3FontStyle);
		$objPHPExcel->getActiveSheet()->getStyle('U3:V3')->applyFromArray($U3FontStyle);
		// $objPHPExcel->getActiveSheet()->getStyle('Z3:AC3')->applyFromArray($Z3FontStyle);

		// 检定历史的表头
		$wIndex = PHPExcel_Cell::columnIndexFromString('V');
		for ($i= $wIndex; $i != $indexLastColumn; $i+7) { 
			$c = $i + 2;
			$e = $i + 6;
			$objPHPExcel->setActiveSheetIndex(0)->getStyleByColumnAndRow($i, 3, $i+2, 3)->applyFromArray($U3FontStyle);
			$objPHPExcel->setActiveSheetIndex(0)->getStyleByColumnAndRow($c, 3, $e, 3)->applyFromArray($Z3FontStyle);
	        $objPHPExcel->setActiveSheetIndex(0)
	        	->setCellValueByColumnAndRow($i++, 3, "检定日期")->setCellValueByColumnAndRow($i++, 3, "有效日期")
	        	->setCellValueByColumnAndRow($i++, 3, "实际完成日期")->setCellValueByColumnAndRow($i++, 3, "溯源方式")
	        	->setCellValueByColumnAndRow($i++, 3, "证书结论")->setCellValueByColumnAndRow($i++, 3, "确认日期")
	        	->setCellValueByColumnAndRow($i++, 3, "确认结论");
	    }

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
		// $objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.date("Y-m-d").'台账.xls');
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

	public function getXlsDev($idStr){
		$sql = "SELECT buy.id,buy.name,spec,accuracy,scale,codeManu,supplier,loc,circle,valid,stopTime,useTime,takeTime,equip,`usage`,
				factory.depart takeFct,status.status,checkDpt,checkComp,class
				from buy 
				left join status
				on status.id = buy.status
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



}
?>