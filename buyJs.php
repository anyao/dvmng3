<script type="text/javascript">
<?php 
include "./model/dptService.class.php";
$dptService = new dptService();
$dptAll = $dptService->getDpt();
?>
// 搜索表单提交时为空限制
$(".yesFind").click(function(){
	var allow_submit = false;
	var $notNull = $(this).parents("form").find(".form-control");
	var nDpt = $(this).parents("form").find("input[name=nDpt]").val();
	var dptId = $(this).parents("form").find("input[name=dptId]").val();
	// input至少有一个不为空
	$notNull.each(function() {
		if ($(this).val().length > 0) {
			allow_submit = true;
			return false;
		}
	});

	// 部门id不可为空，为空就重新选择
	if (nDpt != "" && dptId == "") {
		// allow_submit = false;
		$('#failDpt').modal({
	        keyboard: true
	    });
	    return false;
	}

	if (allow_submit == false) {
		$('#failAdd').modal({
	        keyboard: true
	    });
	}

	return allow_submit;
});

// 部门搜索提示
 $("input[name=nDpt]").bsSuggest({
    allowNoKeyword: false,
    showBtn: false,
    indexId:2,
    // indexKey: 1,
    data: {
         'value':<?php  echo "$dptAll"; ?>,
    }
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
   console.log('onSetSelectValue: ', keyword, data);
   var idDepart=$(this).attr("data-id");
   $(this).parents("form").find("input[name=dptId]").val(idDepart);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});

//时间选择器
$(".date").datetimepicker({
	format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});


function gotoBuy(pro, phase,website){
	$.post("./controller/gaugeProcess.php",{
		flag:'check',
		pro:pro,
		phase:phase
	},function(data,success){
		if (data != 0 ) {
			location.href="buy"+website+".php";
		}else{
		  $('#failCheck').modal({
	          keyboard: true
	      });
		}
	},'text');
}

function find(pro,phase,modal){
	$.post("./controller/gaugeProcess.php",{
		flag:'check',
		pro:pro,
		phase:phase
	},function(data,success){
		if (data != 0) {
		  $("#find"+modal).modal({
	          keyboard: true
	      });
		}else{
		  $('#failCheck').modal({
	          keyboard: true
	      });
		}
	},'text')
}


$(".close-button").click(function(){
  $(".tree").slideUp();
  $(".sidebar-module").slideDown();
  $(this).slideUp();
});

function flowPro(arr){
	// [{"time":"2016-11-05 13:55:15","user":"admin","res":"1","id":"11","codeManu":null,"devid":null},
	var $addHtml = "";
	var len = arr.length - 1;
	for (var i = 0; i < arr.length; i++) {
		switch (arr[i].res)
		{
			case '1':
				$addHtml += "<li><span class='glyphicon glyphicon-map-marker'></span> "+arr[i].time+"： "+arr[i].user+" 创建。</li>";
				$addHtml += flowNow(i,len,1);
				break;
			case '2':
				$addHtml += "<li><span class='glyphicon glyphicon-ok'></span> "+arr[i].time+"： "+arr[i].user+" 同意。</li>";
				$addHtml += flowNow(i,len,2);
				break;
			case '3':
				$addHtml += "<li><span class='glyphicon glyphicon-remove'></span> "+arr[i].time+"： "+arr[i].user+" 审核不通过。</li>";
				$addHtml += flowNow(i,len,3);
				break;
			case '4':
				$addHtml += "<li><span class='glyphicon glyphicon-search'></span> "+arr[i].time+"：由 "+arr[i].user+" 入厂检定合格。</li>";
				$addHtml += flowNow(i,len,4);
				break;
			case '5':
				$addHtml += "<li><span class='glyphicon glyphicon-remove-circle'></span> "+arr[i].time+"：由 "+arr[i].user+" 检定不合格，需返厂。</li>";
				$addHtml += flowNow(i,len,5);
				break;
			case '6':
				$addHtml += "<li><span class='glyphicon glyphicon-shopping-cart'></span> "+arr[i].time+"：由 "+arr[i].user+" 存库。</li>";
				$addHtml += flowNow(i,len,6);
				break;
			case '7':
				$addHtml += "<li><span class='glyphicon glyphicon-shopping-cart'></span> "+arr[i].time+"：由 "+arr[i].user+" 同意申领。</li>";
				// ,<a href='javascript:getTakeInfo('"+arr[i].time+"','"arr[i].sprid"');'>查看详情</a>
				$addHtml += flowNow(i,len,7);
				break;
			case '8':
				$addHtml += "<li><span class='glyphicon glyphicon-cog'></span> "+arr[i].time+"：由 "+arr[i].user+" 安装投入使用，"+"<a href=\'usingSon.php?id="+arr[i].devid+"\'>查看设备信息</a>。</li>";
				$addHtml += flowNow(i,len,8);
				break;
			case '9':
				$addHtml += "<li><span class=' glyphicon glyphicon-briefcase'></span> "+arr[i].time+"：由 "+arr[i].user+" 将备件全部存为本部门备用。"+"<a href=\'spare.php?id="+arr[i].devid+"\'>查看详情</a></li>";
				break;
			default:
				$addHtml += "<li><span class='glyphicon glyphicon-log-in'></span>"+arr[i].time+":由 "+arr[i].user+"登记入厂，出厂编号为："+arr[i].codeManu+" </li>"
		}
	}
	return $addHtml;

}

function flowNow(i,len,res){
	// alert(i+"---"+len);
	var $addHtml = "";
	var $icon = "<li><span class='glyphicon glyphicon-sort-by-attributes-alt'></span> ";
	if (i == len) {
		switch (res)
		{
			case 1:
				$addHtml += $icon+"等待审核...</li>";
				break;
			case 2:
				$addHtml += $icon+"等待备件入厂...</li>";
				break;
			case 3:
				$addHtml += $icon+"请修改后再重新提交...</li>";
				break;
			case 4:
				$addHtml += $icon+"等待存库入账...</li>";
				break;
			case 5:
				$addHtml += $icon+"等待重新入厂...</li>";
				break;
			default:;
		}
	}
	return $addHtml;
}

// 审批流程
function flowInfo(id){
  $.get("./controller/gaugeProcess.php",{
  	flag:'flowInfo',
  	id:id
  },function(data,success){
	var $addHtml = flowPro(data);
	$("#flowInfo ul").empty().append($addHtml);
	$("#flowInfo").modal({
		keyboard:true
	});
	if(window.seeSpr){
		seeSpr(id);
  	}
  },'json');
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
        addHtml += "<tr class='open open-"+id+"'>"+
                   "<td>"+data[i].id+"</td><td>"+data[i].code+"</td>"+
                   "<td><a href='javascript:flowInfo("+data[i].id+");'>"+data[i].name+"</a></td>"+
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


</script>