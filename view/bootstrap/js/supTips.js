// $(document).on("click","a[name=open-child]",child_click);
	// $("a[]").live("click", child_click);

    // function openTips(){

	$("a[name=listInfo]").click(function(){
	// alert("hello");
	// 获得被点击设备的id值，用于以后的get查询子节点
	var $id=$(this).attr("value");
	// 获取被点击设备的td
	var $parent=$(this).parents("tr");
	// alert($(this).parents("tr").attr("class"));
	// $(this).css("background-color","pink");
	
	// 获取被点击设备的下一个设备的class
	var $parentNext=$(this).parents("tr").next().attr("class");
	
	var addHtml="<tr class='tips'>"+
				 "<td colspan='12'> "+
                    "<div class='row'>"+
                      "<div class='col-md-6'>"+
                        "<p><b>设备名称：</b>1</p>"+
                        "<p><b>设备型号：</b>abc</p>"+
                        "<p><b>设备规格：</b>0310-5178939</p>"+
                         "<p><b>主要技术要求：</b>北京故宫于明成祖永乐四年（1406年）开始建设，以南京故宫为蓝本营建，到永乐十八年（1420年）建成。</p>"+
                      "</div>"+
                      "<div class='col-md-6'>"+
                        "<p><b>所需数量：</b>中通、顺丰、江浙沪包邮</p>"+
                        "<p><b>参考单价：</b>10010</p>"+
                        "<p><b>参考生产厂家：</b>"+
                        "<a href='#'>苹果</a>、"+
                        "<a href='#'>三星</a>、"+
                        "<a href='#'>诺基亚</a>、</p>"+
                         "<p><b>申请用途原因：</b>北京故宫是中国明清两代的皇家宫殿，旧称为紫禁城，位于北京中轴线的中心。是中国古代宫廷建筑之精华</p>"+
                      "</div>"+
                    "</div>"+
                  "</td>  "+
               "</tr>";
	
	if ($parentNext=="tips") {
		// 子设备列表显示状态，触发应让其消失
		// 改变icon，使其显示为关闭状态。
		$(this).removeClass('glyphicon glyphicon-resize-full')
			   .addClass('glyphicon glyphicon-resize-small');
		$parent.next().detach();
	}else{
		// 子设备列表未加载状态，触发应让其显示
		// alert("hello");
		$(this).removeClass('glyphicon glyphicon-resize-small')
			   .addClass('glyphicon glyphicon-resize-full');
		$parent.after(addHtml);
		
	}
		
})



