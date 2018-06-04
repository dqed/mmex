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

class User_goods_changeModel extends BaseplayinfoModel {
	public function getinfos($conds){
		$rdata=array();
		$map['user_id']=$conds['userid'];
		$map['good_id']=$conds['goldtype'];
		$count = $this->where($map)->count();
		$ppp = ceil($count/C(PAGE_NUM));
		if($conds['page']){
			$page=$conds['page'];
		}else{
			$page=1;
		}
		$data=$this->where($map)->order('change_time desc')->limit(($page-1)*C(PAGE_NUM).','.C(PAGE_NUM))->select();
		foreach ($data as $key => $value) {
			if($value['source']=='1'){
				$data[$key]['source']='游乐充值';
			}else if ($value['source']=='2') {
				$data[$key]['source']='转盘抽取';
			}else if ($value['source']=='3') {
				$data[$key]['source']='转盘获得';
			}else if ($value['source']=='4') {
				$data[$key]['source']='钻石兑换金币';
			}
		}
		array_push($rdata, $ppp);
		array_push($rdata, $data);
		array_push($rdata, $conds['gameid']);
		array_push($rdata,$page);
		// var_dump($rdata);
		// exit();
		return $rdata;		

	}
	
}