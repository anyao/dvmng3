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

	function checkOne($arr){
		// [0] => time [1] => type [2] => res [3] => info [4] => class [5] => status [6] => devid
		$arr['user'] = $_SESSION['uid'];
		$arr['info'] = $arr['res'] == 1 ? 'null' : "'{$arr['info']}'"; 
		$arr['status'] = $arr['res'] == 2 ? $arr['status'] : 'null';
		$arr['class'] = $arr['res'] == 3 ? "'{$arr['class']}'" : 'null';
		$sql = "INSERT INTO `check` (devid,type,res,info,user,time,downClass,chgStatus) 
				values ({$arr['devid']}, {$arr['type']}, {$arr['res']}, {$arr['info']}, {$arr['user']}, '{$arr['time']}', {$arr['class']}, {$arr['status']})";
		$res = $this->sqlHelper->dml($sql);
		$this->setValid($arr['devid']);
		return $res;
	}

	function setValid($devid){
		$sql = "UPDATE buy set 
					valid=date_add(
						( SELECT time from `check` where devid = $devid order by id desc limit 0,1 ),
						interval circle MONTH
					) 
				where id = $devid";
		$res = $this->sqlHelper->dml($sql);
	}

	function getCheckByDev($id){
		$sql = "SELECT `check`.id,check_type.name type,res,info,user.name user,time,
				status.status,chgStatus,downClass
				from `check`
				left join check_type
				on check_type.id = `check`.type
				left join user
				on user.id = `check`.user
				left join status
				on status.id = `check`.chgStatus
				where `check`.devid=$id
				order by `check`.id desc";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	function getXlsChk($idStr){
		$sql = "SELECT devid,time,res from `check` where devid in ($idStr)";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	function trimXls($check){
		$_check = [];
		foreach ($check as $k => $v) 
			$_check[$v['devid']][] = $v;
		return $_check;
	}

	function listStylePlan($arr){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// 内容
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', '测量设备(      )周检计划')
		->setCellValue('N2', 'CLJL-部门号-06')
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

	function getXlsPlan($devid){
		
	}



}
?>