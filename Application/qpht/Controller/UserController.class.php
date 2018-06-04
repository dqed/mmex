<?php
namespace qpht\Controller;
use Think\Controller;
use Org\JwtAPI\JWT;
class UserController extends BaseController {
    /*http://localhost/qpht.php/user/roleList*/
    public function roleList(){
    	$role=D('Role');
    	$count = $role->count();
    	$page = new \Think\Page($count,C('PAGE_NUM'));
    	$page->setConfig('prev',"上一页");
    	$page->setConfig('next',"下一页");
    	$show = $page->show();
    	$rolelist = $role->order('id')->limit($page->firstRow.','.$page->listRows)->select();
		$menu=D('Menu');
		$menudata=$menu->order('moduleid')->select();
		$menuxdata=array();
		for ($i=0; $i <count($menudata) ; $i++) { 
			$moduleid=$menudata[$i]['moduleid'];
			$menuxdata[$moduleid]=array();
		}
		for ($i=0; $i <count($menudata) ; $i++) { 
			$moduleid=$menudata[$i]['moduleid'];
			array_push($menuxdata[$moduleid], $menudata[$i]);
		}
		for ($i=0; $i <count($rolelist) ; $i++) { 
			$jur=explode(",",$rolelist[$i]['jurisdiction']);
			$newjur="";
			for ($j=0; $j <count($jur) ; $j++) { 
				for ($x=0; $x <count($menudata) ; $x++) { 
					if($menudata[$x]['id']==$jur[$j]){
						$newjur.=($menudata[$x]['menuname'].'|');
					}
				}
			}			
			$rolelist[$i]['jurisdiction']=$newjur;
		}
		$this->assign('rolelist',$rolelist);
		$this->assign('page',$show);
		$this->getmenuInfo();
		$this->assign('menuxdata',$menuxdata);
		$this->display(); 
    }

    public function roleAction(){
    	$datas=I('post.');
    	$resdata=null;
    	$actiontype=intval($datas['actiontype']);
    	if($actiontype==1){
    		$resdata=$this->roleadd($datas);
    	}elseif($actiontype==2){
    		$resdata=$this->roleedit($datas);
    	}
    	$this->ajaxReturn($resdata);
    }
    public function roleadd($datas){
    	$role=D('Role');
    	$data['solename']=$datas['r_name'];
    	$data['roledescribe']=$datas['r_des'];
    	$data['createtime']=time();
    	$data['updatetime']=time();
    	$data['jurisdiction']=$datas['menuidss'];
    	if($role->create($data)){
    		$role->add($data);
    		$resdata=['state'=>1,'message'=>'创建数据成功'];
    	}else{
    		$resdata=['state'=>0,'message'=>'创建数据失败'];
    	}
    	return $resdata;
    	//session('datass',$datas);

    }
    public function roleedit($postdata){
    	$role=D('Role');
        $resdata=array();
        $roleid=$postdata['roleid'];
        $data['solename']=$postdata['r_name'];
        $data['roledescribe']=$postdata['r_des'];
        $data['jurisdiction']=$postdata['menuidss'];
        $data['updatetime']=time();
        $map['id']=$roleid;
        if($role->where($map)->save($data)){
            $resdata=['state'=>1,'message'=>'更新数据成功'];
        }else{
            $resdata=['state'=>0,'message'=>'更新数据失败'];
        }
        return $resdata;
    }
    public function rolerem(){
    	$idarr=I('post.ids');
    	$role=D('Role');
    	$resdata=null;
    	$ids='';
    	for ($i=0; $i <count($idarr) ; $i++) { 
    		$ids.=$idarr[$i].',';
    	}
    	$cons=substr($ids, 0, -1);
    	if($role->delete($cons)){
    		 	$resdata=['state'=>1,'message'=>'删除数据成功'];
    		}else{
    			 $resdata=['state'=>0,'message'=>'删除数据失败'];
    		}
    	$this->ajaxReturn($resdata);
    }
    public function getonerole(){
    	$id=I('post.roleid');
    	$role=D('Role');
    	$map['id']=$id;
    	$data=$role->where($map)->find();
    	$this->ajaxReturn($data);
    }

    /*账户模块操作*/

