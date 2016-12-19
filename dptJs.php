<script type='text/javascript'>
// zTree参数设置
var setting = {
    view: {
        selectedMulti: false,
        showIcon: false
    },
    check: {
        enable: true,
        // autoCheckTrigger: true,
        chkboxType: { "Y" : "s", "N" : "s" }
    },
    data: {
        simpleData: {
            enable: true
        }
    }
};


// 获取用户管理部门的范围
function getScale(id){
  $.get("./controller/dptProcess.php",{
    flag:'getUserDpt',
    uid:id
  },function(data){
    var dptid = "";
    for (var i = 0; i < data.length; i++) {
      if(data[i].uid){
        dptid += data[i].id+",";
      }
    }

    $.fn.zTree.init($("#treeUpt"), setting, data);
    uptTree = $.fn.zTree.getZTreeObj("treeUpt");
    
    $("#getUserDpt input[name=dptid]").val(dptid);
    $("#getUserDpt input[name=uid]").val(id);
    $("#getUserDpt").modal({ 
      keyboard: true
    });
  },'json');
}

// 提交用户管理范围的修改
$("#yesUptUserDpt").click(function(){
  var valDpt = $("#getUserDpt input[name=dptid]").val();
  var uptNodes = uptTree.getCheckedNodes(true);
  if (uptNodes.length == 0) {
    // 范围为空，不可提交
    $("#failAdd").modal({
      keyboard: true
    });
  }else{
    var nodeDpt = "";
    for (var i = 0; i < uptNodes.length; i++) {
        nodeDpt += uptNodes[i].id+",";
    }

    $("#getUserDpt input[name=dptid]").val(nodeDpt);
    $.get("./controller/dptProcess.php",$("#formUserDpt").serialize(),function(data){
      if (data == "success") {
        var url = window.location.pathname.split("/").pop();
        $("#getUserDpt").modal('hide');
        if (url == "dptUser.php") {
          getUser($("#userMsg").attr("dptid"));
        }
      }
    },'text');
  }
});

// 修改用户基本信息
function getBsc(id){
  $.get("controller/dptProcess.php",{
    flag:'getUserBsc',
    id:id
  },function(data,success){
    $("#getUserBsc input[name=id]").val(id);
    $("#getUserBsc input[name=name]").val(data.name);
    $("#getUserBsc input[name=code]").val(data.code);
    $("#getUserBsc input[name=dptid]").val(data.dptid);
    $("#getUserBsc input[name=dptName]").val(data.depart);
    $("#getUserBsc input[name=psw]").val(data.psw);
    // 修改用户信息弹出框下的部门提示
    $("#getUserBsc input[name=dptName]").bsSuggest({
        allowNoKeyword: false,
        showBtn: false,
        indexId:1,
        data: {
             'value':<?php $dptAll = $dptService->getDpt();
                           echo "$dptAll"; ?>
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
       console.log('onSetSelectValue: ', keyword, data);
       var dptid = $(this).attr("data-id");
       $(this).parents("form").find("input[name=dptid]").val(dptid);
    }).on('onUnsetSelectValue', function (e) {
        console.log("onUnsetSelectValue");
    });
    $("#getUserBsc").modal({ 
      keyboard: true
    });
  },'json');
}

// 提交用户修改
$("#yesUptUserBsc").click(function(){
  var allow_submit=true;
  $("#getUserBsc input[type!=hidden]").each(function(){
    if ($(this).val()=="") {
      $("#failAdd").modal({ 
        keyboard: true
      });
      allow_submit=false;
    }
  });
  if (allow_submit==true) {
    var dptid=$("#formUptUser input[name=dptid]").val();
    $.get("controller/dptProcess.php",$("#formUptUser").serialize(),function(){
      var url = window.location.pathname.split("/").pop();
      $("#getUserBsc").modal('hide');
      if (url == "dptUser.php") {
        getUser(dptid);
      }
    },'text');
  }
});


function getRole(id){
  $.get("./controller/dptProcess.php",{
    flag:"getUserRole",
    uid:id
  },function(data){
    var rid = "";
    for (var i = 0; i < data.length; i++) {
      $("#getUserRole span[role="+data[i].rid+"]").removeClass("glyphicon-unchecked").addClass('glyphicon-check');
      rid += data[i].rid+",";
    }
    $("#getUserRole input[name=uid]").val(id);
    $("#getUserRole input[name=rid]").val(rid);
    $("#getUserRole").modal({ 
      keyboard: true
    });
  },'json'); 
}

// 提交修改用户Role
$("#yesUptUserRole").click(function(){
  var rid = "";
  var $check = $("#getUserRole .glyphicon-check");
  $check.each(function(){
    rid += $(this).attr("role")+",";
  });
  $("#getUserRole input[name=rid]").val(rid);
  $.get("./controller/dptProcess.php",$("#formUserRole").serialize(),function(data){
    if (data == "success") {
      var url = window.location.pathname.split("/").pop();
      $("#getUserRole").modal('hide');
      if (url == "dptUser.php") {
        getUser($("#userMsg").attr("dptid"));
      }
    }else{
      alert("操作失败请联系管理员。");
    }
  },'text');
});

function delUser(id){
  $("#yesDelUser").attr("uid",id);
  $("#delUser").modal({ 
    keyboard: true
  });
}

$("#yesDelUser").click(function(){
  var uid=$(this).attr("uid");
  $.get("controller/dptProcess.php",{
    flag:'delUser',
    uid:uid
  },function(data,success){
    if (data=="success") {
      $("#delUser").modal('hide');
      var url = window.location.pathname.split("/").pop();
      if (url == "dptUser.php") {
        getUser($("#userMsg").attr("dptid"));
      }else{
        yesFind();
      }
    }
  },"text");
});

// 选中按钮
$(".col-md-3 > p > .glyphicon").click(function(){
  $(this).toggleClass("glyphicon-check");
  $(this).toggleClass("glyphicon-unchecked");
}); 
</script>