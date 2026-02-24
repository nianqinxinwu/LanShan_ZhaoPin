<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    
    use addons\zpwxsys\service\Pay as PayService;
    use addons\zpwxsys\service\WxNotify;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use think\Log;
    
    class Pay extends BaseController
    {
        protected $beforeActionList = ['checkExclusiveScope' => ['only' => 'getPreOrder']];
        
        public function getPreOrder()
        {
            
            $id = input('post.id');
            (new IDMustBePositiveInt())->goCheck();
            
            
            $pay = new PayService($id);
            // Log::record('aaaaaa','error');
            return json_encode($pay->pay());
        }
        
        public function getPreCompanyroleOrder($id = '')
        {
            
            (new IDMustBePositiveInt())->goCheck();
            
            $pay = new PayService($id);
            
            return $pay->pay();
        }
        
        public function redirectNotify()
        {
            $notify = new WxNotify();
            $notify->handle();
        }
        
        public function notifyConcurrency()
        {
            $notify = new WxNotify();
            $notify->handle();
        }
        
        public function receiveNotify()
        {
            
            $notify = new WxNotify();

            $notify->handle();

        }
    }