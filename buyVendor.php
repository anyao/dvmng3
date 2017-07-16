<link rel="stylesheet" type="text/css" href="bootstrap/css/printview.css">
<link rel="stylesheet" href="bootstrap/css/fileinput.css">
<link rel="stylesheet" href="bootstrap/css/choose.css" type="text/css">
<link rel="stylesheet" href="tp/datetimepicker.css">
<link rel="stylesheet" href="bootstrap/css/metroStyle/metroStyle.css">
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="bootstrap/js/html5shiv.js"></script>
  <script src="bootstrap/js/respond.js"></script>
<![endif]-->

<script src="bootstrap/js/jquery.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="tp/bootstrap-datetimepicker.js"></script>
<script src="tp/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="bootstrap/js/bootstrap-suggest.js"></script>
<script src="bootstrap/js/fileinput.js"></script>
<script src="bootstrap/js/zh.js"></script>
<script src="bootstrap/js/jquery.ztree.core.js"></script>
<script src="bootstrap/js/jquery.ztree.excheck.min.js"></script>
<script src="bootstrap/js/jquery.ztree.exedit.js"></script>
<!-- <script src="bootstrap/js/Chart.js"></script> -->
<!-- <script src="bootstrap/js/chartEffects.js"></script> -->
<script src="bootstrap/js/chartModernizr.js"></script>
<script type="text/javascript">
	$(function(){
		$(".page-count").height(document.scrollHeight);
	});

	var session = <?=json_encode($_SESSION, JSON_UNESCAPED_UNICODE)?>;
	function allow_enter(funcid){
	  var user = session.user;
	  var allow = $.inArray(funcid.toString(),session.funcid);
	  if (user == "admin") {
	    allow = 0;
	  }
	  return allow != -1 ? true : false;
	}

</script>