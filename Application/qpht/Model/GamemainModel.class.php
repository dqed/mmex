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
	        	if($conds['beftime']!=$conds['endtime']){
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

	public function GamePlayerInfo($key){
		if($key==1){
			$gameplayinfo = new PlayerinfoModel();
			$res=$gameplayinfo->getOneDay();
		}
	}
}