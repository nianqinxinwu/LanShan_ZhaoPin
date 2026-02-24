<?php

namespace addons\zpwxsys\model;

use think\Model;

class Chatrule extends Model
{
    protected $name = 'zpwxsys_chat_rule';

    public static function getActiveRule()
    {
        return self::where('status', 1)->order('id desc')->find();
    }
}
