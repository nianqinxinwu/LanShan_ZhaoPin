<?php
    
    namespace addons\zpwxsys\model;
    

    
    class Lookrolerecord extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_lookrolerecord';
        
        public function getLookRoleRecordByWhere($map, $od)
        {
            $lookrolelist = $this->where($map)->order($od)->select();
            
            return $lookrolelist;
            
        }
        
        
    }
