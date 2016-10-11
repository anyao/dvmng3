<script type="text/javascript">
function gotoBuy(pro, phase){
	$.post("./controller/gaugeProcess.php",{
		flag:'check',
		pro:pro,
		phase:phase
	},function(data,success){
		if (data != 0 ) {
			location.href="buyApv.php";
		}else{
		  $('#failCheck').modal({
	          keyboard: true
	      });
		}
	},'text');
}

function buyList(obj,id){
  var flagIcon=$(obj).attr("class");
  var $rootTr=$(obj).parents("tr");
  // 列表是否未展开
  if (flagIcon=="glyphicon glyphicon-resize-small") {
    // 展开
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-full");
    $.get("controller/gaugeProcess.php",{
      flag:'getBuyDtl',
      id:id
    },function(data,success){
      var addHtml = "<tr class='open open-"+id+"'>"+
                    "<th>编号</th><th>存货编码</th><th>存货名称</th><th>规格型号</th><th>数量</th><th>备注描述</th>"+
                    "<th></th><th></th>"+
                    "</tr>";
      for (var i = 0; i < data.length; i++){
        addHtml += "<tr class='open "+data[i].id+" open-"+id+"'>"+
                   "<td>"+data[i].id+"</td><td>"+data[i].code+"</td>"+
                   "<td><a href='javascript:apvSpr("+data.id+");'>"+data[i].name+"</a></td>"+
                   "<td>"+data[i].no+"</td><td>"+data[i].num+" "+data[i].unit+"</td><td colspan='3'>"+data[i].info+"</td>"+
                   "</tr>";
      }
      addHtml += "</tr>";
      $rootTr.after(addHtml);
    },'json');
  }else{
    $(obj).removeClass(flagIcon).addClass("glyphicon glyphicon-resize-small");
    $(".open-"+id).detach();
  }
}


$(".close-button").click(function(){
  $(".tree").slideUp();
  $(".sidebar-module").slideDown();
  $(this).slideUp();
})

</script>