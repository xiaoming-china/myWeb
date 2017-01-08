<?php

namespace Think\Template\TagLib;
use Think\Template\TagLib;

/**
 *数据查询
 */

class Data extends TagLib{
    protected $tags   =  array(
        'select'=>array('attr'=>'table,where,order,limit,id,page,sql,cache,field,key,mod,debug','level'=>3)
        );
    public function _select($tag,$content){
        $table                     = !empty($tag['table'])?$tag['table']:'';
        $order                     = !empty($tag['order'])?$tag['order']:'';
        $limit                     = !empty($tag['limit'])?intval($tag['limit']):'';
        $id                        = !empty($tag['id'])?$tag['id']:'v';
        $where                     = !empty($tag['where'])?$tag['where']:' 1 ';
        $key                       = !empty($tag['key'])?$tag['key']:'i';
        $mod                       = !empty($tag['mod'])?$tag['mod']:'2';
        $page                      = !empty($tag['page'])?$tag['page']:false;
        $sql                       = !empty($tag['sql'])?$tag['sql']:'';
        $field                     = !empty($tag['field'])?$tag['field']:'';
        $cache                     = !empty($tag['cache'])?$tag['cache']:0;
        $debug                     = !empty($tag['debug'])?$tag['debug']:false;
        $this->comparison['noteq'] = '<>';
        $this->comparison['sqleq'] = '=';
        $where                     = $this->parseCondition($where);
        $sql                       = $this->parseCondition($sql);
        $parsestr .= '<?php $m = M("'.$table.'");';
         
        if($sql){
            if($page){
                $limit = $limit ? $limit : 10;//如果有page，没有输入limit则默认为10
                $parsestr .= '$count = count($m->query("'.$sql.'"));';
                $parsestr .= '$p = new \Think\Page ( $count, '.$limit.' );';
                $parsestr .= '$sql.="'.$sql.'";';
                
                $parsestr .= '$sql.=" limit ".$p->firstRow.",".$p->listRows."";';
                $parsestr .= '$result=$m->query($sql);';
                $parsestr .= '$pages=$p->show();';
                //$parsestr.='dump($count);dump($sql);';
            }else{
                $sql.=$limit?(' limit '.$limit):'';
                $parsestr.='$result=$m->query("'.$sql.'");'; 
            }
        }else{
            if($page){
                $limit = $limit ? $limit : 10;//如果有page，没有输入limit则默认为10; 
                $parsestr .= '$count=$m->where("'.$where.'")->count();';
                $parsestr .= '$p = new \Think\Page ( $count, '.$limit.' );';
                //缓存
                if($cache){
                    $parsestr .= '$result=$m->cache("true","'.$cache.'")->field("'.$field.'")->where("'.$where.'")->limit($p->firstRow.",".$p->listRows)->order("'.$order.'")->select();';
                }else{
                    $parsestr .= '$result=$m->field("'.$field.'")->where("'.$where.'")->limit($p->firstRow.",".$p->listRows)->order("'.$order.'")->select();';
                }
                $parsestr .= '$pages=$p->show();';
            }else{
                // 缓存
                if($cache){
                    $parsestr.='$result=$m->cache("true","'.$cache.'")->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit("'.$limit.'")->select();';
                }else{
                    $parsestr.='$result=$m->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit("'.$limit.'")->select();';
                }            
            }
        }      
        if($debug!=false){
            $parsestr.='dump($result);dump($m->getLastSql());';
        }
        $parsestr.= 'if ($result): $'.$key.'=0;';
        $parsestr.= 'foreach($result as $key=>$'.$id.'):';
        $parsestr.= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
        $parsestr.= $this->tpl->parse($content);      
        $parsestr.= '<?php endforeach;endif;?>';       
        return $parsestr;    
    }
}
