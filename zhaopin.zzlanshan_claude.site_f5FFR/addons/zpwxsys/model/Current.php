<?php
    
    namespace addons\zpwxsys\model;
    

    
    class Current extends BaseModel
    {
        
        protected $name = 'zpwxsys_current';
        
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
