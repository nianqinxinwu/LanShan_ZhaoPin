<?php
    
    namespace addons\zpwxsys\model;
    

    
    class Companyrole extends BaseModel
    {
        
        protected $name = 'zpwxsys_companyrole';
        
        public function getCompanyroleByWhere($map, $od)
        {
            $companyrolelist = $this->where($map)->order($od)->select();
            
            
            return $companyrolelist;
            
        }
        
        public static function getCompanyrole($map)
        {
            
            $companyrole = self::where($map)->find();
            
            
            return $companyrole;
            
        }
        
        
    }
