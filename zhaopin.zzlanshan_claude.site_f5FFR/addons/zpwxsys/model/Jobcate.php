<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Jobcate extends BaseModel
    {
        protected $name = 'zpwxsys_jobcate';
        
     
        public static function getJobcatelist()
        {
            
            
            $jobcatelist = Jobcate::where('enabled', '=', 1)->order('sort desc')->select();
            
            return $jobcatelist;
        }
        
        
        public static function getOne($map)
        {
            
            
            return self::where($map)->find();
        }
        
    }
