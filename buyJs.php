<script type="text/javascript">
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


$(".close-button").click(function(){
  $(".tree").slideUp();
  $(".sidebar-module").slideDown();
  $(this).slideUp();
});

// 审批流程
function flowInfo(id){
  $("#flowInfo").modal({
    keyboard:true
  });
}

</script>