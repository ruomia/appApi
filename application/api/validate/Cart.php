<?php
namespace app\api\validate;

use think\Validate;

class Cart extends Validate
{
    protected $rule = [
        // 'user_id' => 'require',
        'goods_id' => 'require',
        'sku_id' => 'require',
        'price' => 'require',
        'count' => 'require',
        'checked' => 'require',
    ];
    protected $message  =   [
        // 'user_id.require' => '用户必须',
        'goods_id.require' => '商品必须',
        'sku_id.require' => 'SKU必须',
        'price.require' => '价格必须',
        'count.require' => '数量必须',
        'checked.require' => '是否选中必须',    
    ];
}