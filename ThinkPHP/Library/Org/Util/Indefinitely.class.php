<?php
/**
 * name:无限级类
 * time:16-11-22
 * by:xiaoming
 */

namespace Org\Util;
class Indefinitely{

	/*
	select无限极分类
	$data数组
	$pid父级id
	*/
	static function infinite($data,$pid = 0,$html = '├',$level = 0){
	      $str = '';
	  if($level != 0){
	    for($i = 0;$i<$level;$i++){
	      $str .= '&nbsp;';  
	    }
	      $str  = $str.$html;
	  }
	  $arr = array();
	  foreach ($data as $k => $v) {
	      if($v['parent'] == $pid){
	        $v['html']  = $str;
	        $arr[] = $v;
	        $arr   = array_merge($arr,self::infinite($data,$v['t_id'],$html,$level + 1));
	      }
	  }
	  return $arr;
	}
	/**
	 * [navigation 列表形式tree]
	 * @param  [type]  $data [description]
	 * @param  integer $pid  [description]
	 * @param  integer $lev  [description]
	 * @return [type]        [description]
	 */
	static function navigation($data,$pid = 0,$lev = 0){
          $str = '';
          $t = '';
        if($lev != 0){
          $ii = 0;
          for($i = 0;$i < $lev;$i++){
            $ii = $ii + 45;
           }
          $str = '<span style="margin-left:'.$ii.'px;" class="icon-folder-open-alt">&nbsp;|--&nbsp;</span>';
        }else{
          $str = '<span class="icon-folder-close-alt">&nbsp; </span>';
        }
		  $arr = array();
		  foreach ($data as $k => $v) {
		      if($v['parent'] == $pid){
		        $v['befor']   =  $str;
		        $arr[]        =  $v;
		        $arr = array_merge($arr,self::navigation($data,$v['t_id'],$lev + 1));
		      }
		  }
  return $arr;
}
	//基本树形
	static function basicTree($cate,$html = '--',$pid,$level=0){
		$arr = array();
		foreach ($cate as $key => $v) {
			if($v['parent'] == $pid){
				$v['level'] = $level+1;
				$v['html']  = str_repeat($html, $level);
				$arr[] = $v;
				$arr = array_merge($arr,self::basicTree($cate,$html,$v['t_id'],$level+1));
			}
		}
		return $arr;
	}
	//组合多维数组
	static function manyArray($cate,$pid = 0){
		$arr = array();
		foreach ($cate as $key => $v) {
			if($v['parent'] == $pid){
				$v['child'] = self::manyArray($cate,$v['t_id']);
				$arr[] = $v;
			}
		}
		return $arr;
	}
	//所有父级
	static function getAllParent($cate,$cid){
		$arr = array();
		foreach ($cate as $key => $v) {
			if($v['t_id'] == $cid){
				$arr[] = $v;
				$arr = array_merge(self::getAllParent($cate,$v['parent']),$arr);
			}
		}
		return $arr;
	}
	//父级所有子级id
	static function getAllChilds($cate,$pid){
		$arr = array();
		foreach ($cate as $key => $v) {
			if($v['parent'] == $pid){
				$arr[] = $v['t_id'];//$v全部，查找所有子级
				$arr   = array_merge($arr,self::getAllChilds($cate,$v['t_id']));
			}
		}
		return $arr;
	}

}