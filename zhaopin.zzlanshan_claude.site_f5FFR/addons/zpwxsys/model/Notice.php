<?php
    
    namespace addons\zpwxsys\model;
    

    
    class Notice extends BaseModel
    {
        
        protected $name = 'zpwxsys_notice';
        
        public function getNoticeByWhere($map, $Nowpage, $limits, $od)
        {
            $noticelist = $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($noticelist) {
                foreach ($noticelist as $k => $v) {
                    $noticelist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                }
                
            }
            
            
            return $noticelist;
            
        }
        
        
        public function getNoticeDetail($map)
        {
            
            $notceinfo = self::where($map)->find();
            $data['from'] = 1;
            $notceinfo['createtime'] = date('Y-m-d', $notceinfo['createtime']);
            return $notceinfo;
            
        }
        
        
    }
