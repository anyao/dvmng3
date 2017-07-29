<?php
require_once "./../Classes/PHPExcel.php";
require_once "./../Classes/PHPExcel/Writer/Excel5.php";
header("content-type:text/html;charset=utf-8");
class confirmService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	function addConfirm($arr){
		// [scale] => 0～10MPa [error] => 0.5 [interval] => 0.01MPa [chkid] => 2 
		$_arr = ["user=".$_SESSION['uid']];
		$sql = "INSERT INTO confirm set ".CommonService::sqlTgther($_arr,$arr);
		$this->sqlHelper->dml($sql);
	}

	public function listStyleConfirm($res, $uDpt){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// 合并单元格
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A2:R2')->mergeCells('A3:D3')->mergeCells('K4:L4')->mergeCells('M4:O4');
		// A34:N34合并单元格
		for ($i='A'; $i !='K' ; $i++) { 
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells($i.'4:'.$i.'5');
		}
		for ($i='P'; $i !='S'; $i++) { 
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells($i.'4:'.$i.'5');
		}

		// 内容
		// 表头
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A2', '测量设备计量验证/确认记录表')->setCellValue('A3', '部门:'.$uDpt['factory'])
			->setCellValue('P3', 'CLJL-'.$uDpt['num'].'-01')
			->setCellValue('A4', '序号')->setCellValue('B4', '测量设备名称')
			->setCellValue('C4', '型号规格')->setCellValue('D4', '测量范围')
			->setCellValue('E4', '准确度等级')->setCellValue('F4', '出厂编号')
			->setCellValue('G4', '生产厂家')->setCellValue('H4', '管理类型')
			->setCellValue('I4', '确认间隔(月)')->setCellValue('J4', '安装使用地点')
			->setCellValue('K4', '工艺控制要求')->setCellValue('M4', '计量要求')
			->setCellValue('P4', '检定/校准结果')->setCellValue('Q4', '检定/校准时间')
			->setCellValue('R4', '验证结果')->setCellValue('K5', '测量范围')
			->setCellValue('L5', '控制精度')->setCellValue('M5', '测量范围')
			->setCellValue('N5', '最大允许误差')->setCellValue('O5', '分度值');

		for ($i=0; $i < count($res); $i++) { 
			$r = $i + 6;
			$rid = $i + 1;
			$row = $res[$i];

			switch ($row['res']) {
				case 1:
					$row['res'] = "合格"; break;
				case 2:
					$row['res'] = "维修"; break;
				case 3:
					$row['res'] = "降级"; break;
				case 4:
					$row['res'] = "封存"; break;
			}

			// $res['chkRes'] = !empty($res['chkRes']) ? $res['chkRes'] : "未确认";

			// 设备基本信息
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$r, $rid)
				->setCellValue('B'.$r, $row['name']."类")
				->setCellValue('C'.$r, $row['spec'])
				->setCellValue('D'.$r, $row['scale'])
				->setCellValue('E'.$r, $row['accuracy'])
				->setCellValue('F'.$r, $row['codeManu'])
				->setCellValue('G'.$r, $row['supplier'])
				->setCellValue('H'.$r, $row['class'])
				->setCellValue('I'.$r, $row['circle'].'个月')
				->setCellValue('J'.$r, $row['loc'])
				->setCellValue('P'.$r, $row['res'])
				->setCellValue('Q'.$r, $row['checktime'])
				->setCellValue('R'.$r, $row['chkRes'])
				->setCellValue('M'.$r, $row['scale'])
				->setCellValue('N'.$r, $row['error'])
				->setCellValue('O'.$r, $row['interval']);
		}

		$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow();

		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3.25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(7.13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(7.13);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(6.5);

		for ($i='K'; $i != 'O' ; $i++) { 
			$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setWidth(11);
		}


		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A4:R'.$lastRow)->getAlignment()->setWrapText(true);

		// 行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(
			30);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(17);
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(21.75);
		$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(21);
		$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(38.25);
		$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(38.25);


		// 字体
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle('A3:R'.$lastRow)->getFont()->setSize(11);

		//居中
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:R'.$lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );

		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

		// 边框
		$styleArray = [  
			'borders' => [
			    'allborders' => [
			    	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    ],  
			],  
		];  
		$objPHPExcel->getActiveSheet()->getStyle('A4:R'.$lastRow)->applyFromArray($styleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.date("Y-m-d").'计量确认.xls');
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

	public function getXlsCfr($idStr){
		$sql = "SELECT buy.name,spec,buy.scale buyscale,accuracy,codeManu,supplier,class,circle,loc,
				confirm.scale,error,`interval`,`check`.conclu,`check`.time checktime, chkRes, `check`.res
				from `check`
				left join confirm
				on confirm.chkid = `check`.id
				left join buy
				on buy.id = `check`.devid
				where `check`.id in ({$idStr})";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	public function getXlsUnqual($chkid){
		$sql = "SELECT buy.name,factory.depart factory,spec,loc,codeManu,scale,`when`, `check`.time,
				reason1,reason2,reason3,reason4,reason5,reason6,reason7,reason8,reason9,res
				from `check`
				left join buy
				on buy.id = `check`.devid
				left join depart
				on buy.takeDpt = depart.id
				left join depart factory
				on factory.id = depart.fid
				where `check`.id = $chkid";
		$res = $this->sqlHelper->dql($sql);
		return $res;
	}

	public function listStyleUnqual($res, $uDpt){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// 合并单元格
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A1:F1')->mergeCells('E2:F2')
		->mergeCells('A6:B6')->mergeCells('C6:F6')->mergeCells('D10:F10')
		->mergeCells('B3:C3')->mergeCells('B4:C4')->mergeCells('B5:C5')
		->mergeCells('E3:F3')->mergeCells('E4:F4')->mergeCells('E5:F5')
		->mergeCells('B7:F7')->mergeCells('B8:F8')->mergeCells('B9:F9');

		// 内容
		// 表头
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '不合格测量设备处置记录')
			->setCellValue('E2', 'CLJL-'.$uDpt['num'].'-11')
			->setCellValue('A3', '设备名称')->setCellValue('D3', '使用单位')
			->setCellValue('A4', '规格型号')->setCellValue('D4', '安装地点')
			->setCellValue('A5', '出厂编号')->setCellValue('D5', '量程')
			->setCellValue('A6', '发现不合格的场所')
			->setCellValue('A7', '不合格原因分析')
			->setCellValue('A8', '处理方式')
			->setCellValue('A9', '处理结果')
			->setCellValue('D10', '日期：'.$res['time']);

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B3', $res['name'])
			->setCellValue('E3', $res['factory'])	
			->setCellValue('B4', $res['spec'])
			->setCellValue('E4', $res['loc'])
			->setCellValue('B5', $res['codeManu'])
			->setCellValue('E5', $res['scale'])
			->setCellValue('C6', $res['when']);
		
		$reason = "";
		for ($i=1; $i < 10; $i++) { 
			if ($res['reason'.$i] == 1) {
				$reason .= "　".$this->unqualReason($i);
			}
		}

		switch ($res['res']) {
			case 2:
				$res['res'] = "　经确认不合格，修理后再校准，经验证合格，粘贴合格标识；"; break;
			case 3:
				$res['res'] = "　经确认不合格，判定降级或限制使用，粘贴标识；"; break;
			case 4:
				$res['res'] = "　经确认不合格，直接封存，粘贴停用标识"; break;
		}
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B7', $reason)->setCellValue('B8', $res['res']);

		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(18)->setAutoSize(true);


		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A3:B9')->getAlignment()->setWrapText(true);

		// 行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(39);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(54.75);
		$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(18);
		$objPHPExcel->getActiveSheet()->getRowDimension('7')->setRowHeight(154.5);
		$objPHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(120.75);
		$objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(144);
		$objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(22.5);


		// 字体
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle('A3:F10')->getFont()->setSize(12);

		//居中
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:F10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('B7')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('B8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT );

		// 边框
		$styleArray = [  
			'borders' => [
			    'allborders' => [
			    	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    ],  
			],  
		];  
		$objPHPExcel->getActiveSheet()->getStyle('A3:F9')->applyFromArray($styleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=test.xls');
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

	private function unqualReason($reason){
		switch ($reason) {
			case 1: return "损坏；\r\n";
			case 2: return "过载；\r\n";
			case 3: return "可能使其预期用途无效的故障；\r\n";
			case 4: return "产生不正确的测量结果；\r\n";
			case 5: return "超过规定的计量确认间隔；\r\n";
			case 6: return "误操作；\r\n";
			case 7: return "封印或保护装置损坏或破裂；\r\n";
			case 8: return "暴露在已有可能影响其预期用途的影响量中(如电磁场、灰尘)。\r\n";
			case 9: return "其它\r\n";
		}
	}

}
?>