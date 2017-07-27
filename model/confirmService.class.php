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
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);
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

}
?>