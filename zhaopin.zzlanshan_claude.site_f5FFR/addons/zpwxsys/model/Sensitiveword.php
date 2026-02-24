<?php

namespace addons\zpwxsys\model;

use think\Model;

class Sensitiveword extends Model
{
    protected $name = 'zpwxsys_sensitive_word';

    public static function getAllWords()
    {
        return self::where('status', 1)->column('word');
    }

    public static function filterContent($content)
    {
        $words = self::getAllWords();
        if (empty($words)) {
            return $content;
        }
        foreach ($words as $word) {
            $content = str_replace($word, str_repeat('*', mb_strlen($word)), $content);
        }
        return $content;
    }

    public static function containsSensitive($content)
    {
        $words = self::getAllWords();
        if (empty($words)) {
            return false;
        }
        foreach ($words as $word) {
            if (mb_strpos($content, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}
