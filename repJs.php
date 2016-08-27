<script type="text/javascript">
// 添加维修任务确认按钮
$("#addYes").click(function(){
 var allow_submit = true;
 $("#addMis .form-control").each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
 });
 return allow_submit;
});

// 添加维修任务搜索提示
$("#addMis input[name=name]").bsSuggest({
  allowNoKeyword: false,
  showBtn: false,
  indexKey: 1,
  indexId:0,
  data: {
      'value':<?php 
                $devAll=$repairService->getdevAll();
                echo "$devAll";
             ?>
}
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
    var devid=$(this).attr("data-id");
    $("#addMis input[name=devid]").val(devid);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});


// 搜索任务确认按钮
$("#yesFindMis").click(function(){
   var $notNull=$("#findMis input[name=devName],#findMis input[name=time],#findMis input[type=radio]:checked");
   var allow_submit = true;
   var flag=false;
   $notNull.each(function(){
    if($(this).val()!=""){
      flag=true;
    }
   });
   if (flag==false) {
      $('#failAdd').modal({
         keyboard: true 
      });
      allow_submit = false;
   }
 return allow_submit;
});


// 修改维修任务搜索提示
$("#updateMis input[name=name],#findMis input[name=devName]").bsSuggest({
  allowNoKeyword: false,
  showBtn: false,
  indexKey: 1,
  indexId:0,
  data: {
       'value':<?php 
                    $devAll=$repairService->getdevAll();
                    echo "$devAll";
                 ?>,
}
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
    var devid=$(this).attr("data-id");
    var $parent=$(this).parents("div.modal");
    var $inputId=$parent.find("input[name=devid]");
    $inputId.val(devid);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});

// 搜索条件时间
$("#findMis input[name=time]").datetimepicker({
	format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});

// 添加维修记录
function addInfo(id){
  $.get("controller/repairProcess.php",{
    flag:'getMis',
    id:id
  },function(data,success){
    $("#addInfo textarea[name=err]").val(data.err);
    $("#addInfo input[name=time]").val(data.time);
    $("#addInfo input[name=name]").val(data.name);
    $("#addInfo input[name=liable]").val(data.liable);
    $("#addInfo input[name=devid]").val(data.devid);
    $("#addInfo input[name=misid]").val(data.id);
  },"json")
 $('#addInfo').modal({
      keyboard: true
  });
}

// 添加维修记录确认按钮
$("#addYes").click(function(){
 var allow_submit = true;
 $("#addInfo .form-control").each(function(){
    if($(this).val()==""){
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
 });
 return allow_submit;
});

// 添加维修记录设备搜索提示
    $("#addInfo input[name=name]").bsSuggest({
      allowNoKeyword: false,
      showBtn: false,
      indexKey: 1,
      indexId:0,
      data: {
          'value':<?php 
                    $devAll=$repairService->getdevAll();
                    echo "$devAll";
                 ?>,
    }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
        var devid=$(this).attr("data-id");
        $("#addInfo input[name=devid]").val(devid);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

    // 搜索记录确认按钮
$("#yesFindInfo").click(function(){
   var $notNull=$("#findInfo input[type=text]");
   var allow_submit = true;
   var flag=false;
   $notNull.each(function(){
    if($(this).val()!=""){
      flag=true;
    }
   });
   if (flag==false) {
      $('#failAdd').modal({
         keyboard: true 
      });
      allow_submit = false;
   }
 return allow_submit;
});

// 搜索条件时间
$("#findInfo input[name=time]").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
});


// 修改维修记录搜索提示
$("#updateInfo input[name=name],#findInfo input[name=name]").bsSuggest({
  allowNoKeyword: false,
  showBtn: false,
  indexKey: 1,
  indexId:0,
  data: {
       'value':<?php 
                    $devAll=$repairService->getdevAll();
                    echo "$devAll";
                 ?>,
}
}).on('onDataRequestSuccess', function (e, result) {
    console.log('onDataRequestSuccess: ', result);
}).on('onSetSelectValue', function (e, keyword, data) {
    console.log('onSetSelectValue: ', keyword, data);
    var devid=$(this).attr("data-id");
    // $("#updateInfo input[name=devid]").val(devid);
    var $parent=$(this).parents("div.modal");
    var $inputId=$parent.find("input[name=devid]");
    $inputId.val(devid);
}).on('onUnsetSelectValue', function (e) {
    console.log("onUnsetSelectValue");
});
</script>