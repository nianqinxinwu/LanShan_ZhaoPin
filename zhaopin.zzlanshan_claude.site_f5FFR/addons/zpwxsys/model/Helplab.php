<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Helplab extends BaseModel
    {
        protected $name = 'zpwxsys_helplab';
        
        public static function getOne($map)
        {
            
            
            $info = self::where($map)->find();
            
            
            return $info;
        }
        
        
        public static function getList($map, $od)
        {
            $list = self::where($map)->order($od)->select();
            
            return $list;
            
        }
        
        
    }
