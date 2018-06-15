var convertgoods=(function($){
	var dataobj=pubFun.dataobj;
	var initFun=function(){
		 pubFun.pageaction(); 
		 dataobj.module='3';//充值模块
	}
	var gamechange=function(){
		$('.nav-tabs li ').each(function(index, el) {
			$(this).on('click',function(){
			dataobj.playid=null;
			dataobj.ordertype=null;
			dataobj.orderfield=null;
			dataobj.phone=null;
			dataobj.playertype=null;
			dataobj.page=1;
			dataobj.gameid=$(this).find('a').attr('data-gameid');
			expage(dataobj);
		})
		});
	}
	var changeTable=function(data,dataobj){
		var pageico=data[3];//当前页码
		var page=data[0];//总页数
		var data=data[1];//返回数据
		var html='';
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+$(this)[0].paytime+"</td>";
			temp+="<td class='center'>"+$(this)[0].payplayers+"</td>";
			temp+="<td class='center'>"+$(this)[0].paynums+"</td>";
			temp+="<td class='center'>"+$(this)[0].paymoney+"</td>";
			temp+="<td class='center'>"+$(this)[0].newpayplayers+"</td>";
			temp+="<td class='center'>"+$(this)[0].newpaynums+"</td>";
			temp+="<td class='center'>"+$(this)[0].newpaymoney+"</td>";
			html+=temp;
		});
		$('#table_main').html(html);
		$('#allpages').html(page);
		$("#pagenums").val(pageico);
	 }
	var expage=function(conds){
		$.ajax({
			    type: "post",
			    data: conds,
			    url: "/qpht.php/gamemanage/seachdata4",
			    beforeSend: function () {
			        // 禁用按钮防止重复提交，发送前响应
			        pubFun.showloading();
			    },
			    success: function (data) {
			            pubFun.hideloading();
			            changeTable(data,conds);
			            pubFun.pageaction();
			    },
			    complete: function () {//完成响应
			         
			    },
			    error: function (data) {
			        console.info("error: " + data.responseText);
			    }
			});
	}
	//默认翻页方法
	var defaultexpage=function(){
		var gameid=$('#gameid').val();
		$('#gopage').on('click',function(){
				dataobj.page=$('#pagenums').val();
				dataobj.gameid=$('#gameid').val();
				expage(dataobj);
			})
	}
	var pageaction=function(){
		$('.pagediv').on('click','#goprevx',function(){
			dataobj.page=1;
			expage(dataobj);
		})
		$('.pagediv').on('click','#goprev',function(){
			var nowpage=$('#pagenums').val();
			dataobj.page=(nowpage-1);
			expage(dataobj);
		})
		$('.pagediv').on('click','#gonext',function(){
			var nowpage=$('#pagenums').val();
			dataobj.page=(parseInt(nowpage)+1);
			expage(dataobj);
		})
		$('.pagediv').on('click','#gonextx',function(){
			dataobj.page=$('#allpages').html();
			expage(dataobj);
		})
	}
	pageaction();
	gamechange();
	initFun();
	/*ordercss();
	orderdata();*/
	defaultexpage();
	// searchTime();
})($);
