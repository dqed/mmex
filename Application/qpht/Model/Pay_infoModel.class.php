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

class Pay_infoModel extends BaseplayinfoModel {
	// protected $connection = 'DB_CONFIG3';
	// protected $tablePrefix = 'tbl_';
	// protected $gameid = '1';
	// public $page=0;
	public function exceldata($cond,$goodsprice,$accountxlmj){
		$rdata=array();
		$tdata=array();
		$map=array();
		$gameid=$cond['gameid'];
		$page=$cond['page'];
		$count= count($this->field("COUNT(*)")->GROUP("DATE_FORMAT(pay_time,'%Y-%m-%d')")->select());// 查询满足要求的总记录数  
        $Page= new \Think\Page($count,10000);// 实例化分页类 传入总记录数和每页显示的记录数(25)  
        $ppp = ceil($count/10000);  
        $pp = range(1,$ppp);
        $str= "统计日期,充值人数,充值次数,充值金额,新增充值人数,新增充值次数,新增充值金额";  
        $exl11 = explode(',',$str);
		foreach ($pp as $kkk => $vvv){
			$res1=$this->field("DATE_FORMAT(pay_time,'%Y-%m-%d'),count(user_id),count(DISTINCT user_id)")->GROUP("DATE_FORMAT(pay_time,'%Y-%m-%d')")->order("DATE_FORMAT(pay_time,'%Y-%m-%d') desc")->page($vvv.', 10000')->select();
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
			$mapp["pay_time"]=array('between',$tdata[$i]['paytime'].' 00:00:00,'.$tdata[$i]['paytime']." 23:59:59");
			$newids=$accountxlmj->getTimeNew($tdata[$i]['paytime'].' 00:00:00',$tdata[$i]['paytime']." 23:59:59");
			//var_dump($newids);
			$payerids=$this->where($mapp)->field('user_id,good_id')->select();
			for ($j=0; $j < count($payerids) ; $j++) {
				$tempid=$payerids[$j]['user_id'];
				if(!in_array($tempid,$newids)){
					unset($payerids[$j]);
				}
			}
			$newpay=0;
			$newpayer=array();
			foreach ($payerids as $key => $value) {
				array_push($newpayer, $value['user_id']);
				if($goodsprice[intval($value['good_id'])]){
						$newpay+=$goodsprice[intval($value['good_id'])];
					}
				
			}
			$tdata[$i]['newpaynums']=count($payerids);//新增次数
			$tdata[$i]['newpaymoney']=$newpay;//新增金额
			$tdata[$i]['newpayplayers']=count(array_unique($newpayer));//新增人数
			$tdata[$i]['payplayers']=$res1[$i]["count(distinct user_id)"];
			$tdata[$i]['paynums']=$res1[$i]["count(user_id)"];
			$tdata[$i]['paymoney']=$res1[$i]["recharge"];
		}
		// var_dump($tdata);
		// 	exit();
			foreach ($tdata as $k => $v){  
                    if (!$v['paytime']) $v['paytime'] = '暂无数据';  
                    if (!$v['payplayers']) $v['payplayers']  = '0';  
                    if (!$v['paynums']) $v['paynums'] = '0';  
                    if (!$v['paymoney']) $v['paymoney']  = '0';  
                    if (!$v['newpayplayers']) $v['newpayplayers']   = '0';  
                    if (!$v['newpaynums']) $v['newpaynums']   = '0';  
                    if (!$v['newpaymoney']) $v['newpaymoney']   = '0';  
                    $tdatas[] = array(  
                        $v['paytime'],$v['payplayers'],$v['paynums'],$v['paymoney'],$v['newpayplayers'],$v['newpaynums'],$v['newpaymoney'] 
                    );  
                }
               	exportToExcel('充值记录_'.time().$vvv.'.csv',$exl11,$tdatas);  
		}
		exit();
	}
}