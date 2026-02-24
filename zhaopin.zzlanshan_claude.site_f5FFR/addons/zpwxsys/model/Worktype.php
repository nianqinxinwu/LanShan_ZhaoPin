<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Worktype extends BaseModel
    {
        protected $name = 'zpwxsys_worktype';
        
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
