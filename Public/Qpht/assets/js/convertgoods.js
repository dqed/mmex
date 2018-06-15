var convertgoods=(function($){
	var dataobj=pubFun.dataobj;
	var initFun=function(){
		 // dataobj.bgtime=$("#timespan").attr('data-begin');
		 // dataobj.entime=$("#timespan").attr('data-end');
		 dataobj.gameid=$('#gameid').val();
		 // dataobj.playertype=0;
		 dataobj.ordertype=null;
		 dataobj.orderfield=null;
		 dataobj.module='2';//兑换相关
		 pubFun.pageaction();
	}
	var searchTime=function(){
		var myDate=new Date();
		$('#seachbtn').on('click',function(){
			// dataobj.bgtime=$('#begin_time').val();
			// dataobj.entime=$('#end_time').val();
			dataobj.page=1;
			dataobj.phone=$('#playerphone').val();
			dataobj.playid=$('#playerid').val();
			dataobj.gameid=$('#gametype').val();
			// var btime=new Date(dataobj.bgtime);
			// var etime=new Date(dataobj.entime);
			if(dataobj.phone==''&&dataobj.playid==''&&dataobj.gameid==''){
				alert('选择搜索参数');
				return false;
			}else{
				//console.log(dataobj);
				expage(dataobj);
			}
		})
	}
	var changeTable=function(data,dataobj){
		var gamename=pubFun.getgamename(dataobj.gameid);
		var pageico=data[3];//当前页码
		console.log(pageico);
		var page=data[0];//总页数
		var data=data[1];//返回数据
		var html='';
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+((pageico-1)*pubFun.fynum+index+1)+"</td>";
			temp+="<td class='center'>"+$(this)[0].user_id+"</td>";
			temp+="<td class='center'>"+gamename+"</td>";
			temp+="<td class='center'>"+$(this)[0].data+"</td>";
			temp+="<td class='center'>"+$(this)[0].goods_name+"</td>";
			temp+="<td class='center'>"+$(this)[0].user_phone+"</td>";
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
			    url: "/qpht.php/gamemanage/seachdata3",
			    beforeSend: function () {
			        // 禁用按钮防止重复提交，发送前响应
			        pubFun.showloading();
			    },
			    success: function (data) {
			            pubFun.hideloading();
			            dataobj.gameid=data[2];
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
		$('#gopage').on('click',function(){
				dataobj.page=$('#pagenums').val();
				expage(dataobj);
			})
	}

	var ordercss=function(ele){
		if($(ele).hasClass('glyphicon-triangle-bottom')){
			$(ele).removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top');
			$(ele).attr('data-ordertype','DESC');
		}else{
			$(ele).removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');	
			$(ele).attr('data-ordertype','ASC');
		}
	}
	//排序方法
	var orderdata=function(){
		$('.ordersign').each(function(){
			$(this).on('click', function(event) {
				ordercss($(this).children('span'));
				dataobj.page=1;
				dataobj.ordertype=$(this).children('span').attr('data-ordertype');
				dataobj.orderfield=$(this).children('span').attr('data-orderfile');
				expage(dataobj);
			});
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
	initFun();
	ordercss();
	orderdata();
	defaultexpage();
	searchTime();
})($);
