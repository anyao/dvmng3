<?php
require_once "./../Classes/PHPExcel.php";
require_once "./../Classes/PHPExcel/Writer/Excel5.php";
header("content-type:text/html;charset=utf-8");
class devService{
	private $authDpt = "";
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	public function getPaging($paging){	
		$sql1="SELECT buy.id,codeManu,name,spec,status.status,depart.depart,factory.depart factory,loc,unit
			   from buy
			   left join depart
			   on depart.id=buy.takeDpt
			   left join depart factory
			   on depart.fid=factory.id
			   left join status
			   on status.id=buy.status
			   where(
			   (
			   	unit != '套' and buy.pid is null and buy.status > 3 ) or
				buy.id in (
					SELECT pid from buy where pid is not null and buy.status > 3 
			 	) 
			   ) and
			   takeDpt {$this->authDpt}
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
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

	// 需改为insert into set
	public function addDev($name, $spec, $codeManu, $accuracy, $status, $scale, $certi, $unit, $checkDpt, $outComp, $checkNxt, $valid, $circle, $track, $takeDpt, $pid, $useTime, $storeTime,$category,$class){
		$storeTime = $status == 4 ? 'null' : "'".date("Y-m-d")."'";
		$useTime = $status == 5 ? 'null' : "'".date("Y-m-d")."'";
		if (empty($pid)) {
			$pid = 'null';
			$path ='null';
		}else
			$path = "'-".$pid."'";

		$sql = "INSERT into buy(name,spec,codeManu,accuracy,status,scale,certi,unit,checkDpt,checkComp,checkNxt,valid,circle,track,takeDpt,pid,path,useTime,storeTime,category,class) 
				values ('$name', '$spec', '$codeManu', '$accuracy', $status, '$scale', '$certi', '$unit', $checkDpt, '$outComp', '$checkNxt', '$valid', $circle, '$track', $takeDpt, $pid, $path, $useTime, $storeTime,$category,'{$class}')";
		$res = $this->sqlHelper->dml($sql);	
		return $res;
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

	public function findDev($status, $name, $spec, $paging){
		$sql1="SELECT buy.id,codeManu,name,spec,status.status,depart.depart,factory.depart factory,loc,unit
			   from buy
			   left join depart
			   on depart.id=buy.takeDpt
			   left join depart factory
			   on depart.fid=factory.id
			   left join status
			   on status.id=buy.status
			   where $where and (
			   (
			   	unit != '套' and buy.pid is null) or
				buy.id in (
					SELECT pid from buy where pid is not null
			 	) 
			   ) and
			   takeDpt {$this->authDpt}
			   limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from buy 
				 where $where and (
				 (
				  unit != '套' and buy.pid is null) or
				  buy.id in (
					SELECT pid from buy where pid is not null
			 	  ) 
			     ) and
			     takeDpt {$this->authDpt}";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);	
	}

	// 根据id获取设备信息
	public function getDevById($id){
		$sql = "SELECT buy.name,spec,accuracy,scale,codeManu,supplier,loc,circle,valid,unit,track,
				status.status, status.id statusid,stopTime,category.name,useTime,class,
				CONCAT(tkFct.depart,tkDpt.depart) take,takeDpt,checkComp,
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
		$res = $this->sqlHelper->dql($sql);
		return $res;
	}

	public function uptDev($arr, $id){
		$_arr = [];
		foreach ($arr as $k => $v) {
			array_push($_arr, $v == '' ? "$k = null" : "$k = '{$v}'");
		}
		$sql = "UPDATE buy set ".implode(", ", $_arr)." where id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
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
		->mergeCells('A1:P1')->mergeCells('A2:P2');

		// 内容
		// 表头
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', $uDpt['depart'].'测量设备（'.self::groupClass($res).'类）台账')
			->setCellValue('A2', 'CLJL-'.$uDpt['num'].'-05')
			->setCellValue('A3', '序号')
			->setCellValue('B3', '设备名称')
			->setCellValue('C3', '规格型号')
			->setCellValue('D3', '精度等级')
			->setCellValue('E3', '量程')
			->setCellValue('F3', '出厂编号')
			->setCellValue('G3', '制造厂')
			->setCellValue('H3', '检测点')
			->setCellValue('I3', '间隔')
			->setCellValue('J3', '有效日期')
			->setCellValue('K3', '使用单位')
			->setCellValue('L3', '检定单位')
			->setCellValue('M3', '现状')
			->setCellValue('N3', '(停)用日期')
			->setCellValue('O3', '类别标志')
			->setCellValue('P3', '新增时间');

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
				->setCellValue('B'.$r, $row['name'])
				->setCellValue('C'.$r, $row['spec'])
				->setCellValue('D'.$r, $row['accuracy'])
				->setCellValue('E'.$r, $row['scale'])
				->setCellValue('F'.$r, $row['codeManu'])
				->setCellValue('G'.$r, $row['supplier'])
				->setCellValue('H'.$r, $row['loc'])
				->setCellValue('I'.$r, $row['circle']."月")
				->setCellValue('J'.$r, $row['valid'])
				->setCellValue('K'.$r, $row['takeFct'])
				->setCellValue('L'.$r, $row['checkDpt'])
				->setCellValue('M'.$r, $row['status'])
				->setCellValue('N'.$r, $row['stopTime'])
				->setCellValue('O'.$r, $row['class'])
				->setCellValue('P'.$r, $row['takeTime']);

			// 检定历史
			$col = 'Q';
			if(isset($check[$row['id']]))
				for ($k=0; $k < count($check[$row['id']]); $k++) { 
					$chk = $check[$row['id']][$k];
					switch ($chk['res']) {
						case '1':
							$chk['res'] = '合格';
							break;
						case '2':
							$chk['res'] = '维修';
							break;
						case '3':
							$chk['res'] = '降级';
							break;
					}
					$objPHPExcel->setActiveSheetIndex(0)
					    ->setCellValue($col++.$r, $chk['time'])->setCellValue($col++.$r, $chk['res']);
				}
		}

		$lastRow = $objPHPExcel->getActiveSheet()->getHighestRow();
		$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();

		// 检定历史的表头
		for ($i= 'Q'; $i != $lastColumn; $i+2) { 
			$c = $i++;
	        $objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue($i."3", "确认日期")->setCellValue($c."3", "检定结果");    
	    }
		
		// 列宽
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12)->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5.64);

