<?php
namespace app\common\model;

use think\Model;

class Carousel extends Model
{
    protected $autoWriteTimestamp = 'timestamp';
    protected $createTime = 'create_time';
    protected $updateTime = false;

    public static function init()
    {
        self::event('before_update', function ($row) {
            @unlink(ltrim($row->image, '/'));
        });

        self::event('before_delete', function ($row) {
            @unlink(ltrim($row->image, '/'));
        });
    }
}