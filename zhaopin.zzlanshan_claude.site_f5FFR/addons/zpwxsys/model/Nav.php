<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Nav extends BaseModel
    {
        protected $name = 'zpwxsys_nav';
        
        public static function getOne($map)
        
        {
            return self::where($map)->find();
            
            
        }
        
        
        public static function getNav()
        {
            $map['enabled'] = 1;
            
            $navlist = self::where($map)->order('sort desc')->select();
         
            
            
            $data['from'] = 1;
            
            foreach ($navlist as $k => $v) {
                
                // $navlist[$k]['thumb'] = self::prefixImgUrl($v['thumb'],$data);
                $navlist[$k]['thumb'] = cdnurl($v['thumb'], true);
                
            }
            
            
            return $navlist;
            
            
        }
    }
