<?php
namespace qpht\Controller;
use Think\Controller;
use Org\JwtAPI\JWT;
class IndexController extends BaseController {
	 public function _before_index(){
        //$this->accessControl();
    }
    public function index(){
        $this->getmenuInfo();
        $gamemain=D('Gamemain');
        $gamemain->updatadb();

        $gameinfo=D('Gameinfo');
        $gameinfos=$gameinfo->where('state=1')->select();
        // var_dump($gameinfos);
        // exit();
        $lres=array();
        foreach ($gameinfos as $key => $value) {
            $gameid=$value['id'];
            $countdata=$gamemain->where('gameid='.$gameid)->order('countime desc')->limit('0,7')->select();
            $lres[$gameid]=$countdata;
        }
        // var_dump($lres);
        // exit();
        $this->assign('lres',$lres);
        $this->show();
    }

    public function gamechange(){
        $data=I('post.');
        $gameid=$data['gameid'];
        $gamemain=D('Gamemain');
        $resdata=$gamemain->order('countime desc')->where('gameid='.$gameid)->limit('0,7')->select(); 
        $this->ajaxReturn($resdata);
    }

    public function menuAction(){
        $this->getmenuInfo();
        $this->show();
    }
    //localhost/qpht.php/index/menuAction
    public function menudata(){
        $dates1=session('s1');
        $dates2=session('s2');
        $dates3=session('s3');
        var_dump($dates1);
        var_dump($dates2);
        var_dump($dates3);
    }
    //菜单操作分发
    public function menuChangAction(){
        $postdata=I('post.');
        $actiontype=intval($postdata['actiontype']);
        if($actiontype==1){
            $resdata=$this->addmenuAction($postdata);
        }elseif($actiontype==2){
            $resdata=$this->editMenuAction($postdata);
        }else{

        }
        $this->ajaxReturn($resdata);
    }
    //localhost/qpht.php/index/addmenuAction
    public function addmenuAction($postdata){
        $data=null;
        $resdata=array();
        $module=D('Module');
        $menu=D('Menu');
        $actiontype=intval($postdata['actiontype']);
        $type=intval($postdata['m_type']);
        if($type==1){
            $data['modulename']=$postdata['m_name'];
            $data['sortnum']=$postdata['sortnum'];
            $data['describe']=$postdata['m_des'];
            $data['createtime']=time();
            if($module->create($data)){
                $module->add($data);
                $resdata=['state'=>1,'message'=>'创建数据成功'];
            }else{
                $resdata=['state'=>0,'message'=>'创建数据失败'];
            }
        }elseif($type==2) {
            $data['menuname']=$postdata['m_name'];
            $data['describe']=$postdata['m_des'];
            $data['sortnum']=$postdata['sortnum'];
            $data['menurl']=$postdata['m_url'];
            $data['moduleid']=intval($postdata['m_module']);
            $data['createtime']=time();
            if($menu->create($data)){
                $menu->add($data);
                $resdata=['state'=>1,'message'=>'创建数据成功'];
            }else{
                $resdata=['state'=>0,'message'=>'创建数据失败'];
            }
        }else{
            $resdata=['state'=>0,'message'=>'菜单类型错误'];
        }
        
        return $resdata;
    } 

    ////localhost/qpht.php/index/getonemenuInfo
    public function getonemenuInfo(){
        $menuid=I('post.menuid');
        $module=D('Module');
        $menu=D('Menu');
        $condition['id']=$menuid;
        $data=$menu->where($condition)->find();
        $this->ajaxReturn($data);
    }

    public function editMenuAction($postdata){
        $menu=D('Menu');
        $resdata=array();
        $menuid=$postdata['menuid'];
        $data['menuname']=$postdata['m_name'];
        $data['describe']=$postdata['m_des'];
        $data['sortnum']=$postdata['sortnum'];
        $data['menurl']=$postdata['m_url'];
        $data['moduleid']=intval($postdata['m_module']);
        $map['id']=$menuid;
        if($menu->where($map)->save($data)){
            $resdata=['state'=>1,'message'=>'更新数据成功'];
        }else{
            $resdata=['state'=>0,'message'=>'更新数据失败'];
        }
        return $resdata;
    }

    public function removemenu(){
        $idarr=I('post.ids');
        $menu=D('Menu');
        $resdata=null;
        $ids='';
        for ($i=0; $i <count($idarr) ; $i++) { 
            $ids.=$idarr[$i].',';
        }
        $cons=substr($ids, 0, -1);
        if($menu->delete($cons)){
                $role=D('Role');
                $data=$role->select();
                session('s1',$data);
                session('s2',$idarr);
                for ($i=0; $i < count($idarr); $i++) { 
                    for ($j=0; $j <count($data) ; $j++) { 
                        if(strpos($data[$j]['jurisdiction'],$idarr[$i]) !== false){
                            $temp=str_replace($idarr[$i],"",$data[$j]['jurisdiction']);
                            $data[$j]['jurisdiction']=substr($temp, 0, -1);
                        }
                    }
                }
                for ($x=0; $x < count($data) ; $x++) {
                    $map['id']=$data[$x]['id'];
                    $role->jurisdiction=$data[$x]['jurisdiction'];
                    $role->where($map)->save();
                }
                $resdata=['state'=>1,'message'=>'删除数据成功'];
            }else{
                $resdata=['state'=>0,'message'=>'删除数据失败'];
            }
        $this->ajaxReturn($resdata);
    }
}
 