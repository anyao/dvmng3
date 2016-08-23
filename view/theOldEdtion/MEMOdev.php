<!-- cover的背景颜色可能是 05233F。 -->
<script type="text/javascript">
// 有限树形结构列表
$(document).on("click","a[name=open-child]",child_click);
// $("a[]").live("click", child_click);

function child_click(){
	// $("#open-child").click(function(){
	// alert("hello");
	// 获得被点击设备的id值，用于以后的get查询子节点
	var $id=$(this).attr("value");
	// 获取被点击设备的td
	var $parent=$(this).parents("tr");
	// alert($(this).parents("tr").attr("class"));
	// $(this).css("background-color","pink");

	// 设置被点击设备的子设备的class
	var $childNum=parseInt($parent.attr("class").substr(10,1))+1;
	var $childClass="treetable-"+$childNum;
	
	// 获取被点击设备的下一个设备的class
	var $parentNext=$(this).parents("tr").next().attr("class");
	
	var addHtml="<tr class='"+$childClass+"'>"+
                   "<td><a href='javascript:void(0)' class='glyphicon glyphicon-triangle-right' value='123' name='open-child'></a></td>"+
                   "<td>1</td>"+
                   "<td>2</td>"+
                   "<td>3</td>"+
                   "<td>5</td>"+
                   "<td><a href=javascript:inspectRev() class='glyphicon glyphicon-edit'></a></td>"+
                   "<td><a href=javascript:inspectDel() class='glyphicon glyphicon-trash'></a></td>"+
              "</tr>";
	if ($parentNext==$childClass) {
		// 子设备列表显示状态，触发应让其消失
		// 改变icon，使其显示为关闭状态。
		$(this).removeClass('glyphicon glyphicon-triangle-bottom')
			   .addClass('glyphicon glyphicon-triangle-right');
		$("tr[class='"+$childClass+"']").detach();
		// $("tr[class='"+$childClass+"']").click(function(){
		// 	alert("hello");
		// })
		// alert("welldone");
	}else{
		// 子设备列表未加载状态，触发应让其显示
		// alert("hello");
		$(this).removeClass('glyphicon glyphicon-triangle-right')
			   .addClass('glyphicon glyphicon-triangle-bottom');
		$parent.after(addHtml);
	}
}
</script>

<div class="form-group">
  <label class="col-sm-4 control-label">旧设备编号：</label>
  <div class="col-sm-8">
    <div class="input-group">
      <input type="text" class="form-control" name="oldCode" id="oldDev" placeholder="请输入要停用的旧设备编号">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
        </ul>
      </div>
    </div>
  </div>
</div>



          <!-- 在巡检类型下添加新的巡检内容 -->
<div class="modal fade" id="addTypeInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">在巡检类型下添加具体内容项目</h4>
      </div>
      <form class="form-horizontal" action="../controller/inspectProcess.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">巡检类型：</label>
              <div class="col-sm-6">
                <div class="input-group">
                <input type="text" class="form-control" id="findName" name="devCode">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                  </ul>
                </div>
                <!-- /btn-group -->
              </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">新的巡检内容：</label>
              <div class="col-sm-6">
                <input type="text" class="form-control" name="inspecter" placeholder="请输入新的巡检内容" >
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">基本描述：</label>
              <div class="col-sm-6">
                <textarea class="form-control" rows="3" name="inspectInfo" placeholder="请输入该巡检内容所要巡检的基本信息..."></textarea>
              </div>
            </div>     
            <div class="modal-footer">
                <input type="hidden" name="flag" value="addInspect">
                <input type="hidden" name="return" value="list">
                <button type="submit" class="btn btn-primary" id="add">确认添加</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">取消</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

<?php
// $find_name=$_GET['find_name'];
$inspectService=new inspectService();
$info=$inspectService->findDevName();
?>
<script type="text/javascript">
var testdataBsSuggest = $("#findName").bsSuggest({
        allowNoKeyword: false,
        // indexKey: 0,
        data: {
            'value':<?php echo $info;?>,
            // 'defaults':'没有相关设备请另查询或添加新的设备'
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });
</script>