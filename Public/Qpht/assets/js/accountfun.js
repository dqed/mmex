var accfun=(function($,pubFun){
	var accpub=function(){
		$('.isdev').on('click',function(){
			if($(this).val()==1){
				$('#partners').show();
			}else{
				$('#partners').hide();
			}
		})
	}
	var accountAdd=function(){
		$('#accountbtn').on('click',function(argument) {
		  var formid=$(this).attr('formid');
		  $('#roleidss').val($(('#'+formid)+' input[type=checkbox]:checked').map(function(){return this.value}).get().join(','));
		  var dataobj=$('#'+formid).serialize();
		  $.post("/qpht.php/user/accountAction",dataobj,function(data,status){
		  		pubFun.messagewarn(data.state);
		  });
		  $('#'+formid).modal('hide');
		})
	}
	var accountEdit=function(accountid){
		$.post('/qpht.php/user/accountGetInfo', {accountid: accountid}, function(data, textStatus, xhr) {
  		console.log(JSON.stringify(data));
  		 $('#myModalLabel').text('编辑账户');
  		 $('#accountid').val(data.id);
  		 $('#a_name').val(data.accountname);
  		 $('#a_pwd').attr('placeholder', '如不更改则不需填写');
  		 var roles=data.roleids.split(',');
  		 $('.roles').each(function(index, el) {
  		 	if($.inArray($(this).val(),roles)>=0){
  		 		$(this).prop('checked','checked');
  		 	};
  		 });
  		 if(data.isdev==1){
  		 	$('#isdev1').prop('checked','checked');
  		 	$('#a_partnersid').val(data.partnersid);
  		 	$('#partners').show();
  		 }else{
  		 	$('#isdev2').prop('checked','checked');
  		 };
  		 $('#a_des').val(data.remarks);
  		 $("#actiontype").val(2);
  		 $('#myModal').modal('show');
  		 });
	}
	accpub();
	accountAdd();

	return {
		accountEdit:accountEdit
	}
})($,window.pubFun)