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
 * 血流麻将模块模型
 *1:血流麻将
 * @author ed <xie5296808@163.com>
 */

class AccountxlmjModel extends BaseplayinfoModel {	
	// public function getNewAccount($conds){
	// 	$map['create_time']=array('between',array($conds['beftime'],$conds['endtime']));
	// 	$res1=$this->where($map)->field("create_time,count('userid')")->GROUP("DATE_FORMAT(create_time,'%Y-%m-%d')")->select();
	// 	$dayArr=array();
	// 	$days=diffBetweenTwoDays($conds['beftime'],$conds['endtime']);
	// 	for ($i=1; $i <=($days) ; $i++) {
	// 		$temp=date_sub(date_create($conds['endtime']),date_interval_create_from_date_string($i." days"));
	// 		$maps["DATE_FORMAT(create_time,'%Y-%m-%d')"]=date_format($temp,'Y-m-d');
	// 		$res2=$this->where($maps)->field('userid')->select();
	// 		$dayArr[date_format($temp,'Y-m-d')]=$res2;
	// 	}
	// 	return $dayArr;
	// }

	public function getDayInfo($gameid){
		$now=time();
		$timestr=date('Y-m-d', $now);
		$dayinfo=$this->query("select DATE_FORMAT(create_time,'%Y-%m-%d') days,count(userid),count(login_time) from tbl_account where DATE_FORMAT(create_time,'%Y-%m-%d')!='".$timestr."'  group by days DESC");
		$allplaynum=$this->query("select DATE_FORMAT(change_time,'%Y-%m-%d') days,count(user_id),count(DISTINCT user_id) from table_game_change_gold where user_id<1000 and DATE_FORMAT(change_time,'%Y-%m-%d') !='".$timestr."'group by days DESC");
		return $this->formatTableAcc($dayinfo,$allplaynum,$gameid);
	}
	public function getTimeNew($begin,$end){
		$map['create_time']=array('between',$begin.",".$end);
		$data=$this->field('userid')->where($map)->select();
		$newplayarr=array();
		foreach ($data as $key => $value) {
			array_push($newplayarr, $value['userid']);
		}
		return $newplayarr;
	}
	protected function formatTableAcc($data1,$data2,$gameid){
		$data=array();
		for ($i=0; $i <count($data1) ; $i++) {
			$data[$i]['gameid']=$gameid;
			$data[$i]['countime']=$data1[$i]['days'];
			$data[$i]['newplayer']=$data1[$i]['count(userid)'];
			$data[$i]['oldplayer']='0';
			$data[$i]['activeplayer']=$data1[$i]['count(login_time)'];
			$data[$i]['allplay']='0';
			$data[$i]['aveplay']='0';
			for ($j=0; $j < count($data2); $j++) { 
				if($data1[$i]['days']==$data2[$j]['days']){
					$data[$i]['allplay']=$data2[$j]['count(user_id)'];
					$data[$i]['aveplay']=$data2[$j]['count(distinct user_id)'];
					$data[$i]['aveplay']=ceil($data[$i]['allplay']/$data[$i]['aveplay']);
				}
			 } 
		}
		return $data;
	}
	public function upDataSome($daydata,$gameid){
		$daycondition=dayConditionFormat($daydata);
		$dayinfo=$this->query("select DATE_FORMAT(create_time,'%Y-%m-%d') days,count(userid),count(login_time) from tbl_account where create_time BETWEEN '".$daycondition[1]."' AND '".$daycondition[0]."' group by days DESC");
		$allplaynum=$this->query("select DATE_FORMAT(change_time,'%Y-%m-%d') days,count(user_id),count(DISTINCT user_id) from table_game_change_gold where user_id<1000 and change_time BETWEEN '".$daycondition[1]."' AND '".$daycondition[0]."' group by days DESC");
		return $this->formatTableAcc($dayinfo,$allplaynum,$gameid);
	}

public function exceldata($cond){
	$map=array();
	$tempid=array();
	if($cond['playid']!='null'&&$cond['playid']!=''){
		$map['tbl_playerinfo.userid']=$cond['playid'];
	}
	if($cond['bgtime']!='null'&&$cond['entime']!='null'&&$cond['bgtime']!=0&&$cond['entime']!=0){
		$map['tbl_account.create_time']=array('between',array($cond['bgtime'],$cond['entime']));
	}
	if($cond['nickname']!='null'&&$cond['playid']!=''){
		$map['nickname']=$cond['nickname'];
	}
	if($cond['playertype']==1){
		$map['tbl_account.userid'] = array('GT',1000);
	}elseif ($cond['playertype']==2) {
		$map['tbl_account.userid'] = array('LT',1000);
	}
	$count= $this->where($map)->count();// 查询满足要求的总记录数 
	$Page= new \Think\Page($count,10000);// 实例化分页类 传入总记录数和每页显示的记录数(25)  
	$ppp = ceil($count/10000);  
	$pp = range(1,$ppp);
	$str= "ID,昵称,登录方式,平台,性别,创建时间,最后登录时间,金币,钻石,福卡, 兑换劵,游戏局数,初级场,中级场,高级场";  
	$exl11= explode(',',$str);
	foreach ($pp as $kkk => $vvv) {  
	    $rs[$kkk] = $this->join('tbl_playerinfo ON tbl_playerinfo.userid = tbl_account.userid')->where($map)->page($vvv.', 10000')->field("tbl_account.userid,nickname,sex,gold,tbl_account.create_time,convert_tick,diamond,luck_tick,channel,last_update,ip,platform")->select();
		foreach ($rs[$kkk] as $key => $value){
			array_push($tempid, $value['userid']);
		}
	    foreach ($rs[$kkk] as $key => $value) {
				if($rs[$kkk][$key]['sex']==1){
					$rs[$kkk][$key]['sex']='男';
				}else{
					$rs[$kkk][$key]['sex']='女';
				}
				if($rs[$kkk][$key]['channel']=='game'){
					$rs[$kkk][$key]['channel']='游客登录';
				}elseif($res1[$key]['channel']=='weixin'){
					$rs[$kkk][$key]['channel']='微信登录';
				}
			}
			$sql="SELECT user_id,count(if(room_id=1201,true,null)) AS r1,count(if(room_id=1202,true,null)) AS r2,count(if(room_id=1203,true,null)) as r3 FROM `table_game_change_gold` where user_id in(";
	            foreach ($tempid as $keys => $values) {
	              $sql.=$values.',';
	            }
	        $sql=substr($sql,0,strlen($sql)-1);
			$sql.=") GROUP BY user_id";
			$res3=$this->query($sql);
			$temp3=array();
			foreach ($res3 as $key => $value) {
				$newkey=$value['user_id'];
				$temp3[$newkey]=$value;
			}
			$nres1=array();
			foreach ($rs[$kkk] as $keyss => $valuess) {
				$tempkey=$valuess['userid'];
				if($temp3[$tempkey]){
					$rs[$kkk][$keyss]['r1']=$temp3[$tempkey]['r1'];
					$rs[$kkk][$keyss]['r2']=$temp3[$tempkey]['r2'];
					$rs[$kkk][$keyss]['r3']=$temp3[$tempkey]['r3'];
					$rs[$kkk][$keyss]['rall']=$temp3[$tempkey]['r3']+$temp3[$tempkey]['r2']+$temp3[$tempkey]['r1'];
				}else{
					$rs[$kkk][$keyss]['r1']=0;
					$rs[$kkk][$keyss]['r2']=0;
					$rs[$kkk][$keyss]['r3']=0;
					$rs[$kkk][$keyss]['rall']=0;
				}
			}
	     
	    foreach ($rs[$kkk] as $k => $v){  
	        if (!$v['userid']) $v['userid']           = '暂无数据';  
	        if (!$v['nickname']) $v['nickname']                 = '暂无数据';  
	        if (!$v['sex']) $v['sex']             = '暂无数据';  
	        if (!$v['gold']) $v['gold']                 = '0';  
	        if (!$v['create_time']) $v['create_time']   = '暂无数据';
	        if (!$v['last_update']) $v['last_update']   = '暂无数据';  
	        if (!$v['convert_tick']) $v['convert_tick']   = '0';  
	        if (!$v['diamond']) $v['diamond']   = '0';  
	        if (!$v['luck_tick']) $v['luck_tick']   = '0';  
	        if (!$v['channel']) $v['channel']   = '暂无数据';  
	        if (!$v['platform']) $v['platform']   = '暂无数据';  
	        if (!$v['r1']) $v['r1']   = '0';  
	        if (!$v['r2']) $v['r2']   = '0';  
	        if (!$v['r3']) $v['r3']   = '0';  
	        if (!$v['rall']) $v['rall'] = '0';  
	        $exl[$kkk][] = array(  
	            $v['userid'],$v['nickname'],$v['channel'],$v['platform'],$v['sex'],$v['create_time'],$v['last_update'],$v['gold'],$v['diamond'],$v['luck_tick'],$v['convert_tick'],$v['rall'],$v['r1'],$v['r2'],$v['r3']
	        );  
	    }
	    exportToExcel('用户概况_'.time().$vvv.'.csv',$exl11,$exl[$kkk]);
	}  
	exit();  
	}

}
