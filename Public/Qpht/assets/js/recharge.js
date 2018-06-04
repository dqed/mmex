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
		//var gamename=pubFun.getgamename(dataobj.gameid);
		var pageico=data[3];//当前页码
		var page=data[0];//总页数
		var data=data[1];//返回数据
		// var stime=;//查询开始时间
		// var etime=//查询结束时间
		// console.log(data);
		// return false;
		//$('#timespan').attr('data-begin',dataobj.bgtime).attr('data-end',dataobj.entime);
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
		var temppage='';
		for (var i = 0; i < page; i++) {
			var x= i+1
			temppage+="<option vaule='"+x+"'>"+x+"</option>";
		}
		//console.log(dataobj);
		$('#pagenums').html(temppage);
		$('#allpages').html(page);
		//pageico==1,说明重新进行了一次查询，重新绑定翻页函数
		if(pageico==1){
			$('#pagenums').unbind();
			$('#pagenums').on('change',function(){
				dataobj.page=$(this).val();
				expage(dataobj);
			})	
		}
		$('#pagenums option').each(function(index, el) {
			if($(this).val()==pageico){
				$("#pagenums").val(pageico);
			}
		});
		
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
		// $.post("/qpht.php/gamemanage/seachdata4",conds,function(data,status){
	 //  		changeTable(data,conds);
	 //  	});
	}
	//默认翻页方法
	var defaultexpage=function(){
		var gameid=$('#gameid').val();
		$('#pagenums').on('change',function(){
				dataobj.page=$(this).val();
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
