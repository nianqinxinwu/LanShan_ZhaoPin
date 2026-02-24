<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Cate extends BaseModel
    {
        
    
        
        protected $name = 'zpwxsys_cate';
        
        public static function getCatelist()
        {
            
            
            $catelist = Cate::where(array('enabled' => 1))->order('sort desc')->select();
            
            return $catelist;
        }
        
    }