    public function accountAction(){
        $datas=I('post.');
        $resdata=null;
        $actiontype=intval($datas['actiontype']);
        if($actiontype==1){
            $resdata=$this->accountAdd($datas);
        }elseif($actiontype==2){
            $resdata=$this->accountedit($datas);
        }
        $this->ajaxReturn($resdata);
    }
    public function accountList(){
        $role=D('Role'); 
        $roleinfo=$role->order('id')->getField('id, solename');
        $account=D('Account');
        $count = $account->count();
        $page = new \Think\Page($count,C('PAGE_NUM'));
        $page->setConfig('prev',"上一页");
        $page->setConfig('next',"下一页");
        $show = $page->show();
        $accountlist = $account->order('id')->limit($page->firstRow.','.$page->listRows)->select();
        $roledata=$role->getField('id,solename');
        for ($i=0; $i < count($accountlist); $i++) { 
            $rolestr='';
            $rolearr=explode(",",$accountlist[$i]['roleids']);
            for ($j=0; $j < count($rolearr) ; $j++) { 
               $rolestr.=$roledata[$rolearr[$j]].'|';
            }
            $accountlist[$i]['roleids']=$rolestr;
        }
        $this->getmenuInfo();
        // var_dump($accountlist);
        // exit();
        $this->assign('roleinfo',$roledata);
        $this->assign('accountlist',$accountlist);
        $this->display();
    }

    public function accountAdd($postdata){
        $resdata=null;
        $account=D('Account');
        $data['accountname']=$postdata['a_name'];
        $data['accountsecond']=$postdata['a_name'];
        $data['state']=$postdata['a_state'];
        $data['isdev']=$postdata['is_dev'];
        $data['roleids']=$postdata['roleidss'];
        $data['password']=think_ucenter_md5($postdata['a_pwd']);
        if($postdata['is_dev']==1){
           $data['partnersid']=$postdata['a_partnersid']; 
        }
        $data['remarks']=$postdata['a_des'];
        $data['uptime']=time();
        if($account->create($data)){
            $account->add($data);
            $resdata=['state'=>1,'message'=>'创建数据成功'];
        }else{
            $resdata=['state'=>0,'message'=>'创建数据失败'];
        }
        return $resdata;
    }
    public function accountRemove(){
        $idarr=I('post.ids');
        $account=D('Account');
        $resdata=null;
        $ids='';
        for ($i=0; $i <count($idarr) ; $i++) { 
            $ids.=$idarr[$i].',';
        }
        $cons=substr($ids, 0, -1);
        if($account->delete($cons)){
                $resdata=['state'=>1,'message'=>'删除数据成功'];
            }else{
                 $resdata=['state'=>0,'message'=>'删除数据失败'];
            }
        $this->ajaxReturn($resdata);
    }
    public function accountedit($postdata){
        $account=D('Account');
        $resdata=null;
        $accountid=$postdata['accountid'];
        $data['accountname']=$postdata['a_name'];
        $data['accountsecond']=$postdata['a_name'];
        $data['state']=$postdata['a_state'];
        $data['isdev']=$postdata['is_dev'];
        $data['roleids']=$postdata['roleidss'];
        if($postdata['a_pwd']!=''){
           $data['password']=think_ucenter_md5($postdata['a_pwd']); 
        }
        if($postdata['is_dev']==1){
           $data['partnersid']=$postdata['a_partnersid']; 
        }else{
           $data['partnersid']='';  
        }
        $data['remarks']=$postdata['a_des'];
        $map['id']=$accountid;
        if($account->where($map)->save($data)){
            $resdata=['state'=>1,'message'=>'更新数据成功'];
        }else{
            $resdata=['state'=>0,'message'=>'更新数据失败'];
        }
        return $resdata;
    }
    public function accountGetInfo(){
        $id=I('post.accountid');
        $account=D('Account');
        $map['id']=$id;
        $data=$account->where($map)->find();
        $this->ajaxReturn($data);
    }
    public function showdata(){
    	$data=session('accdata');
    	var_dump($data);
    }
    public function jwtencode($data=array()){
    	$key = C('JWTKEY');
		$token = $data;
		$jwt = JWT::encode($token, $key);
		return $jwt;
    }

    public function jwtdecode($str=""){
    	$result=false;
    	$key = C('JWTKEY');
		if($str == ''){
			return $result;
		}
		$decoded = JWT::decode($str, $key, array('HS256'));
		$arr = json_decode(json_encode($decoded), true);
		if(!is_object($decoded)){
			return $result;
		}else{
			$arr = json_decode(json_encode($decoded), true);
			return $arr;
		}
    } 
}