<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
 <!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
 <x:ExcelWorksheets>
   <x:ExcelWorksheet>
   <x:Name></x:Name>
   <x:WorksheetOptions>
     <x:DisplayGridlines/>
   </x:WorksheetOptions>
   </x:ExcelWorksheet>
 </x:ExcelWorksheets>
 </x:ExcelWorkbook>
 </xml><![endif]-->
</head>
<?php
header("content-type:text/html;charset=utf-8");
require_once '../model/gaugeService.class.php';	
$cljl = $_GET['cljl'];
$id = $_GET['id'];	
$user = $_GET['user'];	
$gaugeService = new gaugeService();
$res = $gaugeService->getBuyDtl($id);
$res = json_decode($res,true);

$addHtml= "<table>
      <caption align='center'>备件申报明细</caption>
      <tr><td colspan=\"8\" style='text-align:right'>$cljl</td></tr>
			<tr>
      	<th>存货编码</th><th>存货名称</th><th>规格型号</th><th>单位</th><th>数量</th><th>申报单位</th><th>申报人</th><th>备注描述</th>
			</tr>";
      
       for ($i=0; $i < count($res); $i++) { 
  		 $addHtml.="<tr>
  					 <td style='vnd.ms-excel.numberformat:@'>{$res[$i]['code']}</td>
  					 <td>{$res[$i]['name']}</td>
  					 <td>{$res[$i]['no']}</td>
  					 <td>{$res[$i]['unit']}</td>
  					 <td>{$res[$i]['num']}</td>
  					 <td>$dpt</td>
  					 <td>$user</td>
  					 <td>$info</td>
  				    </tr>";
       }
	   $addHtml .= "</table>";

header("Content-type:application/vnd.ms-excel");
Header("Accept-Ranges:bytes");
Header('Content-Disposition:attachment;filename="'.$cljl.'.xls"'); //$filename导出的文件名
header("Pragma: no-cache");
header("Expires: 0");
echo "$addHtml";
?> 
</html>