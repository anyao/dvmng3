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
$devId = $_GET['devid'];	

$gaugeService = new gaugeService();
$res = $gaugeService->installDown($devId);
$date = date("Y-m-d");
$addHtml= "<table>
            <caption align='center'>测量设备安装验收单</caption>
            <tr><td colspan=\"6\" style='text-align:right'>编号：CLJL-{$res['cljl']}-07</td></tr>
            <tr>
              <td>设备名称</td>
              <td colspan=\"2\">{$res['name']}</td>
              <td>使用部门</td>
              <td colspan=\"2\">{$res['factory']}{$res['depart']}</td>
            </tr>
            <tr>
              <td>规格型号</td>
              <td colspan=\"2\">{$res['no']}</td>
              <td>安装地点</td>
              <td colspan=\"2\">{$res['location']}</td>
            </tr>
            <tr>
              <td>出厂编号</td>
              <td colspan=\"2\">{$res['codeManu']}</td>
              <td>量程</td>
              <td colspan=\"2\">{$res['scale']}</td>
            </tr>
            <tr>
              <td>技术参数</td>
              <td colspan=\"5\">{$res['parainfo']}</td>
            </tr>
            <tr>
              <td>运行情况</td>
              <td colspan=\"5\">{$res['runinfo']}</td>
            </tr>
            <tr>
              <td>结论</td>
              <td colspan=\"5\">{$res['conclude']}</td>
            </tr>
            <tr>
              <td>使用方签字：</td>
              <td></td>
              <td>安装方签字：</td>
              <td></td>
              <td>部门签字：</td>
              <td></td>
            </tr>
            <tr><td  colspan=\"6\" style='text-align:right'>日期：{$date}</td></tr>
      			";
      
	   $addHtml .= "</table>";

header("Content-type:application/vnd.ms-excel");
Header("Accept-Ranges:bytes");
Header('Content-Disposition:attachment;filename=CLJL-'.$res['cljl'].'-07.xls');
header("Pragma: no-cache");
header("Expires: 0");
echo "$addHtml";
?> 

</html>