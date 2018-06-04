<?php
namespace qpht\Controller;
use Think\Controller;
class GoldController extends BaseController {
	public function getGoldInfos(){
        $data=I('post.');
        $conds['userid']=$data['userid'];
        $conds['goldtype']=$data['goldtype'];
        $conds['gameid']=$data['gameid'];
        $conds['page']=$data['page'];
        // $conds['userid']=24881;
        // $conds['goldtype']=15;
        // $conds['gameid']=1;
        $connection=$this->getcontion($conds['gameid'],true);
        $tableGameChange=new \qpht\Model\User_goods_changeModel('user_goods_change','table_',$connection);
        $rdata=$tableGameChange->getinfos($conds);
        $this->ajaxReturn($rdata);
    }
}
 