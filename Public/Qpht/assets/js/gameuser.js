var gameuser=(function($){
	var dataobj=pubFun.dataobj;
	var dataobj1=pubFun.dataobj1;
	var dataobjgold={
		data:null,
		url:'',
	}
	var initFun=function(){
		 dataobj.gameid=1;
		 dataobj.playertype=0;
		 dataobj.module='1';//用户概况
		 //console.log(dataobj) 
	}
	var initFun=function(){
		 dataobj.gameid=1;
		 dataobj.playertype=0;
		 dataobj.module='1';//用户概况
		 pubFun.pageaction();
	}
	var gamechange=function(){
		$('.nav-tabs li').each(function(index, el) {
			$(this).on('click',function(){
				dataobj.playid=null;
				dataobj.ordertype=null;
				dataobj.orderfield=null;
				dataobj.phone=null;
				dataobj.playertype=null;
				dataobj.nickname=null;
				dataobj.page=1;
				dataobj.gameid=$(this).find('a').attr('data-gameid');
				expage(dataobj);
			})
		});
	}
	var searchTime=function(){
		var myDate=new Date();
		$('#seachbtn').on('click',function(){
			dataobj.bgtime=$('#begin_time').val()||0;
			dataobj.entime=$('#end_time').val()||0;
			dataobj.nickname=$('#nickname').val();
			dataobj.page=1;
			dataobj.ordertype=null;
			dataobj.orderfield=null;
			if(dataobj.bgtime){
				var btime=new Date(dataobj.bgtime).getTime();
			}else{
				var btime=0;
			}
			if(dataobj.entime){
				var etime=new Date(dataobj.entime).getTime();
			}else{
				var etime=0;
			}
			if(etime-btime>=0){
				if(etime!=0||btime!=0){
				 	// var isdate = new Date(dataobj.entime.replace(/-/g,"/"));  //把日期字符串转换成日期格式
				  //   isdate = new Date((isdate/1000+(86400*1))*1000);  //日期加1天
				  //   var pdate = isdate.getFullYear()+"-"+(isdate.getMonth()+1)+"-"+(isdate.getDate())+` 23:59:59`; 
					dataobj.entime=dataobj.entime+` 23:59:59`;
				}
				var cod_playerid=$('#playerid').val();
				var cod_playertype=$('#usertype').val();
				dataobj.playid=cod_playerid;
				dataobj.playertype=cod_playertype;
				expage(dataobj);
			}else{
				alert('选择正确的时间区间');
			}
		})
	}
	var changeTable=function(data,dataobj){
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
			temp="<tr class='odd gradeX' data-userid='"+$(this)[0].userid+"'><td class='center'>"+((pageico-1)*pubFun.fynum+index+1)+"</td>";
			temp+="<td class='center'>"+$(this)[0].userid+"</td>";
			temp+="<td class='center'>"+$(this)[0].nickname+"</td>";
			temp+="<td class='center'>"+$(this)[0].channel+"</td>";
			temp+="<td class='center'>"+$(this)[0].platform+"</td>";
			temp+="<td class='center'>"+$(this)[0].sex+"</td>";
			temp+="<td class='center'>"+$(this)[0].create_time+"</td>";
			temp+="<td class='center'>"+$(this)[0].login_time+"</td>";
			temp+="<td class='center logold canclick'>"+$(this)[0].gold+"</td>";
			temp+="<td class='center logdiamond canclick'>"+$(this)[0].diamond+"</td>";
			temp+="<td class='center logluck'>"+$(this)[0].luck_tick+"</td>";
			temp+="<td class='center logconvert'>"+$(this)[0].convert_tick+"</td>";
			temp+="<td class='center'>"+"..."+"</td><td class='center' style='position: relative;'>"+$(this)[0].rall+"<i style='position: absolute; right: 5px;top:8px' class='xqbtn' data-userid="+$(this)[0].userid+">详情</i></td>";
			temp+="<td class='center'>"+$(this)[0].r1+"</td>";
			temp+="<td class='center'>"+$(this)[0].r2+"</td>";
			temp+="<td class='center'>"+$(this)[0].r3+"</td></tr>"; 
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
	 var changeTablex=function(data,dataobjgold){
		console.log(data);
		var pageico=data[3];//当前页码
		var page=data[0];//总页数
		var data=data[1];//返回数据
		// var stime=;//查询开始时间
		// var etime=//查询结束时间
		// console.log(data);
		// return false;
		//$('#timespan').attr('data-begin',dataobj.bgtime).attr('data-end',dataobj.entime);
		if(dataobjgold.data.goldtype==15){
			$('#myModalLabelx').html('金币明细');
		}else if(dataobjgold.data.goldtype==16){
			$('#myModalLabelx').html('钻石明细');
		}
		var html='';
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+((pageico-1)*pubFun.fynum+index+1)+"</td>";
			temp+="<td class='center'>"+$(this)[0].start_num+"</td>";
			temp+="<td class='center'>"+$(this)[0].end_num+"</td>";
			temp+="<td class='center'>"+$(this)[0].source+"</td>";
			temp+="<td class='center'>"+$(this)[0].change_time+"</td></tr>"; 
			html+=temp;
		});
		$('#table_mainx').html(html);
		var temppage='';
		for (var i = 0; i < page; i++) {
			var x= i+1
			temppage+="<option vaule='"+x+"'>"+x+"</option>";
		}
		//console.log(dataobj);
		$('#pagenumxx').html(temppage);
		//pageico==1,说明重新进行了一次查询，重新绑定翻页函数
		if(pageico==1){
			$('#pagenumxx').unbind();
			$('#pagenumxx').on('change',function(){
				dataobjgold.data.page=$(this).val();
				expagegold(dataobjgold);
			})	
		}
		$('#pagenumxx option').each(function(index, el) {
			if($(this).val()==pageico){
				$("#pagenumxx").val(pageico);
			}
		});	
	 }
	var expage=function(conds){
		$.ajax({
			    type: "post",
			    data: conds,
			    url: "/qpht.php/gamemanage/seachdata",
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
		// $.post("/qpht.php/gamemanage/seachdata",conds,function(data,status){
	 	//  		changeTable(data,conds); 
	 	//  	});
	}
	var expagegold=function(conds){
		$.ajax({
			    type: "post",
			    data: conds.data,
			    url: conds.url,
			    beforeSend: function () {
			        // 禁用按钮防止重复提交，发送前响应
			        //pubFun.showloading();
			    },
			    success: function (data) {
			    	changeTablex(data,conds);
			    	$('#goldModal').modal('show');
			            // pubFun.hideloading();
			            // changeTable(data,conds);
			            // pubFun.pageaction();
			    },
			    complete: function () {//完成响应
			         
			    },
			    error: function (data) {
			        console.info("error: " + data.responseText);
			    }
			});
	}
	var xqgo=function(){
		$('body').delegate('.xqbtn','click',function(){			
				//self.location='/qpht.php/gamemanage/playsinfo?userid='+$(this).attr('data-userid')+'&gameid='+dataobj.gameid; 
				$('#djModal').modal('show');
				dataobj1.playid=$(this).attr('data-userid');
				dataobj1.page=1;
				dataobj1.gameid=dataobj.gameid;
				console.log(dataobj);
				console.log(dataobj1);
				pubFun.expage1(dataobj1);
		})
	}
	//默认翻页方法
	var defaultexpage=function(){
		var cod_playerid=null;
		var cod_playertype=0;
		var gameid=$('#gameid').val();
		$('#pagenums').on('change',function(){
				dataobj.page=$(this).val();
				expage(dataobj);
			})
	}

	var ordercss=function(ele){
		if($(ele).hasClass('glyphicon-triangle-bottom')){
			$(ele).removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top');
			$('#timespan').val('DESC');
		}else{
			$(ele).removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');	
			$('#timespan').val('ASC');
		}
	}
	//排序方法
	var orderdata=function(){
		$('.ordersign').each(function(){
			$(this).on('click', function(event) {
				ordercss($(this).children('span'));
				dataobj.page=1;
				dataobj.ordertype=$('#timespan').val();
				dataobj.orderfield=$(this).attr('field');
				expage(dataobj);
			});
		})
	}
	//翻页方法
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
	var goldlist=function(){
		$('#table_main').on('click','.logold',function(){
			var useridx=$(this).parent().attr('data-userid');
			var tdata={
				userid:useridx,
				gameid:dataobj.gameid,
				goldtype:15
			}
			dataobjgold.data=tdata;
			dataobjgold.url=`/qpht.php/Gold/getGoldInfos`;
			expagegold(dataobjgold);
		});
		$('#table_main').on('click','.logdiamond',function(){
			var useridx=$(this).parent().attr('data-userid');
			var tdata={
				userid:useridx,
				gameid:dataobj.gameid,
				goldtype:15
			}
			dataobjgold.data=tdata;
			dataobjgold.url=`/qpht.php/Gold/getGoldInfos`;
			expagegold(dataobjgold);
		})
	}
	goldlist();
	initFun();
	ordercss();
	orderdata();
	defaultexpage();
	searchTime();
	gamechange();
	xqgo();
	pageaction();
})($);
