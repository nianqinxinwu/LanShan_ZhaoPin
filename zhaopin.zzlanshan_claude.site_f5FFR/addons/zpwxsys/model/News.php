<?php
    
    namespace addons\zpwxsys\model;
    

    
    class News extends BaseModel
    {
        protected $name = 'zpwxsys_news';
        
        public function getNewsByWhere($map, $Nowpage, $limits, $od)
        {
            $newslist = $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            if ($newslist) {
                foreach ($newslist as $k => $v) {
                    $newslist[$k]['thumb'] = cdnurl($v['thumb'], true);
                    $newslist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                }
                
            }
            
            
            return $newslist;
            
        }
        
        
        public function getNewsDetail($map)
        {
            
            $newsinfo = self::where($map)->find();
            $newsinfo['thumb'] = cdnurl($newsinfo['thumb'], true);
            $newsinfo['createtime'] = date('Y-m-d', $newsinfo['createtime']);
            return $newsinfo;
            
        }
        
        
    }
