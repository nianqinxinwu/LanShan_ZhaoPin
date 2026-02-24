<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Order as OrderModel;
    use addons\zpwxsys\model\Lookrole as LookroleModel;
    use addons\zpwxsys\model\Companyrole as CompanyroleModel;
    use addons\zpwxsys\model\Active as ActiveModel;
    use addons\zpwxsys\service\Order as OrderService;
    use addons\zpwxsys\service\Token;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\validate\OrderPlace;
    use addons\zpwxsys\lib\exception\OrderException;
    use addons\zpwxsys\lib\exception\SuccessMessage;
    
    class Order extends BaseController
    {
        protected $beforeActionList = ['checkExclusiveScope' => ['only' => 'placeOrder'], 'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser'], 'checkSuperScope' => ['only' => 'delivery,getSummary']];
        
        /**
         * 下单
         * @url /order
         * @HTTP POST
         */
        public function placeOrder()
        {
            (new OrderPlace())->goCheck();
            $products = input('post.products/a');
            $uid = Token::getCurrentUid();
            $order = new OrderService();
            $status = $order->place($uid, $products);
            return $status;
        }
        
        
        public function lookRoleOrder()
        {
            
            (new OrderPlace())->goCheck();
            
            $type = input('post.type');
            $pid = input('post.pid');
            
            $lookroleinfo = LookroleModel::get($pid);
            
            $products = ['id' => $lookroleinfo->id, 'name' => $lookroleinfo->title, 'money' => $lookroleinfo->money, 'looknum' => $lookroleinfo->looknum];
            
            
            if (!$lookroleinfo) {
                throw new OrderException();
            }
            
            
            $uid = Token::getCurrentUid();
            $order = new OrderService();
            
            
            $status = $order->placeLookrole($uid, $lookroleinfo, $type);
            
            return json_encode($status);
            
        }
        
        
        public function companyroleOrder()
        {
            
            
            (new OrderPlace())->goCheck();
            
            $type = input('post.type');
            $pid = input('post.pid');
            
               $ctoken = input('post.ctoken');
                
                $companyid = Token::getCurrentCid($ctoken);
                
                
           // $companyid = input('post.companyid');
            
            $roleinfo = CompanyroleModel::get($pid);
            
            $products = ['id' => $roleinfo->id, 'name' => $roleinfo->title, 'money' => $roleinfo->money, 'jobnum' => $roleinfo->jobnum, 'notenum' => $roleinfo->notenum, 'topnum' => $roleinfo->topnum];
            
            
            if (!$roleinfo) {
                throw new OrderException();
            }
            
            
            $uid = Token::getCurrentUid();
            $order = new OrderService();
            
            $status = $order->placeCompanyrole($uid, $products, $type, $companyid);
            
            return json_encode($status);
            
            
        }
        
        
        public function activeOrder()
        {
            
            
            (new OrderPlace())->goCheck();
            $type = input('post.type');
            $pid = input('post.pid');
            $companyid = input('post.companyid');
            $activeinfo = ActiveModel::get($pid);
            $products = ['id' => $activeinfo->id, 'name' => $activeinfo->title, 'money' => $activeinfo->money];
            
            
            if (!$activeinfo) {
                throw new OrderException();
            }
            
            
            $uid = Token::getCurrentUid();
            $order = new OrderService();
            
            $status = $order->placeActive($uid, $products, $type, $companyid);
            
            return json_encode($status);
            
            
        }
        
        
        /**
         * 获取订单详情
         * @param $id
         * @return static
         * @throws OrderException
         */
        public function getDetail($id)
        {
            (new IDMustBePositiveInt())->goCheck();
            $orderDetail = OrderModel::get($id);
            if (!$orderDetail) {
                throw new OrderException();
            }
            return $orderDetail->hidden(['prepay_id']);
        }
        
    
        
    
        
        public function delivery($id)
        {
            (new IDMustBePositiveInt())->goCheck();
            $order = new OrderService();
            $success = $order->delivery($id);
            if ($success) {
                return new SuccessMessage();
            }
        }
    }




















