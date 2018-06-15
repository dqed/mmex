var gameprodata=(function($){
	var dataobj=pubFun.dataobj;
	dataobj.mid=1;
	dataobj.isone=0;
	var myChart=null;
	var time=null;
	var pageobj={

	}
	var init=function(){
			times=pubFun.getWeekDay(-8);
			dataobj.bgtime=times[7];
			dataobj.entime=times[0];
			expage(dataobj);
			expagex(dataobj);

		}
	var expagex=function(conds){
		$.ajax({
			    type: "post",
			    data: conds,
			    url: "/qpht.php/gamemanage/seachdata51",
			    beforeSend: function () {
			        // 禁用按钮防止重复提交，发送前响应
			        pubFun.showloading();
			    },
			    success: function (data) {
			    		console.log(data);			    	
			            pubFun.hideloading();
			            newplaytable(data);			       
			    },
			    complete: function () {//完成响应
			         
			    },
			    error: function (data) {
			        console.info("error: " + data.responseText);
			    }
			});
	}
	var expage=function(conds){
		$.ajax({
			    type: "post",
			    data: conds,
			    url: "/qpht.php/gamemanage/seachdata5",
			    beforeSend: function () {
			        // 禁用按钮防止重复提交，发送前响应
			        pubFun.showloading();
			    },
			    success: function (data) {			    	
			            pubFun.hideloading();
			            if(data.mid==1){
			            	console.log(data);
			            	newplaychar(data.data);			       
			            }else if(data.mid==3){
			            	activeplayerchar(data.data);
			            }else if(data.mid==2){
			            	onlineplayerchar(data.data);
			            }
			    },
			    complete: function () {//完成响应
			         
			    },
			    error: function (data) {
			        console.info("error: " + data.responseText);
			    }
			});
	}
	var newplaychar=function(data){
		var times=new Array();
		var newplayer=new Array();
		var oldplayer=new Array();
		for (var i = data.length - 1; i >= 0; i--) {
			times.push(data[i]['countime']);
			newplayer.push(data[i]['newplayer']);
			oldplayer.push(data[i]['oldplayer']);
		}
		var ctx = document.getElementById("myChart").getContext('2d');
	    myChart = new Chart(ctx, {
		    type: 'line',
		    data: {
	            labels: times,
	            datasets: [
		            {
			            label: "新增",
			            fillColor: "rgba(220,220,220,0.2)",
			            strokeColor: "rgba(220,220,220,1)",
			            pointColor: "rgba(220,220,220,1)",
			            pointStrokeColor: "#fff",
			            pointHighlightFill: "#fff",
			            pointHighlightStroke: "rgba(220,220,220,1)",
			            data: newplayer
		            },
		            {
			            label: "留存",
			            fillColor: "rgba(151,187,205,0.2)",
			            strokeColor: "rgba(151,187,205,1)",
			            pointColor: "rgba(151,187,205,1)",
			            pointStrokeColor: "#fff",
			            pointHighlightFill: "#fff",
			            pointHighlightStroke: "rgba(151,187,205,1)",
			            data: oldplayer
		            }
	            ]
            }
		});
	}
	var newplaytable=function(data){
		var gamename=pubFun.getgamename(dataobj.gameid);
		var pageico=data.page;//当前页码
		var page=data.pages;//总页数
		var data=data.data;//返回数据
		var html='';
		//$('.panel-body h4').html(gamename);
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+((pageico-1)*pubFun.fynum+index+1)+"</td>";
			temp+="<td class='center'>"+$(this)[0].countime+"</td>";
			temp+="<td class='center'>"+$(this)[0].newplayer+"</td>";
			temp+="<td class='center'>"+$(this)[0].oldplayer+"</td>";
			temp+="<td class='center'>"+$(this)[0].activeplayer+"</td>";
			temp+="<td class='center'>"+$(this)[0].onlines+"</td>";
			html+=temp;
		});
		$('#table_main').html(html);
		$('#allpages').html(page);
		$("#pagenums").val(pageico);
		pubFun.pageaction();
	}
	var activeplayerchar=function(data){
		var times=new Array();
		var activeplayer=new Array();
		for (var i in data) {
			times.push(i);
			activeplayer.push(data[i]);
		}
		var newtimes=times.reverse();
		var newactive=activeplayer.reverse();
		var ctx = document.getElementById("myChart2").getContext('2d');
	    myChart = new Chart(ctx, {
		    type: 'line',
		    data: {
	            labels: newtimes,
	            datasets: [
		            {
			            label: "活跃",
			            fillColor: "rgba(220,220,220,0.2)",
			            strokeColor: "rgba(220,220,220,1)",
			            pointColor: "rgba(220,220,220,1)",
			            pointStrokeColor: "#fff",
			            pointHighlightFill: "#fff",
			            pointHighlightStroke: "rgba(220,220,220,1)",
			            data: newactive
		            },
	            ]
            }
		});
	}
	var onlineplayerchar=function(data){
		var times=new Array();
		var activeplayer=new Array();
		for (var i in data) {
			times.push(i);
			activeplayer.push(data[i]);
		}
		if(dataobj.isone==1){
			var newtimes=times;
			var newactive=activeplayer;
		}else{
			var newtimes=times.reverse();
			var newactive=activeplayer.reverse();
		}
		var ctx = document.getElementById("myChart3").getContext('2d');
	    myChart = new Chart(ctx, {
		    type: 'line',
		    data: {
	            labels: newtimes,
	            datasets: [
		            {
			            label: "在线",
			            fillColor: "rgba(220,220,220,0.2)",
			            strokeColor: "rgba(220,220,220,1)",
			            pointColor: "rgba(220,220,220,1)",
			            pointStrokeColor: "#fff",
			            pointHighlightFill: "#fff",
			            pointHighlightStroke: "rgba(220,220,220,1)",
			            data: newactive
		            },
	            ]
            },
		    options: {
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        }
		    }
		});
	}
	var updata=function(){
		$('#seachbtn').on('click', function(event) {
			dataobj.gameid=$('#gametype').val();
			dataobj.bgtime=$('#begin_time').val()||0;
			dataobj.entime=$('#end_time').val()||0;
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
				if(etime-btime==0){
					dataobj.isone=1;
				}else{
					dataobj.isone=0;
				}
				/*console.log(dataobj);
				return false;*/
				expage(dataobj);
				expagex(dataobj);
			}else{
				alert('选择正确的时间区间');
			}

			// myChart.data.datasets[0].data=[20, 100, 50, 60, 200, 300, 400];
			// myChart.update();
		});
	}
	var pageaction=function(){
		$('.pagediv').on('click','#goprevx',function(){
			dataobj.page=1;
			expagex(dataobj);
		})
		$('.pagediv').on('click','#goprev',function(){
			var nowpage=$('#pagenums').val();
			dataobj.page=(nowpage-1);
			expagex(dataobj);
		})
		$('.pagediv').on('click','#gonext',function(){
			var nowpage=$('#pagenums').val();
			dataobj.page=(parseInt(nowpage)+1);
			expagex(dataobj);
		})
		$('.pagediv').on('click','#gonextx',function(){
			dataobj.page=$('#allpages').html();
			expagex(dataobj);
		})
	}
	var defaultexpage=function(){
		$('#gopage').on('click',function(){
				dataobj.page=$('#pagenums').val();
				expagex(dataobj);
			})
	}
	var tabactive=function(){
		$('.nav-tabs li').each(function(index, el) {
			$(this).on('click',function(){
				$mid=$(this).attr('data-list');
				dataobj.mid=$mid;
				console.log(dataobj);
				expage(dataobj);
				expagex(dataobj);
			})		
		});
		// $('#tabactivex').on('click',function(){
		// 	$mid=$(this).attr('data-list');
		// 	dataobj.mid=$mid;
		// 	console.log(dataobj);
		// 	expage(dataobj);
		// });
		// $('#tabonlinex').on('click',function(){
		// 	$mid=$(this).attr('data-list');
		// 	dataobj.mid=$mid;
		// 	console.log(dataobj);
		// 	expage(dataobj);
		// });
		// $('#tabnewplayerx').on('click',function(){
		// 	$mid=$(this).attr('data-list');
		// 	dataobj.mid=$mid;
		// 	console.log(dataobj);
		// 	expage(dataobj);
		// })
	}
	init();
	updata();
	tabactive();
	pageaction();
	defaultexpage();
})($)