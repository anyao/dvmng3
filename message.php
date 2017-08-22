<?php  
$msgService = new msgService($sqlHelper);
$validNum = $msgService->getCountValid();
?>

<?php if ($validNum != 0): ?>
  <div class="row" id="message">
   <div class='col-md-12' >
      <div class='alert alert-warning' id='mesSee'>
         <a href=javascript:void(0) class='close' data-dismiss='alert'>&times;</a>
         <strong>当前有 <?=$validNum?> 个检定任务。</strong><a href='checkMis.php'>点击查看</a>
      </div>
    </div>
  </div>
<?php endif ?>


<!-- 查看当前用户具体信息 -->
<div class="modal fade" id="chgPwd" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">修改密码</h4>
        </div>
        <form class="form-horizontal">
          <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-3 control-label">原密码：</label>
              <div class="col-sm-7">
                  <input type="password" class="form-control" name="pre">        
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label">新密码：</label>
              <div class="col-sm-7">
                  <input type="password" class="form-control" name="new" placeholder="密码最长不可超过20位">        
              </div>
            </div>
			
			     <div class="form-group">
            <label class="col-sm-3 control-label">确认新密码：</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" name="newAgain">        
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <input type="hidden" name="flag" value="chgPwd">
          <span style="color:red;display:none" id="failPer">信息填写不完整。</span>
          <span style="color:red;display:none" id="failLong">密码长度不可超过20位。</span>
          <span style="color:red;display:none" id="failDif">两次输入的新密码不一致，请重新输入。</span>
          <button type="button" class="btn btn-default" id="yesChgPwd">确认修改</button>
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
        </form>
      </div>
    </div>
</div>


<script type="text/javascript">
  function chgPwd(){
    $("#failDif,#failPer,#failLong").hide();
    $('#chgPwd').modal({
        keyboard: true
    });
  }

  $("#yesChgPwd").click(function(){
    var f = true;
    $("#chgPwd input").each(function(){
      if ($(this).val() == "") {
        f = "has_null";
        return;
      }
    });

    if ($("#chgPwd input[name=new]").val().length > 20) 
      f = "too_long";

    if ($("#chgPwd input[name=new]").val() != $("#chgPwd input[name=newAgain]").val()) {
      f = "dif_new";
    }

    switch (f){
      case "has_null":
        $("#failPer").show();
        break;
      case "dif_new":
        $("#failDif").show();
        break;
      case "too_long":
        $("#failLong").show();
      default:
       $.get("controller/userProcess.php",{
        flag: "chgPwd",
        new: $("#chgPwd input[name=new]").val(),
        pre: $("#chgPwd input[name=pre]").val()
       },function(data){
         if (data == "suc") 
          $('#chgPwd').modal('hide');
         else
          alert("更改失败，"+data+"。");
       },'text');
    }
  });
</script>