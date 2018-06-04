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

class BaseplayinfoModel extends Model {
	// protected $connection = 'DB_CONFIG1';
	// protected $tablePrefix = 'qp_';
	public function getalldata(){

	}
//页面条件查询
	public function getOneDay($page,$cond){
			$rdata=array();
			$map=array();
			if($cond['playid']){
				$map['tbl_playerinfo.userid'] = array('EQ',$cond['playid']);
			}
			if($cond['playertype']==1){
				$map['tbl_playerinfo.userid'] = array('GT',1000);
			}elseif ($cond['playertype']==2) {
				$map['tbl_playerinfo.userid'] = array('LT',1000);
			}
			if($cond['bgtime']&&$cond['entime']){
				$map['tbl_playerinfo.create_time']=array('between',array($cond['bgtime'],$cond['entime']));
			}
			if($cond['nickname']){
				$map['nickname']=$cond['nickname'];
			}
			$num=$this->where($map)->count();
			if($num>C(PAGE_NUM)){
				$pagenum=ceil($num/C(PAGE_NUM));
			}else{
				$pagenum=1;
			}
			if($cond['ordertype']){
				$res1=$this->join('tbl_account ON tbl_playerinfo.userid = tbl_account.userid')->where($map)->order($cond['orderfile'].' '.$cond['ordertype'])->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->field("tbl_playerinfo.userid,nickname,sex,gold,tbl_playerinfo.create_time,convert_tick,diamond,luck_tick,channel,login_time,ip,platform")->select();
			}else{
				$res1=$this->join('tbl_account ON tbl_playerinfo.userid = tbl_account.userid')->where($map)->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->field("tbl_playerinfo.userid,nickname,sex,gold,tbl_playerinfo.create_time,convert_tick,diamond,luck_tick,channel,login_time,ip,platform")->order('tbl_playerinfo.create_time desc')->select();	
			}
			$tempid=array();
		foreach ($res1 as $key => $value) {
			if($res1[$key]['sex']==1){
				$res1[$key]['sex']='男';
			}else{
				$res1[$key]['sex']='女';
			}
			if($res1[$key]['channel']=='game'){
				$res1[$key]['channel']='游客登录';
			}elseif($res1[$key]['channel']=='weixin'){
				$res1[$key]['channel']='微信登录';
			}
			array_push($tempid, $value['userid']);
		}
		$nres1=array();
		if($tempid){
			$sql="SELECT user_id,count(if(room_id=".$cond['playlog']."01,true,null)) AS r1,count(if(room_id=".$cond['playlog']."02,true,null)) AS r2,count(if(room_id=".$cond['playlog']."03,true,null)) as r3 FROM `table_game_change_gold` where user_id in(";
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
			// //var_dump($temp3);
			// exit();
			foreach ($res1 as $keyss => $valuess) {
				$tempkey=$valuess['userid'];
				if($temp3[$tempkey]){
					$valuess['r1']=$temp3[$tempkey]['r1'];
					$valuess['r2']=$temp3[$tempkey]['r2'];
					$valuess['r3']=$temp3[$tempkey]['r3'];
					$valuess['rall']=$temp3[$tempkey]['r3']+$temp3[$tempkey]['r2']+$temp3[$tempkey]['r1'];
				}else{
					$valuess['r1']=0;
					$valuess['r2']=0;
					$valuess['r3']=0;
					$valuess['rall']=0;
				}
				array_push($nres1, $valuess);
			}
		}
		// var_dump($nres1);
		// exit();

		array_push($rdata, $pagenum);
		array_push($rdata, $nres1);
		array_push($rdata, $cond['gameid']);
		array_push($rdata,$page);
		// var_dump($rdata);
		// exit();
		return $rdata;		
	}

