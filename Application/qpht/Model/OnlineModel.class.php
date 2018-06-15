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

class OnlineModel extends Model {
	protected $connection = 'DB_CONFIG1';
	protected $tablePrefix = 'qp_';
	public function getOnlineData($conds){
		if($conds['isone']==1){
			$timex=array();
			$datax=array();
			$timearr=[$conds['beftime']." 00:00:00",$conds['beftime']." 01:00:00",$conds['beftime']." 02:00:00",$conds['beftime']." 03:00:00",$conds['beftime']." 04:00:00",$conds['beftime']." 05:00:00",$conds['beftime']." 06:00:00",$conds['beftime']." 07:00:00",$conds['beftime']." 08:00:00",$conds['beftime']." 09:00:00",$conds['beftime']." 10:00:00",$conds['beftime']." 11:00:00",$conds['beftime']." 12:00:00",$conds['beftime']." 13:00:00",$conds['beftime']." 14:00:00",$conds['beftime']." 15:00:00",$conds['beftime']." 16:00:00",$conds['beftime']." 17:00:00",$conds['beftime']." 18:00:00",$conds['beftime']." 19:00:00",$conds['beftime']." 20:00:00",$conds['beftime']." 21:00:00",$conds['beftime']." 22:00:00",$conds['beftime']." 23:00:00"];
			for ($i=0; $i <count($timearr) ; $i++) { 
				$t1=strtotime($timearr[$i]);
				$timex[$i]=$t1;
			}
			for ($i=0; $i <count($timex) ; $i++) { 
				$bt=$timex[$i];
				$et=$timex[$i+1];
				if(!$et){
					$et=strtotime($conds['beftime']." 23:59:59");
				}
				$map['time']=array('between',$bt.','.$et);
				$map['gameid']=$conds['gameid'];
				$data=$this->where($map)->max('onlinenum');
				$datax[$bt]=$data;
			}
			$dataxx=array();
			foreach ($datax as $key => $value) {
				$tempkey=date('H:i:s',$key);
				if(!$value){
					$value=0;
				}
				$dataxx[$tempkey]=$value;
			}
			$dataxxx=array();
			foreach ($timearr as $key => $value) {
				$tempkeyc=date('H:i:s',strtotime($value));
				if(!$dataxx[$tempkeyc]){
					$dataxxx[$tempkeyc]=0;
				}else{
					$dataxxx[$tempkeyc]=$dataxx[$tempkeyc];
				}
			}
			return $dataxxx;
		}else{
				$res=array();
				$days=diffBetweenTwoDays($conds['beftime'],$conds['endtime']);
				if($days>0&&$days<1){
					$days=1;
				}
				for ($i=0; $i <($days) ; $i++) {
					$temp=date_sub(date_create($conds['endtime']),date_interval_create_from_date_string($i." days"));
					$tempx=strtotime(date_format($temp,'Y-m-d'));
					$temp2=date("Y-m-d",strtotime("+1 day",$tempx));
					$temp2x=strtotime($temp2);
					$maps['time']=array('between',$tempx.','.$temp2x);
					$maps['gameid']=$conds['gameid'];
					$res2=$this->where($maps)->max("onlinenum");
					if(!$res2){
						$res2=0;
					}
					$res[date_format($temp,'Y-m-d')]=$res2;
				}
				return $res;
			}
		}
}