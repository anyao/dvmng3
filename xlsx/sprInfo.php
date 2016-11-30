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

require_once '../model/gaugeService.class.php';	
$dev = json_decode($_GET['dev']);


$gaugeService = new gaugeService();
$cljl = $gaugeService->getCLJLByDev($dev[1]);
$res = $gaugeService->sprDown($dev);

$date = date("Y-m-d");

$addHtml = "<table>
            <caption align='center'>测量设备管理台账</caption>
            <tr><th colspan=\"24\" style='text-align:right'>编号：CLJL-$cljl-05</th></tr>
            <tr>
            <th>序号</th><th>管理类别</th><th>设备名称</th><th>规格型号</th><th>精度等级</th><th>量程</th><th>出厂编号</th><th>制造厂</th>
            <th>安装地点</th><th>使用单位</th><th>现状</th><th>启用日期</th><th>新增时间</th><th>测量装置名称及编号</th><th>用途</th>
            <th>检定周期(月)</th><th>检定单位</th><th>检定日期</th><th>有效日期</th><th>实际完成日期</th><th>溯源方式</th><th>证书结论</th>
            <th>确认日期</th><th>确认结论</th>
            </tr>";
foreach ($res as $k => $v) {
  $circle = "+".$v['para'][82]." months";
  $edible = date("Y-m-d", strtotime($circle, strtotime($v['para'][84])));
  $addHtml .= "<tr>
                 <td>$k</td>
                 <td>{$v['para'][88]}</td>
                 <td>{$v['name']}</td>
                 <td>{$v['no']}</td>
                 <td>{$v['para'][79]}</td>
                 <td>{$v['para'][80]}</td>
                 <td>{$v['para'][81]}</td>
                 <td>{$v['supplier']}</td>
                 <td>{$v['pname']}</td>
                 <td>{$v['factory']}{$v['depart']}</td>
                 <td>{$v['state']}</td>
                 <td>{$v['dateInstall']}</td>
                 <td>{$v['para'][89]}</td>
                 <td>{$v['para'][90]}</td>
                 <td>{$v['para'][91]}</td>
                 <td>{$v['para'][82]}</td>
                 <td>{$v['para'][83]}</td>
                 <td>{$v['para'][84]}</td>
                 <td>$edible</td>
                 <td></td>
                 <td>{$v['para'][86]}</td>
                 <td>{$v['para'][87]}</td>
                 <td>{$v['para'][92]}</td>
                 <td>{$v['para'][93]}</td>
               </tr>";
  
}

$addHtml .= "</table>";
$addHtml = $gaugeService->array_iconv($addHtml);
header("Content-type:application/vnd.ms-excel");
Header("Accept-Ranges:bytes");
Header('Content-Disposition:attachment;filename=CLJL-'.$cljl.'-05.xls');
header("Pragma: no-cache");
header("Expires: 0");
echo "$addHtml";
?> 


</html>