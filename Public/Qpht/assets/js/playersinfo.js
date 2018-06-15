var gameuser=(function($){
	var dataobj=pubFun.dataobj;
	var initFun=function(){
		 // dataobj.bgtime=$("#timespan").attr('data-begin');
		 // dataobj.entime=$("#timespan").attr('data-end');
		 dataobj.module=5;
		 dataobj.gameid=$('#isgo').attr('data-gameid');
		 dataobj.playid=$('#isgo').attr('data-userid');
		 pubFun.pageaction();
	}
	var searchTime=function(){
		var myDate=new Date();
		$('#seachbtn').on('click',function(){
			dataobj.bgtime=$('#begin_time').val()||0;
			dataobj.entime=$('#end_time').val()||0;
			dataobj.gameid=$('#gametype').val()||0;
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
					dataobj.entime=dataobj.entime+` 23:59:59`;
				}
				var cod_playerid=$('#playerid').val();
				var cod_playertype=$('#usertype').val();
				dataobj.playid=cod_playerid;
				dataobj.playertype=cod_playertype;
				console.log(dataobj);
				expage(dataobj);
			}else{
				alert('选择正确的时间区间');
			}
		})
	}
	var changeTable=function(data,dataobj){
		var gamename=pubFun.getgamename(dataobj.gameid);
		var pageico=data[3];//当前页码
		var page=data[0];//总页数
		var data=data[1];//返回数据
		var html='';
		$('.panel-body h4').html(gamename);
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+((pageico-1)*pubFun.fynum+index+1)+"</td>";
			temp+="<td class='center'>"+$(this)[0].user_id+"</td>";
			temp+="<td class='center'>"+$(this)[0].nickname+"</td>";
			temp+="<td class='center'>"+$(this)[0].room_id+"</td>";
			temp+="<td class='center'>"+$(this)[0].in_gold+"</td>";
			temp+="<td class='center'>"+$(this)[0].change_gold+"</td>";
			temp+="<td class='center'>"+$(this)[0].last_gold+"</td>";
			temp+="<td class='center'>配置</td>";
			temp+="<td class='center'>"+$(this)[0].change_time+"</td>";
			temp+="<td class='center'>...</td>"; 
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
			    url: "/qpht.php/gamemanage/seachdata2",
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
		$('#gopage').on('click',function(){
				dataobj.page=$('#pagenums').val();
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
	initFun();
	defaultexpage();
	searchTime();
})($);
