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
 * 血流麻将用户概况模型
 * @author ed <xie5296808@163.com>
 */

class Game_change_goldModel extends BaseplayinfoModel {
	// public $connection = 'DB_CONFIG2';
	// protected $tablePrefix = 'table_';
	//protected $gameid = '1';

	public function getOldOlayer($conds){
		$lres=array();
		foreach ($conds as $key => $value) {
			$res=array();			
			$ytiem=date_format(date_sub(date_create($key),date_interval_create_from_date_string("1 days")),'Y-m-d');
			$map["DATE_FORMAT(change_time,'%Y-%m-%d')"]=$key;
			$res1=$this->where($map)->field('DISTINCT user_id')->select();
			$temps1=array();
			foreach ($res1 as $keys1 => $valuess) {
				array_push($temps1, $valuess['user_id']);
			}
			$res2=$conds[$ytiem];
			$temps2=array();
			foreach ($res2 as $keys2 => $values) {
				array_push($temps2, $values['userid']);
			}
			$res3=array_intersect($temps1,$temps2);
			$res['time']=$key;
			$res['newplayer']=count($value);
			$res['oldplayer']=count($res3);
			array_push($lres, $res);
		}
		return $lres;
	}

	public function getPlayerNum($conds){
		$res=array();
		foreach ($conds as $key => $value) {
			$map["DATE_FORMAT(change_time,'%Y-%m-%d')"]=$value['time'];
			$map["user_id"]=array('GT',1000);
			$playerNum=$this->where($map)->field('count(DISTINCT user_id)')->select();
			$value['hyplayer']=$playerNum[0]['count(distinct user_id)'];
			array_push($res, $value);
			//var_dump($value);
		}
		return $res;
	}

	public function getActiver($conds){
		$res=array();
		$days=diffBetweenTwoDays($conds['beftime'],$conds['endtime']);
		if($days<1&&$days>0){
			$maps["DATE_FORMAT(change_time,'%Y-%m-%d')"]=$conds['beftime'];
			$maps["user_id"]=array('GT',1000);
			$res2=$this->where($maps)->field("count(DISTINCT user_id)")->select();
			$res[$conds['beftime']]=$res2[0]['count(distinct user_id)'];
		}else{
			for ($i=1; $i <=($days) ; $i++) {
				$temp=date_sub(date_create($conds['endtime']),date_interval_create_from_date_string($i." days"));
				$maps["DATE_FORMAT(change_time,'%Y-%m-%d')"]=date_format($temp,'Y-m-d');
				$maps["user_id"]=array('GT',1000);
				$res2=$this->where($maps)->field("count(DISTINCT user_id)")->select();
				$res[date_format($temp,'Y-m-d')]=$res2[0]['count(distinct user_id)'];
			}	
		}
		return $res;
	}
	public function getplaysInfo($conds){
		$res=array();
		foreach ($conds as $key => $value) {
			// $map["DATE_FORMAT(change_time,'%Y-%m-%d')"]=$value['time'];
			$data=$this->query("select DATE_FORMAT(change_time,'%Y-%m-%d') days,count(user_id),count(DISTINCT user_id) from table_game_change_gold where user_id>1000 and DATE_FORMAT(change_time,'%Y-%m-%d') ='".$value['time']."'");
			// $data=$this->query("select DATE_FORMAT(change_time,'%Y-%m-%d') days,count(user_id),count(DISTINCT user_id) from table_game_change_gold where user_id<1000 and DATE_FORMAT(change_time,'%Y-%m-%d') ='".$value['time']."'");去除机器人
			$s1=$data[0]['count(user_id)'];
			if($s1!=0){
				$s2=ceil($s1/$data[0]['count(distinct user_id)']);
			}else{
				$s2=0;
			}
			$value['allplays']=$s1;
			$value['avplays']=$s2;
			array_push($res, $value);
		}
		return $res;
	}

	public function exceldata($cond){
            //$count      = $res->alias('l')->where($where)->count();// 查询满足要求的总记录数
            if($cond['playid']!='null'&&$cond['playid']!=''){
				$map['user_id']=$cond['playid'];
			}
			if($cond['bgtime']!='null'&&$cond['entime']!='null'&&$cond['bgtime']!=0&&$cond['entime']!=0){
				$map['change_time']=array('between',array($cond['bgtime'],$cond['entime']));
			} 
            $count = $this->where($map)->count();// 查询满足要求的总记录数  
            $Page  = new \Think\Page($count,10000);// 实例化分页类 传入总记录数和每页显示的记录数(25)  
            $ppp = ceil($count/10000);  
            $pp = range(1,$ppp);
            $str = "user_id,change_gold,room_id,change_time,in_gold"; 
            $exl11= explode(',',$str);
            $filename='对局数据_'.time().'.csv';
            foreach ($pp as $kkk => $vvv) {  
                $rs[$kkk] = $this->field('user_id,change_gold,room_id,change_time,in_gold')->where($map)->page($vvv.', 10000')->select();    
                foreach ($rs[$kkk] as $k => $v){  
                    if (!$v['user_id']) $v['userid']           = '暂无数据';  
                    if (!$v['change_gold']) $v['change_gold']  = '0';  
                    if (!$v['room_id']) $v['room_id']          = '暂无数据';  
                    if (!$v['change_time']) $v['change_time']  = '暂无数据';  
                    if (!$v['in_gold']) $v['in_gold']   = '0'; 
                    $exl[$kkk][] = array(  
                        $v['user_id'],$v['change_gold'],$v['room_id'],$v['change_time'],$v['in_gold']  
                    );  
                }

               	exportToExcel($filename,$exl11,$exl[$kkk]);  
            }
            exit();  
    	}
}