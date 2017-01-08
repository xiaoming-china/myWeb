<?php

namespace Org\Util;
use Think\Model;

class transaction{
	//数据库对象
	public $db;
	//表前缀
	public $prefix;

	public function __construct(){
		$this->db = new Model();
		$this->db->startTrans();
		$this->prefix = C('DB_PREFIX');
	}

	/**
	 * 提交事务
	 * @param  array $array 条件数组，如果存在一个false就回滚
	 * @return bool        提交成功返回true
	 */
	public function submit($array){
		foreach ($array as $key => $value) {
			if(!$value){
				$this->db->rollback();
				return false;
			}
		}
		return $this->db->commit();
	}

	/**
	 * 简化表名
	 * @param  [type] $tableName [description]
	 * @return [type]            [description]
	 */
	public function table($tableName){
		return $this->db->table($this->prefix.$tableName);
	}
}