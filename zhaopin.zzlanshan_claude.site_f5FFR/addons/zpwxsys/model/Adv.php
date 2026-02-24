<?php
    
    namespace addons\zpwxsys\model;
    
    
    class Adv extends BaseModel
    {
        
        protected $name = 'zpwxsys_adv';
        
        public function items()
        {
            return $this->hasMany('BannerItem', 'banner_id', 'id');
        }
       
       
        public static function getBannerById($id)
        {
            $banner = self::with(['items', 'items.img'])->find($id);
            
            
            return $banner;
        }
        
        public static function getBanner($map)
        {
            
            $bannerlist = self::where($map)->order('sort desc')->select();
            
            
            if (empty($bannerlist)) {
                
                
                /*
               throw new MissException([
                   'msg' => '还没有任何数据',
                   'errorCode' => 50000
               ]);
                */
            }
            
            
            $data['from'] = 1;
            
            foreach ($bannerlist as $k => $v) {
                
                //$bannerlist[$k]['thumb'] = self::prefixImgUrl($v['thumb'],$data);
                
                $bannerlist[$k]['thumb'] = cdnurl($v['thumb'], true);
            }
            
            return $bannerlist;
            
            
        }
    }
