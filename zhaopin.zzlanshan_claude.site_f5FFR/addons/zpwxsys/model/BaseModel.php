<?php
    
    
    namespace addons\zpwxsys\model;
    
    
    use think\Model;
    
    class BaseModel extends Model
    {
        
        
        public static function prefixImgUrl($value, $data)
        {
            $finalUrl = $value;
            if ($data['from'] == 1) {
                $pos = strstr($value, 'http');
                if (!$pos) {
                    
                    $finalUrl = config('setting.img_prefix') . $value;
                    
                    
                }
            }
            return $finalUrl;
        }
        
        public function time_tran($time)
        {
            $t = time() - $time;
            $f = array('31536000' => '年', '2592000' => '个月', '604800' => '星期', '86400' => '天', '3600' => '小时', '60' => '分钟', '1' => '秒');
            foreach ($f as $k => $v) {
                if (0 != $c = floor($t / (int)$k)) {
                    return $c . $v . '前';
                }
            }
        }
        
    }