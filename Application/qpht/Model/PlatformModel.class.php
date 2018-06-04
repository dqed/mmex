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

class PlatformModel extends Model {
	protected $connection = 'DB_CONFIG2';
	protected $tablePrefix = 'table_';
	protected $_validate = array(
     
   );
   
   public function getGameName(){
   		return $this->order('id')->getField('id,name'); 
   }
}