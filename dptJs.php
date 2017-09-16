<script type='text/javascript'>
var session = <?php echo json_encode($_SESSION,JSON_UNESCAPED_UNICODE); ?>;

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

// 进入角色管理权限
function goto(funcid,website){
  var user = session.user;
  var allow_enter = $.inArray(funcid.toString(),session.funcid);
  if (user == "admin") {
    allow_enter = 0;
  }
  if(allow_enter != -1){
    location.href = "./"+website+".php";
  }else{
    $("#failCheck").modal({
            keyboard: true
        });
  }
}


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
    console.log(data);
    $("#getUserBsc input[getname=id]").val(id);
    $("#getUserBsc input[getname=name]").val(data.name);
    $("#getUserBsc input[getname=code]").val(data.code);
    $("#getUserBsc input[getname=psw]").val(data.psw);
    $("#getUserBsc input[getname=dptid]").val(data.dptid);

    initTreeForUser();
    var treeObj = $.fn.zTree.getZTreeObj("TreeForUser");
    var node = treeObj.getNodeByParam("id", data.dptid, null);
    treeObj.checkNode(node, true, true);

    $("#getUserBsc").modal({ 
      keyboard: true
    });
  },'json');
}

function initTreeForUser(){
  var set = {
      view: {
          selectedMulti: false,
          showIcon: false
      },
      data: {
          simpleData: {
              enable: true
          }
      },
      check: {
          enable: true,
          chkStyle:"radio",
          radioType:'all',
      },
      callback: {
        onClick: setDpt
      }
  };
  var zTree = <?= $dptService->getDptForRole("1,2,3") ?>;
  $.fn.zTree.init($("#TreeForUser"), set, zTree);

}

function setDpt(event, treeId, treeNode){
  $("#getUserBsc input[name=dptid]").val(treeNode.id);
}

// 提交用户修改
$("#yesUptUserBsc").click(function(){
  var allow_submit=true;
  $("#getUserBsc input[type!=hidden]").each(function(){
    if ($(this).val()=="") {
      $("#failUptUser").show();
      allow_submit=false;
      return false;
    }
  });

  var treeObj = $.fn.zTree.getZTreeObj("TreeForUser");
  var nodes = treeObj.getCheckedNodes(true);
  if (nodes.lenth == 0) {
     $("#failUptUser").show();
     allow_submit=false;
  }

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
  var user = session.user;
  var ifFunc = $.inArray('13',session.funcid);
  if (user == "admin") {
    ifFunc = 0;
  }
  if(ifFunc != -1 ){
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
  }else{
    $('#failCheck').modal({
        keyboard: true
    });
  }
  
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