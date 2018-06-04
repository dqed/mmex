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

class DbupdataModel extends Model {
	protected $connection = 'DB_CONFIG1';
	protected $tablePrefix = 'qp_';

	public function dbUp($daystr,$gameid){
		$data['uptime']=$daystr;
		$data['gameid']=$gameid;
		$map['uptime']=$daystr;
		$map['gameid']=$gameid;
		if(!$this->where($map)->find()){
			$this->add($data);
		}
	}

	public function getupnew($gameid){
		return $this->where('gameid='.$gameid)->order('uptime desc')->find();
	}
}