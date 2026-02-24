<?php
    
    
    namespace addons\zpwxsys\service;
    
    
    use addons\zpwxsys\model\OrderProduct;
    use addons\zpwxsys\model\Product;
    use addons\zpwxsys\model\Order as OrderModel;
    use addons\zpwxsys\model\Active as ActiveModel;
    use addons\zpwxsys\model\Companyrole as Companyrole;
    use addons\zpwxsys\model\UserAddress;
    use addons\zpwxsys\lib\enum\OrderStatusEnum;
    use addons\zpwxsys\lib\exception\OrderException;
    use addons\zpwxsys\lib\exception\UserException;
    use think\Exception;
    
    /**
     * 订单类
     */
    class Order
    {
        protected $oProducts;
        protected $products;
        protected $uid;
        protected $ordertype;
        protected $companyid = 0;
        
        function __construct()
        {
        }
        
        /**
         * @param int $uid 用户id
         * @param array $oProducts 订单商品列表
         * @return array 订单商品状态
         * @throws Exception
         */
        public function place($uid, $oProducts)
        {
            $this->oProducts = $oProducts;
        //    $this->products = $this->getProductsByOrder($oProducts);
            $this->uid = $uid;
            $status = $this->getOrderStatus();
            if (!$status['pass']) {
                $status['order_id'] = -1;
                return $status;
            }
            
            $orderSnap = $this->snapOrder();
            $status = self::createOrderByTrans($orderSnap);
            $status['pass'] = true;
            return $status;
        }
        
        
        public function placeCompanyrole($uid, $oProducts, $type, $companyid)
        {
            
            $this->products = $this->getCompanyroleByOrder($oProducts);//通过前端提交的产品信息获取数据库最新的产品信息
            $this->uid = $uid;
            $this->ordertype = $type;
            $this->companyid = $companyid;
            //处理订单快照
            $orderSnap = $this->snapCompanyroleOrder();
            $status = self::createRoleOrderByTrans($orderSnap);
            $status['pass'] = true;
            return $status;
            
        }
        
        
        public function placeActive($uid, $oProducts, $type, $companyid)
        {
            
            $this->products = $this->getActiveByOrder($oProducts);//通过前端提交的产品信息获取数据库最新的产品信息
            $this->uid = $uid;
            $this->ordertype = $type;
            $this->companyid = $companyid;
            //处理订单快照
            $orderSnap = $this->snapActiveOrder();
            $status = self::createRoleOrderByTrans($orderSnap);
            $status['pass'] = true;
            return $status;
            
        }
        
        
        public function placeLookrole($uid, $oProducts, $type)
        {
            
            $this->products = $oProducts;
            $this->uid = $uid;
            $this->ordertype = $type;
            
            //处理订单快照
            $orderSnap = $this->snapLookroleOrder();
            
            
            $status = self::createRoleOrderByTrans($orderSnap);
            
            
            $status['pass'] = true;
            return $status;
            
        }
        
        
        private function getCompanyroleByOrder($oProducts)
        {
            $products = Companyrole::where('id', '=', $oProducts['id'])->find();
            return $products;
        }
        
        private function getActiveByOrder($oProducts)
        {
            $products = ActiveModel::where('id', '=', $oProducts['id'])->find();
            return $products;
        }
        
        
        private function snapCompanyroleOrder()
        {
            // status可以单独定义一个类
            $snap = ['orderPrice' => 0, 'pStatus' => [], 'snapName' => $this->products['title']];
            
            $product = $this->products;
            $oProduct = $this->oProducts;
            $pStatus = $this->snapCompanyroleProduct($product);
            $snap['orderPrice'] = $pStatus['money'];
            array_push($snap['pStatus'], $pStatus);
            return $snap;
        }
        
        
        private function snapActiveOrder()
        {
            // status可以单独定义一个类
            $snap = ['orderPrice' => 0, 'pStatus' => [], 'snapName' => $this->products['title']];
            
            $product = $this->products;
            $oProduct = $this->oProducts;
            $pStatus = $this->snapActiveProduct($product);
            $snap['orderPrice'] = $pStatus['money'];
            array_push($snap['pStatus'], $pStatus);
            return $snap;
        }
        
        
        private function snapLookroleOrder()
        {
            // status可以单独定义一个类
            $snap = ['orderPrice' => 0, 'pStatus' => [], 'snapName' => $this->products['title']];
            
            $product = $this->products;
            $oProduct = $this->oProducts;
            $pStatus = $this->snapLookroleProduct($product);
            $snap['orderPrice'] = $pStatus['money'];
            array_push($snap['pStatus'], $pStatus);
            return $snap;
        }
        
        
        private function createRoleOrderByTrans($snap)
        {
            try {
                $orderNo = $this->makeOrderNo();
                $order = new OrderModel();
                $order->user_id = $this->uid;
                $order->type = $this->ordertype;
                $order->companyid = $this->companyid;
                $order->order_no = $orderNo;
                $order->total_price = $snap['orderPrice'];
                $order->snap_name = $snap['snapName'];
                $order->snap_items = json_encode($snap['pStatus']);
                $order->save();
                
                $orderID = $order->id;
                $create_time = $order->create_time;
                /*
                foreach ($this->oProducts as &$p) {
                    $p['order_id'] = $orderID;
                }
                $orderProduct = new OrderProduct();
                
                $orderProduct->saveAll($this->oProducts);
                */
                
                
                return ['order_no' => $orderNo, 'order_id' => $orderID, 'create_time' => $create_time];
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        
        
        /**
         * @param string $orderNo 订单号
         * @return array 订单商品状态
         * @throws Exception
         */
        public function checkOrderStock($orderID)
        {
            //        if (!$orderNo)
            //        {
            //            throw new Exception('没有找到订单号');
            //        }
            
            // 一定要从订单商品表中直接查
            // 不能从商品表中查询订单商品
            // 这将导致被删除的商品无法查询出订单商品来
            $oProducts = OrderProduct::where('order_id', '=', $orderID)->select();
        //   $this->products = $this->getProductsByOrder($oProducts);
            $this->oProducts = $oProducts;
            $status = $this->getOrderStatus();
            return $status;
        }
        
        public function delivery($orderID, $jumpPage = '')
        {
            $order = OrderModel::where('id', '=', $orderID)->find();
            if (!$order) {
                throw new OrderException();
            }
            if ($order->status != OrderStatusEnum::PAID) {
                throw new OrderException(['msg' => '未付款,请先支付', 'errorCode' => 80002, 'code' => 403]);
            }
            $order->status = OrderStatusEnum::DELIVERED;
            $order->save();
//            ->update(['status' => OrderStatusEnum::DELIVERED]);
            $message = new DeliveryMessage();
            return $message->sendDeliveryMessage($order, $jumpPage);
        }
        
        private function getOrderStatus()
        {
            $status = ['pass' => true, 'orderPrice' => 0, 'pStatusArray' => []];
            foreach ($this->oProducts as $oProduct) {
                $pStatus = $this->getProductStatus($oProduct['product_id'], $oProduct['count'], $this->products);
                if (!$pStatus['haveStock']) {
                    $status['pass'] = false;
                }
                $status['orderPrice'] += $pStatus['totalPrice'];
                array_push($status['pStatusArray'], $pStatus);
            }
            return $status;
        }
        
        private function getProductStatus($oPID, $oCount, $products)
        {
            $pIndex = -1;
            $pStatus = ['id' => null, 'haveStock' => false, 'count' => 0, 'name' => '', 'totalPrice' => 0];
            
            for ($i = 0; $i < count($products); $i++) {
                if ($oPID == $products[$i]['id']) {
                    $pIndex = $i;
                }
            }
            
            if ($pIndex == -1) {
                // 客户端传递的productid有可能根本不存在
                throw new OrderException(['msg' => 'id为' . $oPID . '的商品不存在，订单创建失败']);
            } else {
                $product = $products[$pIndex];
                $pStatus['id'] = $product['id'];
                $pStatus['name'] = $product['name'];
                $pStatus['count'] = $oCount;
                $pStatus['totalPrice'] = $product['price'] * $oCount;
                
                if ($product['stock'] - $oCount >= 0) {
                    $pStatus['haveStock'] = true;
                }
            }
            return $pStatus;
        }
        
        
     
        
        private function getUserAddress()
        {
            $userAddress = UserAddress::where('user_id', '=', $this->uid)->find();
            if (!$userAddress) {
                throw new UserException(['msg' => '用户收货地址不存在，下单失败', 'errorCode' => 60001,]);
            }
            return $userAddress->toArray();
        }
        
        // 创建订单时没有预扣除库存量，简化处理
        // 如果预扣除了库存量需要队列支持，且需要使用锁机制
        private function createOrderByTrans($snap)
        {
            try {
                $orderNo = $this->makeOrderNo();
                $order = new OrderModel();
                $order->user_id = $this->uid;
                $order->order_no = $orderNo;
                $order->total_price = $snap['orderPrice'];
                $order->total_count = $snap['totalCount'];
                $order->snap_img = $snap['snapImg'];
                $order->snap_name = $snap['snapName'];
                $order->snap_address = $snap['snapAddress'];
                $order->snap_items = json_encode($snap['pStatus']);
                $order->save();
                
                $orderID = $order->id;
                $create_time = $order->create_time;
                
                foreach ($this->oProducts as &$p) {
                    $p['order_id'] = $orderID;
                }
                $orderProduct = new OrderProduct();
                $orderProduct->saveAll($this->oProducts);
                return ['order_no' => $orderNo, 'order_id' => $orderID, 'create_time' => $create_time];
            } catch (Exception $ex) {
                throw $ex;
            }
        }
        
        
        private function snaproleProduct($product)
        {
            $pStatus = ['id' => null, 'name' => null, 'totalPrice' => 0, 'price' => 0];
            
            // 以服务器价格为准，生成订单
            $pStatus['totalPrice'] = $product['money'];
            $pStatus['name'] = $product['title'];
            $pStatus['id'] = $product['id'];
            $pStatus['price'] = $product['money'];
            return $pStatus;
        }
        
        
        private function snapLookroleProduct($product)
        {
            $pStatus = ['id' => null, 'title' => null, 'looknum' => 0, 'money' => 0];
            
            // 以服务器价格为准，生成订单
            $pStatus['looknum'] = $product['looknum'];
            $pStatus['title'] = $product['title'];
            $pStatus['id'] = $product['id'];
            $pStatus['money'] = $product['money'];
            return $pStatus;
        }
        
        
        private function snapActiveProduct($product)
        {
            $pStatus = ['id' => null, 'title' => null, 'money' => 0];
            
            // 以服务器价格为准，生成订单
            $pStatus['title'] = $product['title'];
            $pStatus['id'] = $product['id'];
            $pStatus['money'] = $product['money'];
            return $pStatus;
        }
        
        
        private function snapCompanyroleProduct($product)
        {
            $pStatus = ['id' => null, 'title' => null, 'jobnum' => 0, 'notenum' => 0, 'topnum' => 0, 'money' => 0];
            
            // 以服务器价格为准，生成订单
            $pStatus['jobnum'] = $product['jobnum'];
            $pStatus['notenum'] = $product['notenum'];
            $pStatus['topnum'] = $product['topnum'];
            $pStatus['title'] = $product['title'];
            $pStatus['id'] = $product['id'];
            $pStatus['money'] = $product['money'];
            return $pStatus;
        }
        
        
        // 预检测并生成订单快照
        private function snapOrder()
        {
            // status可以单独定义一个类
            $snap = ['orderPrice' => 0, 'totalCount' => 0, 'pStatus' => [], 'snapAddress' => json_encode($this->getUserAddress()), 'snapName' => $this->products[0]['name'], 'snapImg' => $this->products[0]['main_img_url'],];
            
            if (count($this->products) > 1) {
                $snap['snapName'] .= '等';
            }
            
            
            for ($i = 0; $i < count($this->products); $i++) {
                $product = $this->products[$i];
                $oProduct = $this->oProducts[$i];
                
                $pStatus = $this->snapProduct($product, $oProduct['count']);
                $snap['orderPrice'] += $pStatus['totalPrice'];
                $snap['totalCount'] += $pStatus['count'];
                array_push($snap['pStatus'], $pStatus);
            }
            return $snap;
        }
        
        // 单个商品库存检测
        private function snapProduct($product, $oCount)
        {
            $pStatus = ['id' => null, 'name' => null, 'main_img_url' => null, 'count' => $oCount, 'totalPrice' => 0, 'price' => 0];
            
            $pStatus['counts'] = $oCount;
            // 以服务器价格为准，生成订单
            $pStatus['totalPrice'] = $oCount * $product['price'];
            $pStatus['name'] = $product['name'];
            $pStatus['id'] = $product['id'];
            $pStatus['main_img_url'] = $product['main_img_url'];
            $pStatus['price'] = $product['price'];
            return $pStatus;
        }
        
        public static function makeOrderNo()
        {
            $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
            $orderSn = $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            return $orderSn;
        }
    }