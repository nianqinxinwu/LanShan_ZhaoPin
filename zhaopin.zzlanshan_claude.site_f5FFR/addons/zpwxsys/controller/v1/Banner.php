<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\model\Adv as BannerModel;
    use addons\zpwxsys\lib\exception\MissException;
    
    /**
     * 幻灯片相关接口
     */
    class Banner extends BaseController
    {
        /*
         *
         * 获取某个幻灯片详情信息
         */
        
        public function getBanner()
        {
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            $banner = BannerModel::getBanner();
            if (!$banner) {
                throw new MissException(['msg' => '请求banner不存在', 'errorCode' => 40000]);
            }
            
            return $banner;
        }
    }