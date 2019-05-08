<?php
namespace app\api\validate;

use think\Validate;

class Order extends Validate
{
    protected $rule = [
        // 'goods_id' => 'require',
        'sku_id' => 'require',
        'real_payment' => 'require',
        // 'count' => 'require',
        // 'checked' => 'require',
    ];
    protected $message  =   [
        // 'goods_id.require' => '商品必须',
        'sku_id.require' => 'SKU必须',
        'real_payment.require' => '价格必须',
        // 'count.require' => '数量必须',
        // 'checked.require' => '是否选中必须',    
    ];
}