		// 自动换行
		$objPHPExcel->getActiveSheet()->getStyle('A4:'.$lastColumn.$lastRow)->getAlignment()->setWrapText(true);

		// 行高
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);

		for ($i=1; $i < 4; $i++) 
			$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(14.25);


		// 字体
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$lastColumn.$lastRow)->getFont()->setSize(10);

		// 字体颜色
		$fontArray = [
			'bold' => true,
			'color' => [
				'argb' => PHPExcel_Style_Color::COLOR_RED
			],
		];
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->applyFromArray($fontArray);
		$objPHPExcel->getActiveSheet()->getStyle('A3:'.$lastColumn.'3')->getFont()->applyFromArray($fontArray);

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
		header('Content-Disposition: attachment;filename='.$uDpt['depart'].'测量设备（'.self::groupClass($res).'类）.xls');
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
		$sql = "SELECT buy.id,buy.name,spec,accuracy,scale,codeManu,supplier,loc,circle,valid,stopTime,takeTime,
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

	public function getChkPaging($paging){
		$sql1 = "SELECT buy.id,codeManu,buy.name,spec,circle,valid,loc,
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
				and valid <= NOW()
				and takeDpt {$this->authDpt}
				and buy.status > 3
				order by valid
				limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from buy 
				 where codeManu is not null
				 and valid <= NOW()
				 and takeDpt {$this->authDpt}
				 and buy.status > 3";
		$this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}


}
?>