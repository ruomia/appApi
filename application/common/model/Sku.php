<?php
namespace app\common\model;
use think\Model;
class Sku extends Model
{
    protected $table = 'goods_sku';

    public static function getAttrLists($goods_id)
    {
        $attr = self::field('attribute')->where('goods_id',$goods_id)->select();
        // $attr = $attr->toArray();
        $attrList = [];
        foreach($attr as $k => $v){
            $attrList = array_merge(explode(',',$v['attribute']), $attrList);
        }
        $attrList = array_unique($attrList);
        $row = Value::alias('a')
                    ->field("group_concat(concat(a.id,':',a.value)) value,name")
                    ->where('a.id','in',$attrList)
                    ->join('attribute_name b', 'a.name_id = b.id')
                    ->group('b.id')
                    ->select();
        foreach($row as $k => $v) {
            // $row[$k] = explode(':',explode(',',$v['value']));
            $value = explode(',', $v['value']);
            foreach($value as $k1 => $v1) {
                $value[$k1] = explode(':', $v1);
            }
            $row[$k]['value'] = $value;
        }
        return $row;
    }
}