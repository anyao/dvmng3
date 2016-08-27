<script type="text/javascript">
  // 添加巡检标准的设备名称搜索提示
   $("#addStd input[name=name]").bsSuggest({
        allowNoKeyword:false,
        showBtn: false,
        indexKey: 0,
        indexId:1,
        data: {
             'value':<?php 
              $resUsing=$inspectService->getUsingAll();
              echo $resUsing;
              ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
        var devid=$(this).attr("data-id");
        $("#addStd input[name=devid]").val(devid);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

     // 所添加信息不完整时弹出提示框
   $("#addStdYes").click(function(){
     var allow_submit = true;
     $("#addStd .form-control").each(function(){
        if($(this).val()==""){
          $('#failAdd').modal({
              keyboard: true 
          });
          allow_submit = false;
        }
     });
     return allow_submit;
   });

   //搜索巡检标准中，input框为空时，弹出警告
    $("#yesStdFind").click(function(){
       var $notNull=$("#findStd input[type=text]");
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

     // 巡检标准搜索
    $("#findStd input[name=name]").bsSuggest({
        allowNoKeyword:false,
        showBtn: false,
        indexKey: 0,
        indexId:1,
        inputWarnColor: '#f5f5f5',
        data: {
             'value':<?php 
              $resUsing=$inspectService->getUsingAll();
              echo $resUsing;
              ?>,
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
        var devid=$(this).attr("data-id");
        $("#findStd input[name=devid]").val(devid);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });
    // 展开设备巡检的具体标准
    function openInfo(obj,id){
      $("#stdInfo-"+id).toggle();
      $(obj).toggleClass("glyphicon glyphicon-resize-small");
      $(obj).toggleClass("glyphicon glyphicon-resize-full");     
    }

    // 添加任务弹出框中确认添加时间按钮
    $("#addMis #yesTime").click(function(){
      if($("#addMis .datetime").val().length>0){
        var timeNew=$("#addMis .datetime").val();
        var addHtml="<span class='badge'>"+timeNew+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='time[]' value="+timeNew+"></span> "
        $("#addMis #forTime").append(addHtml);
        $("#addMis .datetime").val("");
      }else{
        $('#noTime').modal({
          keyboard: true
        });
      }  
    });

    // 添加任务弹出框中确认添加设备按钮
    $("#addMis #yesDev").click(function(){
      if($("#addMis input[name=devName]").val().length>0){
        var nameDev=$("#addMis input[name=devName]").val();
        var idDev=$("#addMis input[name=devName]").attr("data-id");
        var addHtml="<span class='badge'>"+nameDev+" <a href='javascript:void(0);' class='glyphicon glyphicon-remove' style='color: #f5f5f5;text-decoration: none'></a><input type='hidden' name='dev[]' value="+idDev+"></span> "
        $("#addMis #forDev").append(addHtml);
        $("#addMis input[name=devName]").val("");
      }else{
        $('#noDev').modal({
          keyboard: true
        });
      }  
    });

    // 确认添加按钮
   $("#addYes").click(function(){
    var allow_submit = true;
    var forTime=$("#addMis #forTime input").length;
    var forDev=$("#addMis #forDev input").length;
    if (forTime==0 || forDev==0) {
      $('#failAdd').modal({
          keyboard: true
      });
      allow_submit = false;
    }
     return allow_submit;
   });

   // 搜索点检任务确认按钮
    $("#yesMisFind").click(function(){
       var $notNull=$("#findMis input[type=text]");
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

    // 搜索点检任务中的设备搜索任务
    $("#findMis input[name=name]").bsSuggest({
        allowNoKeyword: false,
         showBtn: false,
        indexKey: 0,
        indexId:1,
        inputWarnColor: '#f5f5f5',
        data: {
           'value':<?php
                    $devAll=$inspectService->getUsingAll();
                    echo "$devAll";
                   ?>,
            // 'defaults':'没有相关设备请另查询或添加新的设备'
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
        var devid=$(this).attr("data-id");
        $("#findMis input[name=devid]").val(devid);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

     // 添加巡检记录弹出框中的设备列表显示隐藏设置
    $(function () {
      devErr();
      $("#addInfo input[type!=hidden][name!=result]").val("");
    });

    // 若点击巡检结果，设备列表的显示隐藏
    $("#addInfo").on("click","input[name=result]",devErr);
    function devErr(){
      var result=$("#addInfo input[name=result]:checked").val();
      if (result=="正常") {
        $("#haveErr").hide();
      }else{
        $("#haveErr").show();
      }
    }

   
    $("#haveErr").on("click","span",errInfo);
    function errInfo(){
      var id=$(this).children("input").val();
      var time=$("#addInfo input[name=time]").val();
      var liable=$("#addInfo input[name=liable]").val(); 
      var result=$("#addInfo input[name=result]:checked").val();
      var addHtml="<input type='hidden' name='time' value='"+time+"'>"+
                  "<input type='hidden' name='liable' value='"+liable+"'>"+
                  "<input type='hidden' name='result' value='"+result+"'>"+
                  "<input type='hidden' name='devid' value='"+id+"'>";
      // if(result=="保养"){
      //   $("#mainInfo .modal-body").children("input[type=hidden]").detach();
      //   $("#mainInfo .modal-body").append(addHtml);
      //   $('#mainInfo').modal({
      //         keyboard: true
      //   });  
      // }else 
      if(result=="需维修"){
        $("#repairInfo .modal-body").children("input[type=hidden]").detach();
        $("#repairInfo .modal-body").append(addHtml);
        $('#repairInfo').modal({
              keyboard: true
        }); 
      }
    }

    $("#addYes").click(function(){
     var allow_submit = true;
     $("#addInfo  .form-control").each(function(){
        if($(this).val()==""){
          // alert("hello");
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }
     });
     return allow_submit;
   });

    $("input[name=inspMis]").bsSuggest({
        allowNoKeyword: false,
        indexKey: 1,
        indexId:2,
        showHeader: true,
        showBtn:false,
        effectiveFieldsAlias:{start:'时间',name:'路线',devid:'编号'},
        data: {
          'value':<?php 
                    $allMis=$inspectService->getMisAll();
                    echo "$allMis";
                 ?>
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
        var idList=$(this).attr("data-id");
        $("#addInfo input[name=idList]").val(idList);
        var nameList=$(this).val();
        var nameArr=nameList.split("-");
        var idArr=idList.split("-");
        var addHtml="";
        for(var i in nameArr){
          if (nameArr[i]!="" && idArr!="") {
            addHtml+="<span class='badge' style='cursor:pointer'>"+nameArr[i]+
                     "  <input type='hidden' name='idErr[]' value="+idArr[i]+">"+
                     "</span> ";
          }
        }
        $("#haveErr .col-sm-7").children("span").detach();
        $("#haveErr .col-sm-7").append(addHtml);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

    $("input[name=errDev]").bsSuggest({
        allowNoKeyword: false,
        indexKey: 0,
        indexId:3,
        showBtn:false, 
        data: {
          'value':<?php 
                    $allMis=$inspectService->getMisAll();
                    echo "$allMis";
                 ?>
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });

    // 搜索确认按钮
    $("#yesInfoFind").click(function(){
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

    // 备注信息提示 
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
  // 个别设备维修备注和维修任务添加提交按钮
    $("#addRepair").click(function(){
      var id=$("#repairInfo input[name=devid]").val();
      var allow_submit = true;
     $("#repairInfo .modal-body textarea").each(function(){
        if($(this).val()==""){
          $('#failAdd').modal({
              keyboard: true
          });
          allow_submit = false;
        }else{
          $.post("controller/inspectProcess.php",$("#repairForm").serialize(),function(data,success){
            if (data!="fail") {
              $("#repairInfo").modal('hide');
              $("#haveErr span input[value="+id+"]").parents("span.badge").detach();
            }else{
              alert("添加维修任务失败，请联系管理员。0310-5178939。");
            }
          });
        }
     });
     return allow_submit;
   });

 
    $("#tree-open").click(
      function () {
          $(".tree").slideDown();
          $(".close-button").slideDown();
          $(".sidebar-module").slideUp();
          //树形导航
          $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
          $('.tree li.parent_li > span').on('click', function (e) {
          var children = $(this).parent('li.parent_li').find(' > ul > li');
          if (children.is(":visible")) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
          } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
          }
          e.stopPropagation();
          });
      });


      $(".close-button").click(function(){
        $(".tree").slideUp();
        $(".sidebar-module").slideDown();
        $(this).slideUp();
      });


</script>