var gameindex=(function($){
	var dataobj=pubFun.dataobj;
	var initFun=function(){
		 // dataobj.bgtime=$("#timespan").attr('data-begin');
		 // dataobj.entime=$("#timespan").attr('data-end');
		 // dataobj.gameid=$('#gameid').val();
		 // dataobj.playertype=0;
		 // dataobj.ordertype=null;
		 // dataobj.orderfield=null;
		 // dataobj.module='1';//用户概况
		 //console.log(dataobj);
		 // $('#my-table ').each(function(index, el) {
		 // 	$(this).hide();
		 // });
		$('#my-table1').tab('show')
	}
	var gamechange = function(){
		// $('.nav-tabs li').each(function(index, el) {
		// 	$(this).on('click',function(){
		// 	dataobj.gameid=$(this).find('a').attr('data-gameid');
		// 	var idspan="my-table"+dataobj.gameid;
		// 	$('#home table').hide();
		// 	//alert('#'+idspan);
		// 	//$('#my-table1').show();
		// 	alert($('#my-table2').attr('class'));
		// 	//alert(dataobj.gameid);
		// 	//expage(dataobj);
		// })
		// });
	}
	var changeTable=function(data,dataobj){
		console.log(data);
		var html='';
		$.each(data, function(index, val) {
			temp="<tr class='odd gradeX'><td class='center'>"+val.countime+"</td>";
			temp+="<td class='center'>"+val.newplayer+"</td>";
			temp+="<td class='center'>"+val.oldplayer+"</td>";
			temp+="<td class='center'>"+val.activeplayer+"</td>";
			temp+="<td class='center'>"+val.allplay+"</td>";
			temp+="<td class='center'>"+val.aveplay+"</td>";
			html+=temp;
		});
		$('#table_main').html(html);
	  }
	// var expage=function(conds){
	// 	$.post("/qpht.php/index/gamechange",conds,function(data,status){
	//   		changeTable(data,conds);
	//   	});
	// }
	initFun();
	gamechange();
})($);
