<script type="text/javascript">
	var session = <?php echo json_encode($_SESSION,JSON_UNESCAPED_UNICODE); ?>;
	var user = session.user;
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
</script>