
<!-- 获得用户基本信息 -->
<div class="modal fade" id="getUserBsc">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">用户基本信息</h4>
      </div>
      <form class="form-horizontal" id="formUptUser">
        <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">用户编号：</label>
            <div class="col-sm-7">  
              <input type="text" class="form-control" name="data[code]" readonly getname="code">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">用户名称：</label>
            <div class="col-sm-7">  
              <input type="text" class="form-control" name="data[name]" getname="name">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">所在部门：</label>
            <div class="col-sm-7">  
              <ul id="TreeForUser" class="ztree" style="overflow-y: scroll; height: 150px"></ul>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">登录密码：</label>
            <div class="col-sm-7">  
              <input type="password" class="form-control" name="data[psw]" getname="psw">
            </div>
          </div>

          </div> 
          <div class="modal-footer">
            <input type="hidden" name="flag" value="uptUserBsc">
            <input type="hidden" name="id">
            <input type="hidden" name="data[dptid]" getname="dptid">
            <span style="color:red;display:none" id="failUptUser">信息填写不完整</span>
            <button type="button" class="btn btn-primary" id="yesUptUserBsc">确认修改</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
          </div>
        </form>
    </div>
  </div>
</div>

<!-- 获得用户角色组 -->
<div class="modal fade" id="getUserRole">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">用户角色分组</h4>
      </div>
      <form class="form-horizontal" id='formUserRole' action="./controller/dptProcess.php">
        <div class="modal-body">
         <div class="row" style="margin-top: 20px">
             <?php 
             if (!empty($roleList)) {
             	echo "$roleList"; 
             }else{
	            $role = $dptService->getRoleAll();
	            $roleList = "";
	            for ($i=1; $i < count($role); $i++) { 
	              $roleList .= "<div class='col-md-3'>
	                              <p>
	                                <span class='glyphicon glyphicon-unchecked' role='{$role[$i]['id']}'></span> {$role[$i]['title']}
	                              </p>
	                           </div>";
	            }
	            echo "$roleList";
             }
             ?>
         </div>
        </div> 
        <div class="modal-footer">
          <input type="hidden" name="uid">
          <input type="hidden" name="rid">
          <input type="hidden" name="flag" value="uptUserRole">
          <button type="button" class="btn btn-primary" id="yesUptUserRole">确认修改</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>
        </form>
    </div>
  </div>
</div>

<!-- 获得用户可操作范围 -->
<div class="modal fade" id="getUserDpt">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">管理部门范围</h4>
      </div>
      <form class="form-horizontal" id="formUserDpt" action="./controller/dptProcess.php">
        <div class="modal-body">
          <div class="row" style="height: 500px;overflow-y:scroll;">
            <div class="col-md-offset-2">
              <ul id="treeUpt" class="ztree"></ul>
              
            </div>
          </div>
        </div> 
        <div class="modal-footer">
          <input type="hidden" name="uid">
          <input type="hidden" name="dptid">
          <input type="hidden" name="flag" value="uptUserDpt">
          <button type="button" class="btn btn-primary" id="yesUptUserDpt">确认修改</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        </div>
        </form>
    </div>
  </div>
</div>

<!-- 删除失败弹出框 -->
<div class="modal fade"  id="failAdd">
  <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body"><br/>
            <div class="loginModal">您需要添加的信息不完整，请补充。</div><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div> 

<!-- 删除用户警告框 -->
<div class="modal fade"  id="delUser">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>确定要删除该用户记录吗？<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-danger" id="yesDelUser">删除</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

<!-- 无权进入 -->
<div class="modal fade"  id="failCheck" >
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
         <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-10px"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
          <br>您无权进行该操作！<br/><br/>
         </div>
         <div class="modal-footer">  
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
    </div>
  </div>
</div>

