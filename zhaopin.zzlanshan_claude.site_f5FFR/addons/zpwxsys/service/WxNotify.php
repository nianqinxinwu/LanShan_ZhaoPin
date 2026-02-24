<?php
    
    
    namespace addons\zpwxsys\service;
    

    use addons\zpwxsys\model\Order;
    use addons\zpwxsys\model\Product;
    use addons\zpwxsys\service\LookRoleRecord as LookRoleRecordService;
    use addons\zpwxsys\service\CompanyRecord as CompanyRecordService;
    use addons\zpwxsys\model\Activerecord as ActiverecordModel;
    use addons\zpwxsys\lib\enum\OrderStatusEnum;
    use addons\zpwxsys\library\WxPay\WxPayNotify;
    use think\Db;
    use think\Exception;
    use think\Log;


    
    
    class WxNotify extends WxPayNotify

    {
   
        
        public function NotifyProcess($data, &$msg)
        {

            
            
            if ($data['result_code'] == 'SUCCESS') {
                $orderNo = $data['out_trade_no'];
                Db::startTrans();
                try {
                    $order = Order::where('order_no', '=', $orderNo)->lock(true)->find();
                    
                    
                    
                    if ($order->status == 1) {
                        
                        
                        Log::record('qqqqqqqqq', 'error');
                        
                        $this->updateOrderStatus($order->id, true);
                        
                        
                        if ($order->type == 'lookrole') {
                            
                            $lookroleinfo = $order->snap_items;
                            
                            $looknum = $lookroleinfo[0]->looknum;
                            
                            $lookrolerecodservice = new LookRoleRecordService($order->user_id, $looknum);
                            
                            $lookrolerecodservice->SetRecord();
                            
                            
                        }
                        
                        if ($order->type == 'companyrole') {
                            
                            $companyroleinfo = $order->snap_items;
                            
                            $companyid = $order->companyid;
                            
                            if ($companyid > 0) {
                                $CompanyRecordService = new CompanyRecordService($companyid);
                                $CompanyRecordService->notenum = $companyroleinfo[0]->notenum;
                                $CompanyRecordService->jobnum = $companyroleinfo[0]->jobnum;
                                $CompanyRecordService->topnum = $companyroleinfo[0]->topnum;
                                $CompanyRecordService->SetRecord();
                                
                                
                            }
                            
                            
                        }
                        
                        
                        if ($order->type == 'companyactive') {
                            $map['companyid'] = $order->companyid;
                            
                            $activerecordinfo = ActiverecordModel::where($map)->find();
                            
                            if (!$activerecordinfo) {
                                
                                
                                $activeinfo = $order->snap_items;
                                
                                
                                $ActiverecordModel = new ActiverecordModel();
                                
                                $ActiverecordModel->companyid = $order->companyid;
                                
                                $ActiverecordModel->aid = $activeinfo[0]->id;
                                
                                
                                $ActiverecordModel->createtime = time();
                                
                                
                                $ActiverecordModel->save();
                                
                                
                            }
                            
                            
                        }
                        
                        
                    }
                    Db::commit();
                } catch (Exception $ex) {
                    Db::rollback();
                    Log::error($ex);
                    // 如果出现异常，向微信返回false，请求重新发送通知
                    return false;
                }
            }
            return true;
        }
        
        
        private function reduceStock($status)
        {
            foreach ($status['pStatusArray'] as $singlePStatus) {
                Product::where('id', '=', $singlePStatus['id'])->setDec('stock', $singlePStatus['count']);
            }
        }
        
        private function updateOrderStatus($orderID, $success)
        {
            $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
            Order::where('id', '=', $orderID)->update(['status' => $status]);
        }
    }