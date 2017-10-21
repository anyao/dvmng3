<?php  
header("content-type:text/html;charset=utf-8");
class repairService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getMisPaging($paging){
		$sql1 = "SELECT buy.id,buy.name,spec,codeManu,loc,factory.depart factory,
				reason1,reason2,reason3,reason4,reason5,reason6,reason7,reason8,reason9
				from buy
				left join (
					SELECT reason1,reason2,reason3,reason4,reason5,reason6,reason7,reason8,reason9,devid
					FROM
						`check`
					WHERE
						id IN (
							SELECT
								MAX(id)
							FROM
								`check`
							WHERE
								res = 2
							GROUP BY
								devid
						)
				) `check`
				on `check`.devid = buy.id
				left join depart 
				on depart.id = buy.takeDpt
				left join depart factory
				on factory.id = depart.fid
				where codeManu is not null
				and takeDpt {$this->authDpt}
				and buy.status = 8
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				from buy 
				where codeManu is not null
				and takeDpt {$this->authDpt}
				and buy.status = 8";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function unqualReason($reason){
		switch ($reason) {
			case 1: return "损坏";
			case 2: return "过载";
			case 3: return "可能使其预期用途无效的故障";
			case 4: return "产生不正确的测量结果";
			case 5: return "超过规定的计量确认间隔";
			case 6: return "误操作";
			case 7: return "封印或保护装置损坏或破裂";
			case 8: return "暴露在已有可能影响其预期用途的影响量中(如电磁场、灰尘)";
			case 9: return "其它";
		}
	}

	public function addRepair($repair){
		// [device] => 设备状况 [repair] => 维护调整情况 [surface] => 外观腐蚀情况 [time] => 2017-07-28 [devid] => 573
		$_arr = ["user = '{$_SESSION['uid']}'"];
		$sql = "INSERT INTO repair set ".CommonService::sqlTgther($_arr, $repair);
		$this->sqlHelper->dml($sql);
	}

	public function findMisPaging($paging){
		$arr = $paging->para['para']['data'];
		$name = empty($arr['name']) ? "" : "buy.name like '%{$arr['name']}'%";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$name, $codeManu, $takeDpt]);
		$sql1 = "SELECT buy.id,buy.name,spec,codeManu,loc,factory.depart factory
				from buy
				left join depart 
				on depart.id = buy.takeDpt
				left join depart factory
				on factory.id = depart.fid
				where (
						codeManu is not null
					and buy.status = 8
					and takeDpt {$this->authDpt}
				) and (
				".implode(" and ", $_arr)."
				)
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from buy 
				 where (
						codeManu is not null
					and takeDpt {$this->authDpt}
					and status = 8
				) and (
				".implode(" and ", $_arr).")";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function getRepPaging($paging){
		$sql1 = "SELECT repair.id,buy.name,spec,codeManu,loc,device,repair,surface,repair.time,repair.devid,
				factory.depart factory
				from repair
				left join buy
				on buy.id = repair.devid
				left join depart
				on buy.takeDpt = depart.id
				left join depart factory
				on factory.id = depart.fid
				where codeManu is not null
				and takeDpt {$this->authDpt}
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				from repair
				left join buy
				on buy.id=repair.devid
				where codeManu is not null
				and takeDpt {$this->authDpt}";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function findRepPaging($paging){
		$arr = $paging->para['para']['data'];
		$name = empty($arr['name']) ? "" : "buy.name like '%{$arr['name']}'%";
		$codeManu = empty($arr['codeManu']) ? "" : "codeManu = '{$arr['codeManu']}'";
		$takeDpt = empty($arr['takeDpt']) ? "" : "takeDpt in (".substr($arr['takeDpt'], 0, -1).")";
		$_arr = array_filter([$name, $codeManu, $takeDpt]);
		$sql1 = "SELECT repair.id,buy.name,spec,codeManu,loc,device,repair,surface,repair.time,
				factory.depart factory
				from repair
				left join buy
				on buy.id = repair.devid
				left join depart
				on buy.takeDpt = depart.id
				left join depart factory
				on factory.id = depart.fid
				where (
					codeManu is not null
					and takeDpt {$this->authDpt}
				) and (
				".implode(" and ", $_arr)."
				)
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from repair
				 left join buy
				 on buy.id = repair.devid
				 where (
						codeManu is not null
					and takeDpt {$this->authDpt}
				) and (
				".implode(" and ", $_arr).")";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	public function getXlsRep($idStr){
		$sql = "SELECT buy.name,spec,codeManu,loc,device,repair,surface,user.name uname,time
				from repair
				left join buy
				on buy.id = repair.devid
				left join user
				on user.id = repair.user
				where repair.id in ({$idStr})";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

	public function listStyle($res, $uDpt){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// 合并单元格
		$objPHPExcel->setActiveSheetIndex(0)
		->mergeCells('A1:J1')->mergeCells('H2:J2');
		// 内容
		// 表头
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '测量设备调整维修记录')
			->setCellValue('H2', '编号：CLJL-'.$uDpt['num'].'-12')
			->setCellValue('A3', '序号')
			->setCellValue('B3', '设备名称')
			->setCellValue('C3', '型号规格')
			->setCellValue('D3', '出厂编号')
			->setCellValue('E3', '安装地点')
			->setCellValue('F3', '设备状况')
			->setCellValue('G3', '维护调整情况')
			->setCellValue('H3', '外观腐蚀情况')
			->setCellValue('I3', '维护人')
			->setCellValue('J3', '维护日期');

		// [name] => 耐震压力表 [spec] => Y-100AZ/1.6MPA [codeManu] => S4S923722642 [loc] => location [device] => 设备状况 
		// [repair] => 维护调整情况 [surface] => 维护调整情况 [uname] => admin [time] => 2017-07-28
		for ($i=0; $i < count($res); $i++) { 
			$r = $i + 4;
			$rid = $i + 1;
			$row = $res[$i];

			// 设备基本信息
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$r, $rid)
				->setCellValue('B'.$r, $row['name'])
				->setCellValue('C'.$r, $row['spec'])
				->setCellValue('D'.$r, $row['codeManu'])
				->setCellValue('E'.$r, $row['loc'])
				->setCellValue('F'.$r, $row['device'])
				->setCellValue('G'.$r, $row['repair'])
				->setCellValue('H'.$r, $row['surface'])
				->setCellValue('I'.$r, $row['uname'])
				->setCellValue('J'.$r, $row['time']);
		}


		$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow();

		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(16.25)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6.25);

		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A3:J'.$lastRow)->getAlignment()->setWrapText(true);

		// 行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(56.25);

		// 字体
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J'.$lastRow)->getFont()->setSize(12);

		//居中
		$objPHPExcel->setActiveSheetIndex(0)->getStyle('A1:J'.$lastRow)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER );

		// 边框
		$styleArray = [  
			'borders' => [
			    'allborders' => [
			    	'style' => PHPExcel_Style_Border::BORDER_THIN,
			    ],  
			],  
		];  
		$objPHPExcel->getActiveSheet()->getStyle('A3:J'.$lastRow)->applyFromArray($styleArray);
		// $objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.$lastRow)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=维修调整情况.xls');
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

	public function delRepair($id){
		$sql = "DELETE from repair where id = $id";
		$this->sqlHelper->dml($sql);
	}

}
?>