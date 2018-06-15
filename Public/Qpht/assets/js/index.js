var gameindex=(function($){
	var dataobj=pubFun.dataobj;
	var initFun=function(){
		$('#my-table1').tab('show');
		pubFun.pageaction();
	}
	var defaultexpage=function(){
		$('.gopage').on('click',function(){
			var p1=$('#pagenums').val();
			var p2=$('#allpages').html();
			if(p1>p2){
				alert('超出页码');
				return false;
			}else{
				dataobj.page=p1;
				console.log(dataobj);
				expage(dataobj);	
			}
		})
	}

	var expage=function(conds){
		$.ajax({
			    type: "post",
			    data: conds,
			    url: "/qpht.php/index/seachdata",
			    beforeSend: function () {
			        // 禁用按钮防止重复提交，发送前响应
			        pubFun.showloading();
			    },
			    success: function (data) {
			    	//console.log(data);
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
	var gamechange = function(){
		$('.nav-tabs li').each(function(index, el) {
			$(this).on('click',function(){
			pubFun.pageaction();
			$('#pagenums').val(1);
			dataobj.gameid=$(this).find('a').attr('data-gameid');
			})
		});
	}
	var changeTable=function(data,dataobj){
		console.log(data);
		var gameid=data[3];
		var page=data[0];
		var data=data[2]
		var html='';
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+val.countime+"</td>";
			temp+="<td class='center'>"+val.newplayer+"</td>";
			temp+="<td class='center'>"+val.oldplayer+"/"+val.oldx+"</td>";
			temp+="<td class='center'>"+val.activeplayer+"</td>";
			temp+="<td class='center'>"+val.allplay+"</td>";
			temp+="<td class='center'>"+val.aveplay+"</td>";
			html+=temp;
		});
		$('#my-table'+gameid).find('.table_main').html(html);
		$('#pagenums').val(page);
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
	defaultexpage();
	initFun();
	gamechange();
	pageaction();
})($);