	public function playsInfo($cond){
		$rdata=array();
		$map=array();
		if($cond['page']){
			$page=$cond['page'];
		}else{
			$page=1;
		}
		if($cond['playid']){
			$map['table_game_change_gold.user_id']=$cond['playid'];
		}
		if($cond['bgtime']&&$cond['entime']){
			$map['table_game_change_gold.change_time']=array('between',array($cond['bgtime'],$cond['entime']));
		}
		$num=$this->where($map)->count('user_id');
		if($num>C(PAGE_NUM)){
				$pagenum=ceil($num/C(PAGE_NUM));
			}else{
				$pagenum=1;
			}
		$res1=$this->where($map)->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->select();
		foreach ($res1 as $key => $value) {
			$nickname=$this->query("select nickname from tbl_playerinfo where userid=".$value['user_id']);
			$res1[$key]['nickname']=$nickname[0]['nickname'];
			$res1[$key]['last_gold']=$value['change_gold']-$value['in_gold'];
		}
		array_push($rdata, $pagenum);
		array_push($rdata, $res1);
		array_push($rdata, $cond['gameid']);
		array_push($rdata,$page);
		return($rdata);	
	}

	public function convertGoods($cond){
		$rdata=array();
		$map=array();
		$page=$cond['page'];
		if($cond['user_id']){
			$map['user_id']=$cond['user_id'];
		}
		if($cond['phone']){
			$map['user_phone']=$cond['phone'];
		}
		if(!$cond['orderfile']){
			$cond['orderfile']='data desc';
		}
		if(!$cond['ordertype']){
			$cond['ordertype']=null;
		}
		$num=$this->where($map)->count();
		if($num>C(PAGE_NUM)){
			$pagenum=ceil($num/C(PAGE_NUM));
		}else{
			$pagenum=1;
		}
		$res1=$this->join('tbl_goods ON tbl_convert_good.convert_good_id = tbl_goods.good_id')->where($map)->order($cond['orderfile'].' '.$cond['ordertype'])->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->field('user_id,user_phone,convert_good_id,goods_name,goods_desc,data')->select();
		$res2=array();
		foreach ($res1 as $key => $value) {
			if(strstr($value['convert_good_id'],"TURNTABLE")||strstr($value['convert_good_id'],"CONVERT")){
				$value['goods_name']=$value['goods_desc'].$value['goods_name'];
			}
			array_push($res2, $value);
		}
		array_push($rdata, $pagenum);
		array_push($rdata, $res2);
		array_push($rdata, $cond['gameid']);
		array_push($rdata,$page);
		return($rdata);	
	}

public function payInfoCount($cond,$goodsprice){
		$rdata=array();
		$tdata=array();
		$map=array();
		$gameid=$cond['gameid'];
		$page=$cond['page'];
		$num=count($this->field("COUNT(*)")->GROUP("DATE_FORMAT(pay_time,'%Y-%m-%d')")->select());
		if($num>C(PAGE_NUM)){
				$pagenum=ceil($num/C(PAGE_NUM));
			}else{
				$pagenum=1;
			}
		$res1=$this->field("DATE_FORMAT(pay_time,'%Y-%m-%d'),count(user_id),count(DISTINCT user_id)")->GROUP("DATE_FORMAT(pay_time,'%Y-%m-%d')")->order("DATE_FORMAT(pay_time,'%Y-%m-%d') desc")->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->select();
		$days=array();
		foreach ($res1 as $key => $value) {
			array_push($days, $value["date_format(pay_time,'%y-%m-%d')"]);
		}
		$goodsids=array();
		foreach ($days as $key => $valuex) {
			$childx=array();
			$resx=$this->field('good_id')->where("DATE_FORMAT(pay_time,'%Y-%m-%d')='".$valuex."'")->select();
			foreach ($resx as $key => $value) {
				array_push($childx, $value['good_id']);
			}
			$goodsids[$valuex]=$childx;
		}
		$goodsparr=array();
		foreach ($goodsids as $key => $value) {
			$price=0;
			foreach ($value as $keys => $values) {
				if($goodsprice[intval($values)]){
					$price+=$goodsprice[intval($values)];
				}
			}
			$goodsparr[$key]=$price;
		}
		foreach ($res1 as $key => $value) {
			$timec=$value["date_format(pay_time,'%y-%m-%d')"];
			foreach ($goodsparr as $keye => $valuee) {
				if($timec==$keye){
					$res1[$key]['recharge']=$valuee;
				}
			}
			
		}
		for ($i=0; $i <count($res1) ; $i++) { 
			$tdata[$i]['paytime']=$res1[$i]["date_format(pay_time,'%y-%m-%d')"];
			$tdata[$i]['payplayers']=$res1[$i]["count(distinct user_id)"];
			$tdata[$i]['paynums']=$res1[$i]["count(user_id)"];
			$tdata[$i]['paymoney']=$res1[$i]["recharge"];
			if($res1[$i+1]){
				$tdata[$i]['newpayplayers']=$res1[$i]["count(distinct user_id)"]-$res1[$i+1]["count(distinct user_id)"];
				$tdata[$i]['newpaynums']=$res1[$i]["count(user_id)"]-$res1[$i+1]["count(user_id)"];
				$tdata[$i]['newpaymoney']=$res1[$i]["sum(pre_gold)"]-$res1[$i+1]["sum(pre_gold)"];
			}else{
				$dtime=$res1[$i]["date_format(pay_time,'%y-%m-%d')"];
				$ctime= date("Y-m-d",strtotime("$dtime   -1   day")); 
				$res2=$this->field("DATE_FORMAT(pay_time,'%Y-%m-%d'),Sum(pre_gold),count(user_id),count(DISTINCT user_id)")->where("DATE_FORMAT(pay_time,'%Y-%m-%d')='".$ctime."'")->GROUP("DATE_FORMAT(pay_time,'%Y-%m-%d')")->select();
				if($res2){
					$tdata[$i]['newpayplayers']=$res1[$i]["count(distinct user_id)"]-$res2[0]["count(distinct user_id)"];
					$tdata[$i]['newpaynums']=$res1[$i]["count(user_id)"]-$res2[0]["count(user_id)"];
					$tdata[$i]['newpaymoney']=$res1[$i]["sum(pre_gold)"]-$res2[0]["sum(pre_gold)"];
				}else{
					$tdata[$i]['newpayplayers']=0;
					$tdata[$i]['newpaynums']=0;
					$tdata[$i]['newpaymoney']=0;
				}
			}
		}
		array_push($rdata, $pagenum);
		array_push($rdata, $tdata);
		array_push($rdata, $cond['gameid']);
		array_push($rdata,$page);
		return($rdata);
	}
}
// select * from `tbl_playerinfo` where create_time BETWEEN '2018-05-01' AND '2018-05-08'  
// SELECT tbl_playerinfo.userid,`nickname`,`sex`,`gold`,tbl_account.create_time,`convert_tick`,`diamond`,`luck_tick`,tbl_account.channel,tbl_account.ip,tbl_account.platform,`login_time` FROM `tbl_playerinfo` INNER JOIN tbl_account ON tbl_playerinfo.userid=tbl_account.userid   WHERE  tbl_playerinfo.userid>1000 AND tbl_playerinfo.create_time BETWEEN '2018-05-07' AND '2018-05-08';

