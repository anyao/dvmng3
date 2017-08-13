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
                  <input type="password" class="form-control" name="new">        
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
          <button type="button" class="btn btn-default" id="yesChgPwd">确认修改</button>
          <button class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
        </form>
      </div>
    </div>
</div>

<!-- 添加记录不完整提示框 -->
<div class="modal fade"  id="same_new" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">两次输入的新密码不一致，请重新输入。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function chgPwd(){
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

    if ($("#chgPwd input[name=new]").val() != $("#chgPwd input[name=newAgain]").val()) {
      f = "dif_new";
    }

    switch (f){
      case "has_null":
        $('#failAdd').modal({
            keyboard: true
        });
        break;
      case "dif_new":
        $('#same_new').modal({
            keyboard: true
        });
        break;
      default:
       $.get("controller/userProcess.php",{
        flag: "chgPwd",
        new: $("#chgPwd input[name=new]").val(),
        previous: $("#chgPwd input[name=pre]").val()
       },function(data){
         if (data == "suc") 
          $('#chgPwd').modal('hide');
       },'text');
    }
  });
</script>