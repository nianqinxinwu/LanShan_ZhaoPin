<?php
    
    namespace addons\zpwxsys\model;

    
    class Lookrole extends BaseModel
    {
        protected $name = 'zpwxsys_lookrole';
        
        public function getLookroleByWhere($map, $od)
        {
            $lookrolelist = $this->where($map)->order($od)->select();
            
            
            return $lookrolelist;
            
        }
        
        public static function getLookrole($map)
        {
            
            $companyrole = self::where($map)->find();
            
            
            return $companyrole;
            
        }
        
        
    }
