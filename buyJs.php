<script type="text/javascript">
<?php 
$dptService = new dptService($sqlHelper);
$dptAll = $dptService->getDpt();
?>

// 外检input框显示
$("#checkDpt").click(function(){
  if ($(this).val() == "isOut") 
    $("#outComp").show().css("display", "table");
  else
    $("#outComp").hide();
})

// 检定周期加
$(".glyphicon-plus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  num++;
  $circle.val(num);
});

// 检定周期减
$(".glyphicon-minus").parents("button").click(function(){
  var $circle = $(this).parents(".input-group").find("input[type=text]");
  var num = parseInt($circle.val());
  if (num != 1) {
    num--;
    $circle.val(num);
  }
});

$(function(){
	if ($(".page-count").parent().height() < $(window).height()) 
		$(".page-count").css({"position": "fixed", "width" : "40%"});
});
//所有弹出框
$(function () 
  { $("[data-toggle='popover']").popover();
});

//时间选择器
$(".datetime").datetimepicker({
  format: 'yyyy-mm-dd', language: "zh-CN", autoclose: true,minView:2,
  
});

var session = <?= json_encode($_SESSION,JSON_UNESCAPED_UNICODE);?>;
$("#yesFind").click(function(){
  var allow_submit = true;
  $("#findCheck input").each(function(){
    if($(this).val() == ""){
      allow_submit = false;
      $('#failAdd').modal({
	    keyboard: true
	  });
      return false;
    }
  });
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


function authGo(funcid, web){

}

function gotoBuy(funcid,website){
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

function find(funcid,modal){
	var user = session.user;
	var allow_enter = $.inArray(funcid.toString(),session.funcid);
	if (user == "admin") {
		allow_enter = 0;
	}
	
	if(allow_enter != -1){
		$("#find"+modal).modal({
	          keyboard: true
	      });
	}else{
		$('#failCheck').modal({
	          keyboard: true
	    });
	}
}

$(".close-button").click(function(){
  $(".tree").slideUp();
  $(".sidebar-module").slideDown();
  $(this).slideUp();
});



</script>