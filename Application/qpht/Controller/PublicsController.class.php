<?php
namespace qpht\Controller;
use Think\Controller;
class PublicsController extends Controller{
	//localhost/qpht.php/Publics/loginShow
	public function loginShow(){
		$this->show();
	}

	public function loginAction(){
		 $user=I('post.user');
		 $pwd=I('post.pwd');
		 $account=D('Account');
		 $map['accountname']=$user;
		 $res=$account->where($map)->find();
		 if($res){
		 	if($res['password']==think_ucenter_md5($pwd)){
		 		$stime=time();
		 		$dataarr=array('user'=>$res['accountname'],'roleids'=>$res['roleids'],'stime'=>$stime);
		 		$userc=new UserController();
		 		$token=$userc->jwtencode($dataarr);
		 		cookie('token',$token);
		 		$this->success('登录成功', U('/qp/Index/index'));
		 	}else{
		 		$this->error('密码错误');
		 	}
		 }else{
		 	$this->error('没有此用户');
		 }
		 // $data['verify_code']=I('post.verify_code');
		 // if (check_verify($data['verify_code'])) {
		 // 	echo "nice ";
		 //     //$this->error("验证码不正确", U("loginShow"), 3);
		 // }else{
		 // 	echo "erro";
		 // 	//$this->success("验证通过",U("loginShow"),3);
		 // }
	}

	public function systemout(){
		cookie('token',null);
		$resdata=['state'=>1,'message'=>'退出系统'];
		$this->ajaxReturn($resdata);
	}
	Public function verify(){
        $verify = new \Think\Verify();
        $verify->codeSet = '0123456789';
        $verify->entry();
    }
}