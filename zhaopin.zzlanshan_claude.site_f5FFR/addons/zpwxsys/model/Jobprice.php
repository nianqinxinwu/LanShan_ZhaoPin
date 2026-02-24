<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Jobprice extends BaseModel
    {
        
     
        protected $name = 'zpwxsys_jobprice';
        
        public static function getJobpricelist()
        {
            
            
            $jobpricelist = Jobprice::where('enabled', '=', 1)->order('sort desc')->select();
            
            return $jobpricelist;
        }
        
    }
