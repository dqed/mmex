<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ed <xie5296808@163.com>
// +----------------------------------------------------------------------

namespace qpht\Model;
use Think\Model;

/**
 * 菜单模块模型
 * @author ed <xie5296808@163.com>
 */

class GamemainModel extends Model {
	protected $connection = 'DB_CONFIG1';
	protected $tablePrefix = 'qp_';
	public function updatadb(){
		//更新首页统计数据；qp_gamemain
		$gameinfo=D('Gameinfo');
	    $gameinfos=$gameinfo->where('state=1')->select();
	    foreach ($gameinfos as $key => $value) {
	    	$countime=$this->where('gameid='.$value['id'])->field('countime')->order('countime desc')->limit(1)->select();
	    	if($countime){
		        $lres=array();
	        	$conds['beftime']=date("Y-m-d",strtotime($countime[0]['countime']));
	        	$conds['endtime']=date('Y-m-d', time());
	        	$dtday=diffBetweenTwoDays($conds['beftime'],$conds['endtime']);
	        	if($dtday>1){
		            $conds['gameid']=$value['id'];
		            $conds['gamename']=$value['gamename'];
		            $oldplayers=A('qpht/Gamemanage')->gameProData($conds,true);
		            array_pop ($oldplayers);
		            $lres[$value['id']]=$oldplayers;
	        	}
			}else{
		        $lres=array();
		        $conds['beftime']=null;
	        	$conds['endtime']=null;
	            $conds['gameid']=$value['id'];
	            $conds['gamename']=$value['gamename'];
	            $oldplayers=A('qpht/Gamemanage')->gameProData($conds,true);
	            $lres[$value['id']]=$oldplayers;
			}
			$dataList=array();
			if(count($lres)>0){
		        foreach ($lres as $keys => $values) {
		        	foreach ($values as $key1 => $value1) {
		        		$dataList[]=array('countime'=>$value1['time'],'newplayer'=>$value1['newplayer'],'oldplayer'=>$value1['oldplayer'],'activeplayer'=>$value1['hyplayer'],'allplay'=>$value1['allplays'],'aveplay'=>$value1['avplays'],'gameid'=>$keys); 
		        	}
		        }
		 		$this->addAll($dataList);
			}
	    }
	}

	protected function dataupdata($connection,$gameid){
		$accountxlmj=new \qpht\Model\AccountxlmjModel('account','tbl_',$connection);
		$dbupdata= new DbupdataModel();
		$now=time();
		$nowday=date('Y-m-d', $now);
		$res1=$this->where('gameid='.$gameid)->order('id')->find();
		if($res1){
			$newuptime=$dbupdata->getupnew($gameid);
			if($newuptime['uptime']!=$nowday){
				$nowdays=getdate($now)['yday'];
				$updays=getdate(strtotime($newuptime['uptime']))['yday'];
				$res=$nowdays-$updays;
				$needay=array();
				if($res>0){
					for ($i=0; $i <$res ; $i++) { 
						$contime=date("Y-m-d",strtotime($nowday."-".$i." day"));
						array_push($needay, $contime);
					}
					$data=$accountxlmj->upDataSome($needay,$gameid);
					$this->addAll($data);
					$dbupdata->dbUp($nowday,$gameid);
				}
			}
		}else{
			$data=$accountxlmj->getDayInfo($gameid);
			$this->addAll($data);
			$dbupdata->dbUp($nowday,$gameid);
		}
	}

	public function getGameInfo($page,$gameid){
		$count=$this->where('gameid='.$gameid)->count();
        if($count>C(PAGE_NUM)){
            $pagenum=ceil($count/C(PAGE_NUM));
        }else{
            $pagenum=1;
        }
        $countdata=$this->where('gameid='.$gameid)->order('countime desc')->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->select();
        foreach ($countdata as $key => $value) {
        	$p1=$value['oldplayer'];
        	if($countdata[$key+1]){
        		$p2=$countdata[$key+1]['newplayer'];
        	}else{
        		$c1=$value['countime'];
        		$t1=prevday($c1);
        		$map['countime']=$t1;
        		$tempnew= $this->where($map)->where('gameid='.$gameid)->field('newplayer')->select();
        		if($tempnew[0]['newplayer']){
     				$p2=$tempnew[0]['newplayer'];
        		}else{
        			$p2=0;
        		}
        	}
        	$countdata[$key]['oldx']=(number_format($p1/$p2,4)*100).'%';
        }
        $data[0]=$page;
        $data[1]=$pagenum;
        $data[2]=$countdata;
        $data[3]=$gameid;
        $data[4]=$count;
        return $data;
	}

	public function getGameInfos($conds){
		$map['gameid']=$conds['gameid'];
		$map['countime']=array('between',$conds['beftime'].",".$conds['endtime']);
        $countdata=$this->where($map)->order('countime desc')->select();
        foreach ($countdata as $key => $value) {
        	$p1=$value['oldplayer'];
        	if($countdata[$key+1]){
        		$p2=$countdata[$key+1]['newplayer'];
        	}else{
        		$c1=$value['countime'];
        		$t1=prevday($c1);
        		$map['countime']=$t1;
        		$tempnew= $this->where($map)->field('newplayer')->select();
        		if($tempnew[0]['newplayer']){
     				$p2=$tempnew[0]['newplayer'];
        		}else{
        			$p2=0;
        		}
        	}
        	$countdata[$key]['oldx']=(number_format($p1/$p2,4)*100).'%';
        }
        return $countdata;
	}

	public function GamePlayerInfo($key){
		if($key==1){
			$gameplayinfo = new PlayerinfoModel();
			$res=$gameplayinfo->getOneDay();
		}
	}
}