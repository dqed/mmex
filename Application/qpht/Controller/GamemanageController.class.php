<?php
namespace qpht\Controller;
use Think\Controller;
class GamemanageController extends BaseController {
    public function getcontion($id,$ex=false){
    	$dbmod=D('Gameinfo');
    	$dbdata=$dbmod->where('id='.$id)->select();
    	if($ex){
    		$dbname=$dbdata[0]['extable'];
    	}else{
    		$dbname=$dbdata[0]['dbname'];
    	}
    	$connection = array(
			    'db_type'    =>   'mysql',
			    'db_host'    =>   $dbdata[0]['dburl'],
			    'db_user'    =>   $dbdata[0]['dbuser'],
			    'db_pwd'     =>   $dbdata[0]['dbpwd'],
			    'db_port'    =>    3306,
			    'db_name'    =>    $dbname, 
			    'db_charset' =>    'utf8',
			);
    	return $connection;
    }
    public function gameManList(){
    	$this->getmenuInfo();
    	$dbmod=D('Gameinfo');
    	$cond['gameid']=1;
        $playlog=$dbmod->field('play_log')->where('id='.$cond['gameid'])->select();
        $cond['playlog']=$playlog[0]['play_log'];
    	$connection=$this->getcontion($cond['gameid']);
    	$accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connection);
    	$day=date("Y-m-d",time());
    	$pagecon=1;
    	// $cond['bgtime']='2018-05-01';
    	// $cond['entime']='2018-05-18';
    	$data=$accountxlmj->getOneDay($pagecon,$cond);
    	$page=$data[0];
    	$datalist=$data[1];
    	$gameid=$data[2];
        $infosnum=$data[4];
    	$this->assign('yday',$yday);
    	$this->assign('tday',$tday);
    	$this->assign('datalist',$datalist);
    	$this->assign('pages',$page);
        $this->assign('gameid',$gameid);
    	$this->assign('infosnum',$infosnum);
    	$this->display();
    }

    public function seachdata($conds=null){
    	if(!$conds){
    		$data=I('post.');
	    	$cond['page']=$data['page'];
	    	$cond['bgtime']=$data['bgtime'];
	    	$cond['entime']=$data['entime'];
	    	$cond['playid']=$data['playid'];
	    	$cond['nickname']=$data['nickname'];
	    	$cond['playertype']=$data['playertype'];
	    	$cond['ordertype']=$data['ordertype'];
	    	$cond['orderfile']=C(ORDER_LIST)[$data['orderfield']];
	    	$cond['gameid']=$data['gameid'];//根据游戏ID来选择初始化的模型
    	}else{
    		$cond['page']=$conds['page'];
	    	$cond['bgtime']=$conds['bgtime'];
	    	$cond['entime']=$conds['entime'];
	    	$cond['playid']=$conds['playid'];
	    	$cond['nickname']=$data['nickname'];
	    	$cond['playertype']=$conds['playertype'];
	    	$cond['ordertype']=$conds['ordertype'];
	    	$cond['orderfile']=C(ORDER_LIST)[$conds['orderfield']];
	    	$cond['gameid']=$conds['gameid'];//根据游戏ID来选择初始化的模型
    	}
    	$dbmod=D('Gameinfo');
        $playlog=$dbmod->field('play_log')->where('id='.$cond['gameid'])->select();
        $cond['playlog']=$playlog[0]['play_log'];
    	$connection=$this->getcontion($cond['gameid']);
    	$accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connection);
		$resdata=$accountxlmj->getOneDay($cond['page'],$cond);
		if($conds){
			return $resdata;
		}else{
			$this->ajaxReturn($resdata);
		}
    }

    public function playsInfo(){
    	$this->getmenuInfo();
    	$userid=I('get.userid');
    	$gameids=I('get.gameid');
    	if($userid||$gameids){
    		$this->assign('userid',$userid);
    		$this->assign('gameids',$gameids);
    	}
    	if(!$gameids){
            $cond['gameid']=1;
    	}
    	if($userid){
    		$cond['playid']=$userid;
    	}
    	$connection=$this->getcontion($cond['gameid']);
    	$tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
    	$cond['page']=1;
    	$data=$tableGameChange->playsInfo($cond);
    	$pages=$data[0];
    	$resdata=$data[1];
    	$gameid=$data[2];
    	$page=$data[3];
    	$gameinfo=D('Gameinfo');
        $gamename=$gameinfo->getGameName($gameid);
    	$this->assign('pages',$pages);
    	$this->assign('gamename',$gamename);
    	$this->assign('resdata',$resdata);
    	$this->assign('gameid',$gameid);
    	$this->assign('page',$page);
    	$this->assign('yday',$yday);
    	$this->assign('tday',$tday);
    	$this->display();
    }

    public function seachdata2(){
    	$data=I('post.');
    	$cond['page']=$data['page'];
    	$cond['bgtime']=$data['bgtime'];
    	$cond['entime']=$data['entime'];
    	$cond['gameid']=$data['gameid'];
    	$cond['playid']=$data['playid'];
    	$connection = $connection=$this->getcontion($cond['gameid']);
    	$tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
    	$resdata=$tableGameChange->playsInfo($cond);
    	$this->ajaxReturn($resdata);   	
    }
    public function getexcelplus(){
        $dbmod=D('Gameinfo');
        $data=I('get.');
        $cond['bgtime']=$data['bgtime'];
        $cond['entime']=$data['entime'];
        $cond['playid']=$data['playid'];
        $cond['nickname']=$data['nickname'];
        $cond['playertype']=$data['playertype'];
        $cond['module']=$data['module'];
        $cond['gameid']=$data['gameid'];
        if($cond['module']=='1'){
            $connection=$this->getcontion($cond['gameid']);
            $accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connection);
            $accountxlmj->exceldata($cond); 
        }else if($cond['module']=='5'){
            $connection=$this->getcontion($cond['gameid']);
            $tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
            $tableGameChange->exceldata($cond);
        }else if($cond['module']=='2'){
            $connection=$this->getcontion($cond['gameid']);
            $cond['gamename']=$dbmod->getGameName($cond['gameid']);
            $convertGood=new \qpht\Model\Convert_goodModel('Convert_good','tbl_',$connection);
            $convertGood->exceldata($cond);
        }else if($cond['module']=='3'){
            $connection=$this->getcontion($cond['gameid'],true);
            $cond['gamename']=$dbmod->getGameName($cond['gameid']);
            $rechargedb=new \qpht\Model\Pay_infoModel('pay_info','tbl_',$connection);
            $connectionx=$this->getcontion($cond['gameid'],false);
            $goodsinfo=new \qpht\Model\GoodsModel('goods','table_',$connectionx);
            $accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connectionx);
            if($cond['gameid']==1){
                $goodspic=$goodsinfo->getGoodsPrice(2);
            }elseif($cond['gameid']==2){
                $goodspic=$goodsinfo->getGoodsPrice(1);
            }
            $rechargedb->exceldata($cond,$goodspic,$accountxlmj);
        }
    }

    //兑换相关
    public function convertGoods(){
    	$this->getmenuInfo();
    	$cond['gameid']=1;
    	$cond['page']=1;
    	$cond['user_id']=null;
    	$cond['phone']=null;
    	$connection = $connection=$this->getcontion($cond['gameid']);
    	$tableGameChange=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
    	//$convertGoods=new  \qpht\Model\Convert_goodModel();
    	$data=$tableGameChange->convertGoods($cond);
    	$pages=$data[0];
    	$resdata=$data[1];
    	$gameid=$data[2];
    	$page=$data[3];
    	$this->assign('pages',$pages);
    	$this->assign('resdata',$resdata);
    	$this->assign('gameid',$gameid);
    	$this->assign('page',$page);
    	$this->display();
    }
    //兑换查询
    public function seachdata3($conds=null){
    	$data=I('post.');
    	$cond['page']=$data['page'];
    	$cond['gameid']=$data['gameid'];
    	$cond['user_id']=$data['playid'];
    	$cond['phone']=$data['phone'];
    	$cond['orderfile']=C(ORDER_LIST)[$data['orderfield']];
    	$cond['ordertype']=$data['ordertype'];
    	$connection = $connection=$this->getcontion($cond['gameid']);
    	$convertGoods=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
		$resdata=$convertGoods->convertGoods($cond);
		if($conds){
			return $resdata;
		}else{
    		$this->ajaxReturn($resdata);
		}
    	
    }

    //充值模块
    public function recharge(){
        $this->getmenuInfo();
        $cond['page']=1;
        $cond['gameid']=1;
        $connection=$this->getcontion($cond['gameid'],true);
        $connectionx=$this->getcontion($cond['gameid'],false);
        $accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connectionx);
        $goodsinfo=new \qpht\Model\GoodsModel('goods','table_',$connectionx);
        if($cond['gameid']==1){
            $goodspic=$goodsinfo->getGoodsPrice(2);
        }elseif($cond['gameid']==2){
            $goodspic=$goodsinfo->getGoodsPrice(1);
        }
        $rechargedb=new \qpht\Model\Pay_infoModel('pay_info','tbl_',$connection);
        $data=$rechargedb->payInfoCount($cond,$goodspic,$accountxlmj);
        $pages=$data[0];
        $resdata=$data[1];
        $gameid=$data[2];
        $page=$data[3];
        $this->assign('pages',$pages);
        $this->assign('resdata',$resdata);
        $this->assign('gameid',$gameid);
        $this->assign('page',$page);
        $this->display();
    }

    //充值查询
   public function seachdata4($conds=null){
        $data=I('param.');
        $cond['page']=$data['page'];
        $cond['gameid']=$data['gameid'];
        $connectionx=$this->getcontion($cond['gameid'],false);
        $accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connectionx);
        $goodsinfo=new \qpht\Model\GoodsModel('goods','table_',$connectionx);
        if($cond['gameid']==1){
            $goodspic=$goodsinfo->getGoodsPrice(2);
        }elseif($cond['gameid']==2){
            $goodspic=$goodsinfo->getGoodsPrice(1);
        }
        $connection=$this->getcontion($cond['gameid'],true);
        $rechargedb=new \qpht\Model\Pay_infoModel('pay_info','tbl_',$connection);   
        $resdata=$rechargedb->payInfoCount($cond,$goodspic,$accountxlmj);
        if($conds){
            return $resdata;
        }else{
            $this->ajaxReturn($resdata);
        }
        
    }

    //收入数据
    public function income(){
    	$this->getmenuInfo();
    	$this->display();
    }

    //收入数据查询
    public function seachIncomeData($conds=null){
    	// $data=I('post.');
    	// $cond['begTime']=$data['beginTime'];
    	// $cond['endTime']=$data['endTime'];
    	// $cond['gameid']=$data['gameid'];
    	// $cond['everyday']=$data['everyday'];
    	$dbmod=D('Gameinfo');
    	$gameids=$dbmod->where('state=1')->select();
    	$resdata=array();
    	if($cond['gameid']==0){
    		for ($i=0; $i <count($gameids) ; $i++) { 
    			$connection = $connection=$this->getcontion($gameids[$i]['gameid']);
    			//$tableGameChange=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
    			//$tableGameChange->x();
    		}
    	}else{
    		$connection = $connection=$this->getcontion($cond['gameid']);
    		//$tableGameChange=new \qpht\Model\Convert_goodModel('convert_good','tbl_',$connection);
    		//$tableGameChange->x();
    	}
    	$this->ajaxReturn($cond);
    }

    //项目数据
    public function gameProData($conds=null,$isreturn=null){
    	$this->getmenuInfo();
        if(!$conds['gameid']){
            $conds['gameid']=1;
        }
        if(!$conds['beftime']){
            // $beftime=date_sub(date_create($conds['endtime']),date_interval_create_from_date_string("7 days"));
            $conds['beftime']="2018-05-01";
        }
        if(!$conds['endtime']){
            $conds['endtime']=date("Y-m-d");
        }
        if($isreturn){
        	$connection=$this->getcontion($conds['gameid']);
        	$rechargedb=new \qpht\Model\PlayerinfoModel('playerinfo','tbl_',$connection);
        	$data1=$rechargedb->getNewAccount($conds);//新增
        	$tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
        	$data2=$tableGameChange->getOldOlayer($data1);//次留
        	$data3=$tableGameChange->getPlayerNum($data2);//活跃数
        	$data4=$tableGameChange->getplaysInfo($data3);//获取对局数
            return $data4;            
        }
    	//exit();
    	$this->display();
    }
    //项目数据，获取新增与次留
    public function seachdata5(){
         $data=I('post.');
         $conds['gameid']=$data['gameid'];
         $conds['beftime']=$data['bgtime'];
         $conds['endtime']=$data['entime'];
         $conds['isone']=$data['isone'];
         $mtype=$data['mid'];

         // $conds['gameid']=1;
         // $conds['beftime']='2018-06-08';
         // $conds['endtime']='2018-06-15';
         // $conds['isone']=0;
         // $mtype=1;

         $connection=$this->getcontion($conds['gameid']);
         $tableGameChange=new \qpht\Model\Game_change_goldModel('game_change_gold','table_',$connection);
         $gamemain=D('Gamemain');
         if($mtype==1){
             $data2['data']=$gamemain->getGameInfos($conds);
             $data2['mid']=$mtype;
         }elseif ($mtype==2) {
             $online=D('Online');
             $data2['data']=$online->getOnlineData($conds);
             $data2['mid']=$mtype;
         }elseif ($mtype==3 ) {
            $data2['data']=array();
             $tempdata=$gamemain->getGameInfos($conds);
             foreach ($tempdata as $key => $value) {
                 $data2['data'][$value['countime']]=$value['activeplayer'];
             }
             $data2['mid']=$mtype;
         }
         $this->ajaxReturn($data2);
    }

    //项目数据 获取明细表数据
    public function seachdata51(){
         $data=I('post.');
         $conds['gameid']=$data['gameid'];
         $conds['beftime']=$data['bgtime'];
         $conds['endtime']=$data['entime'];
         $conds['page']=$data['page'];
         $conds['isone']=0;
         // $conds['gameid']=1;
         // $conds['beftime']='2018-05-21';
         // $conds['endtime']='2018-06-14';
         // $conds['page']=1;     
             $days=diffBetweenTwoDays($conds['beftime'],$conds['endtime']);
             if($days>C(PAGE_NUM)){
                $count=ceil($days/C(PAGE_NUM));
                if($conds['page']==$count){
                    $bpage=ceil($days-1);
                }else{
                    $bpage=($conds['page'])*C(PAGE_NUM)-1;
                }
                $ppage=($conds['page']-1)*C(PAGE_NUM);
                $d1=date("Y-m-d",strtotime("+ ".$bpage." day",strtotime($conds['beftime'])));
                $d2=date("Y-m-d",strtotime("+ ".$ppage." day",strtotime($conds['beftime'])));
                $conds['beftime']=$d2;
                $conds['endtime']=$d1;
                // var_dump($conds);
                // exit();
             }else{
                $count=1;
                $conds['page']=1;
             }
             $gamemain=D('Gamemain');
             $data=$gamemain->getGameInfos($conds);   
             //$data=$this->gameProData($conds,true);
             $online=D('Online');
             $data3=$online->getOnlineData($conds);
             foreach ($data as $keys => $values) {
                 $ti=$values['countime'];
                 if($data3[$ti]){
                    $data[$keys]['onlines']=$data3[$ti];
                 }else{
                    $data[$keys]['onlines']=0;
                 }
             }
             foreach($data as $val){
                $key_arrays[]=$val['countime'];
             }
             array_multisort($key_arrays,SORT_ASC,$data);
             $data2['data']=$data;
             $data2['page']=$conds['page'];
             $data2['pages']=$count;
             $this->ajaxReturn($data2);
    }
}