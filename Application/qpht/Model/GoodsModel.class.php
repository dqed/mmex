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

class GoodsModel extends Model {		
	public function getGoodsPrice($id){
		$res=$this->where('platfrom_id='.$id)->getField('aibei_id,good_price');
		return $res;
	}
}
