<?php 
header("content-type:text/html;charset=utf-8");
include_once "Classes/PHPExcel.php";
include_once "Classes/PHPExcel/Writer/Excel5.php";
class gaugeService{
	private $authDpt = "";
	public $dataCheck = [];
	private $sqlHelper;
	function __construct($sqlHelper){
		$this->authDpt = CommonService::getAuth();
		$this->sqlHelper = $sqlHelper;
	}

	// 备件申报入厂检定列表
	function buyCheckHis($paging){
		$sql1 = "SELECT buy.id,codeManu,buy.name,spec,unit,category.name category,supplier,codeWare,
				`check`.time checkTime,`check`.id chkid
				 FROM buy
				 left join category
				 on buy.category = category.no
				 left join	(
					select * from `check` where type=1
				 ) `check`
				 on `check`.devid = buy.id
				 where status=2 
				 and(
				  (unit != '套' and buy.pid is null) or
				  buy.id in (
					SELECT pid from buy where pid is not null
			 	  ) 
			 	 )
				 order by `check`.id desc
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from buy where status=2 and pid is null ";
		$res = $this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function getLeaf($id, $status){
		$sql = "SELECT buy.id,codeManu,takeTime,buy.name,spec,unit,category.name category,supplier,codeWare,
				`check`.time checkTime,	`check`.id chkid
			    FROM buy
			    left join category
			    on buy.category = category.no
			    left join `check`
			    on `check`.devid = buy.id
			    where pid = $id 
			    and status = $status
			    and type = 1";
		$res = $this->sqlHelper->dql_arr($sql);
		return $res;
	}

		// 备件申报入厂检定列表
	function buyCheck($paging){
		$sql1 = "SELECT buy.id id,wareTime,buy.name,spec,codeWare,unit,category.name category,codeWare 
				 FROM buy
				 left join category
				 on buy.category = category.no
				 where status=1
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) from buy where status=1";
		$res = $this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	private function findWhere($code, $name, $spec){
		$where = [];
		if (!empty($code)) 
			$where[] = "codeManu= '{$code}'";

		if (!empty($name)) 
			$where[] = "buy.name LIKE '%{$name}%'";

		if (!empty($spec)) 
			$where[] = "spec LIKE '%{$spec}%'";

		return $where;
	}
 	
	public function addIns($arr){
		$_arr = [];
		$sql = "INSERT INTO install set ".CommonService::sqlTgther($_arr, $arr);
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function buyCheckFind($paging){
		extract($paging->para['para']['data']);

		$where = $this->findWhere($codeManu,$name,$spec);

		if (!empty($check_from)) 
			$where[] = "`check`.time >= '$check_from'";

		if (!empty($check_to))
			$where[] = "`check`.time <= '$check_to'";

		$where[] = "status = 2";

		$sql1 = "SELECT buy.id id,`check`.time checkTime,codeManu,buy.name,spec,unit,category.name category,supplier,codeWare
				 FROM buy
				 left join category
				 on buy.category = category.no
				 left join	(
					select * from `check` where type=1
				 ) `check`
				 on `check`.devid = buy.id
				 where ".
				 implode(" and ", $where)."
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*) 
				 from buy 
				 left join `check`
				 on buy.id = `check`.devid
				 where ".implode(" and ", $where);
		$res = $this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function buyInstallFind($paging){
		extract($paging->para['para']['data']);
		$where = $this->findWhere($codeManu,$name,$spec);
		$where[] = "status in(4,5)";
		$where[] = "codeWare is not null";

		if (!empty($take_from))
			$where[] = "takeTime >= '{$install_from}'";

		if (!empty($install_to))
			$where[] = "takeTime <= '$install_to'";

		if ($_SESSION['user'] != 'admin') 
			$where[] = "takeDpt {$this->authDpt}";

		$sql1 = "SELECT  takeTime, depart.depart, factory.depart factory, name, codeManu, spec, status, loc, buy.id
				 from buy 
				 left join depart
				 on depart.id = buy.takeDpt
				 left join depart factory
				 on depart.fid = factory.id
				 where ".
				 implode(" and ", $where)."
				 order by buy.id  desc
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				from install
				left join buy
				on install.devid = buy.id
				where ".implode(" and ", $where);
		$res = $this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function buyInstall($paging){
		$sql1 = "SELECT buy.id id,takeTime,codeManu,buy.name,spec,unit,category.name category,
				 `check`.id chkid, `check`.time checkTime
				 FROM buy
				 left join category
				 on buy.category = category.no
				 left join (
					SELECT id,time,devid from `check` where type=1
				 ) `check`
				 on `check`.devid=buy.id
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
		$res = $this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function takeSpr($sprid, $dptid){
		$takeUser = $_SESSION['user'];
		$takeTime = date("Y-m-d");
		$sql = "UPDATE buy set takeUser='{$takeUser}',takeDpt=$dptid,takeTime='{$takeTime}',status=3 where id in($sprid) or pid in ($sprid)";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function addInfo($data){
		$sql = "INSERT INTO  buy(wareTime,codeWare,name,spec,unit,category,supplier,wareId,status) VALUES";
		for ($i=0; $i < count($data); $i++) { 
			$arr = $data[$i];
			for ($k=0; $k <$arr['AH']; $k++) { 
				$sql .= " ('{$arr['C']}','{$arr['V']}','{$arr['W']}','{$arr['X']}','{$arr['Z']}','{$arr['S']}','{$arr['O']}','{$arr['AP']}', 1),";
			}
		}
		$sql = substr($sql, 0, -1); 
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function delInfo($id){
		$sql = "DELETE FROM buy where id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	} 

	function chgStatus($id){
		$sql = "UPDATE buy set status=2 where id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function cloneCheck($id, $name, $spec, $unit){
		$sql = "INSERT INTO buy(wareTime,codeWare,name,spec,unit,category,supplier,wareId,status) 
				SELECT wareTime,codeWare,'{$name}','{$spec}','{$unit}',category,supplier,wareId,status FROM buy where id = $id";
		$res = $this->sqlHelper->dml($sql);
		return mysql_insert_id();
	}

	function addCheck($id, $codeManu, $accuracy, $scale, $certi, $track, $checkNxt, $valid, $circle, $checkDpt, $outComp, $pid, $path, $class){
		$checkTime = date("Y-m-d");
		$checkUser = $_SESSION['user'];
		$sql = "UPDATE buy SET 
				codeManu = '{$codeManu}',
				accuracy = '{$accuracy}',
				class = '{$class}',
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
				checkDpt = '{$checkDpt}',
				outComp = '{$outComp}'
				where id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function setBas($info, $id, $status){
		$_arr = ['status ='.$status];
		$sql = "UPDATE buy set ".CommonService::sqlTgther($_arr, $info)." WHERE id = $id";
		$res = $this->sqlHelper->dml($sql);
		return $res;
	}

	function getChk($devid, $chkid){
		$sql = "SELECT class,codeManu,accuracy,scale,equip,`usage`,circle,checkComp,checkdpt,track,`check`.time
				from buy
				left join `check`
				on buy.id = `check`.devid
				where buy.id = $devid 
				and `check`.id = $chkid";
		$res = $this->sqlHelper->dql($sql);
		return $res;
	}

	function useSpr($id, $loc){
		$useTime = date("Y-m-d");
		$sql = "UPDATE buy set loc='{$loc}', useTime='{$useTime}', status=4 where id=$id";
		$res = $this->sqlHelper->dml($sql);
		return $res; 
	}

	function buyInstallHis($paging){
		$sql1 = "SELECT takeTime, depart.depart, factory.depart factory, name, codeManu, spec, status, loc, buy.id
				 from buy 
				 left join depart
				 on depart.id = buy.takeDpt
				 left join depart factory
				 on depart.fid = factory.id
				 where status in(4,14) 
				 and codeWare is not null
				 AND takeDpt {$this->authDpt}
				 order by buy.id  desc
				 limit ".($paging->pageNow-1)*$paging->pageSize.",$paging->pageSize";
		$sql2 = "SELECT count(*)
				from install
				left join buy
				on install.devid = buy.id
				where status in(4,14) 
				AND takeDpt {$this->authDpt}";
		$res = $this->sqlHelper->dqlPaging($sql1,$sql2,$paging);
	}

	function getXls($id){
		$sql = "SELECT buy.name, buy.takeDpt dptid, depart.depart, factory.depart, spec, loc, codeManu, scale
				FROM buy
				LEFT JOIN depart
				on depart.id = buy.takeDpt
				LEFT JOIN depart factory 
				on factory.id= depart.fid
				where buy.id=$id";
		$res = $this->sqlHelper->dql($sql);

		$sql = "SELECT num from dpt_num where depart={$res['dptid']}";
		$cljl = $this->sqlHelper->dql($sql);
		$res['CLJL'] = $cljl['num'];
		return $res;
	}

	function getIns($id){
		$sql = "SELECT tech,info,res,runinfo
				from install 
				where devid
				order by id desc
				limit 0,1";
		$res = $this->sqlHelper->dql($sql);
		return $res;
	}

	function installStyle($res, $ins){
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
	  ->setCellValue('E2', $res['CLJL'])
	  ->setCellValue('B6', $ins['tech'])
	  ->setCellValue('B7', $ins['info'])
	  ->setCellValue('B8', $ins['runinfo'])
	  ->setCellValue('B9', $ins['res']);


	  // Redirect output to a client’s web browser (Excel5)
	  header('Content-Type: application/vnd.ms-excel');
	  header('Content-Disposition: attachment;filename='.$res['codeManu']."安装验收.xls");
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

	function unsetNull($arr){
		for ($i=17; $i < count($arr)-1; $i++) { 
			foreach ($arr[$i] as $k => $v) {
				if ($v == null) {
					unset($arr[$i][$k]);
				}
			}
		}
		array_pop($arr);
		return $arr;
	}

	function array_columns($input, $columnKey, $indexKey = null){  
      $result = array();  
       if(!is_array($input))  
             return $result;  
      $isFetchAll = false;  
      foreach($input as $item){  
        if(is_array($columnKey)) {  
           if(empty($columnKey))  
                $isFetchAll = true;  
           if(!empty($columnKey) || $isFetchAll){  
                $tempItem = '';  
                if(!$isFetchAll){  
                    foreach($columnKey as $colKey){  
                         if(isset ($item[$colKey]))  
                              $tempItem[$colKey] = $item[$colKey];  
                    }  
                }else  
                	$tempItem = $item;  
                	if(null !== $indexKey && isset($item[$indexKey]) && !is_array($item[$indexKey]))  
                      $result[$item[$indexKey]] = $tempItem;  
                 	else  
                       $result[] = $tempItem;  
          }  
        }else{  
           if(isset ($item[$columnKey])){  
             if(null !== $indexKey && isset($item[$indexKey]) && !is_array($item[$indexKey]))  
                  $result[$item[$indexKey]] = $item[$columnKey];  
             else  
                   $result[] = $item[$columnKey];  
          }  
        }  
      }  
      return $result;  
	}  

}
?>