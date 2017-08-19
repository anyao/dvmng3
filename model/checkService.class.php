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
 		$mon = implode(",", array_unique($mon));
 		
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
		$sql1 = "SELECT buy.id,codeManu,buy.name,spec,circle,valid,loc,accuracy,
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

	public function findCheckPaging($arr,$paging){
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

	public function findMisPaging($arr, $paging){
		// [status] => 4 [name] => 差压变送器 [codeManu] => 30112S16 [takeDpt] => 1,2,3,198,
		$status = empty($arr['status']) ? "" : "buy.status = {$arr['status']}";
		$name = empty($arr['name']) ? "" : "buy.name like '%{$arr['name']}%'";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$status, $name, $codeManu, $takeDpt]);
		$sql1 = "SELECT buy.id,codeManu,buy.name,spec,circle,valid,loc,accuracy,
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
}
?>