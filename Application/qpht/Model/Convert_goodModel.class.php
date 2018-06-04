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

class Convert_goodModel extends BaseplayinfoModel {
	// protected $connection = 'DB_CONFIG2';
	// protected $tablePrefix = 'tbl_';
	// protected $gameid = '1';
	// public $page=0;

	public function exceldata($cond){
		    if($cond['playid']!='null'&&$cond['playid']!=''){
				$map['user_id']=$cond['playid'];
			}
			if($cond['user_phone']!='null'&&$cond['user_phone']!=''){
				$map['user_phone']=$cond['phone'];
			}
			if(!$cond['orderfile']){
				$cond['orderfile']='user_id desc';
			}
			if(!$cond['ordertype']){
				$cond['ordertype']=null;
			}
            $count= $this->where($map)->count();// 查询满足要求的总记录数  
            $Page= new \Think\Page($count,10000);// 实例化分页类 传入总记录数和每页显示的记录数(25)  
            $ppp = ceil($count/10000);  
            $pp = range(1,$ppp);
            $str= "用户id,兑换游戏,兑换时间,兑换物品,联系方式";  
            $exl11 = explode(',',$str);
            foreach ($pp as $kkk => $vvv) {  
                $rs[$kkk] = $this->join('tbl_goods ON tbl_convert_good.convert_good_id = tbl_goods.good_id')->where($map)->order($cond['orderfile'].' '.$cond['ordertype'])->page($vvv.', 10000')->field('user_id,user_phone,convert_good_id,goods_name,goods_desc,data')->select();
                foreach ($rs[$kkk] as $key => $value) {
					if(strstr($value['convert_good_id'],"TURNTABLE")||strstr($value['convert_good_id'],"CONVERT")){
						$rs[$kkk][$key]['goods_name']=$value['goods_desc'].$value['goods_name'];
						$rs[$kkk][$key]['game_name']=$cond['gamename'];
					}
				}
                 
                foreach ($rs[$kkk] as $k => $v){  
                    if (!$v['user_id']) $v['user_id'] = '暂无数据';  
                    if (!$v['game_name']) $v['game_name']  = '暂无数据';  
                    if (!$v['data']) $v['data'] = '暂无数据';  
                    if (!$v['goods_name']) $v['goods_name']  = '暂无数据';  
                    if (!$v['user_phone']) $v['user_phone']   = '暂无数据';  
                    $exl[$kkk][] = array(  
                        $v['user_id'],$v['game_name'],$v['data'],$v['goods_name'],$v['user_phone']  
                    );  
                }  
               exportToExcel('兑换记录_'.time().$vvv.'.csv',$exl11,$exl[$kkk]);  
            }  
            exit();
		}
}