//  SELECT tbl_playerinfo.userid,`nickname`,`sex`,`gold`,tbl_account.create_time,`convert_tick`,`diamond`,`luck_tick`,tbl_account.channel,tbl_account.ip,tbl_account.platform,`login_time`,count(if(room_id=1201,true,null)),count(if(room_id=1202,true,null)),count(if(room_id=1203,true,null)) FROM `tbl_playerinfo` INNER JOIN tbl_account ON tbl_playerinfo.userid=tbl_account.userid INNER JOIN table_game_change_gold ON tbl_playerinfo.userid=table_game_change_gold.user_id  WHERE ( tbl_playerinfo.userid>1000 AND tbl_playerinfo.create_time BETWEEN '2018-05-01 00:00:00' AND '2018-05-08 00:00:00') GROUP BY tbl_playerinfo.userid
// SELECT tbl_playerinfo.userid,`nickname`,`sex`,`gold`,tbl_playerinfo.create_time,`convert_tick`,`diamond`,`luck_tick`,`channel`,`login_time`,`ip`,`platform` FROM `tbl_playerinfo` INNER JOIN tbl_account ON tbl_playerinfo.userid=tbl_account.userid WHERE tbl_playerinfo.create_time BETWEEN '2018-05-09' AND '2018-05-11'  LIMIT 0,20
