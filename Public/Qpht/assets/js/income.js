var income=(function($){
	var incomedata={
		beginTime:null,
		endTime:null,
		gameid:null,
		everyday:null
	};

	var searchA=function(){
		$('#seachbtn').on('click',function(){
			incomedata.everyday=false;
			incomedata.gameid=$('#gametype').val();
			incomedata.beginTime=$('#begin_time').val();
			incomedata.endTime=$('#end_time').val();
			if(pubFun.timeCheck(incomedata.beginTime,incomedata.endTime)){
				expage(incomedata);
			}else{
				alert('请选择正确的时间区间');				
			};
		})	
	}

	var searchB=function(){
		$('#seachbtnevery').on('click',function(){
			incomedata.everyday=true;
			incomedata.gameid=$('#gametype').val();
			incomedata.beginTime=$('#begin_time').val();
			incomedata.endTime=$('#end_time').val();
			if(pubFun.timeCheck(incomedata.beginTime,incomedata.endTime)){
				expage(incomedata);
			}else{
				alert('请选择正确的时间区间');				
			};
		})	
	}

	var expage=function(conds){
		$.post("/qpht.php/gamemanage/seachIncomeData",conds,function(data,status){
			console.log(data);
	  		//changeTable(data,conds);
	  	});
	} 
	searchA();
	searchB();
})($);
