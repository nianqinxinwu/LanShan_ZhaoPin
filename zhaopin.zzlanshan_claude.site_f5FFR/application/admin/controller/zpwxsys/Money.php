<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Money as MoneyModel;
    use app\admin\model\zpwxsys\User as WxuserModel;
    /**
     *
     *
     * 分销佣金记录
     */
    class Money extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','ischeck'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Money;
        }
        
        
        public function index()
        {
            //当前是否为关联查询
            $this->relationSearch = true;
            //设置过滤方法
            $this->request->filter(['strip_tags', 'trim']);
            
            
            if ($this->request->isAjax()) {
                //如果发送的来源是Selectpage，则转发到Selectpage
                if ($this->request->request('keyField')) {
                    return $this->selectpage();
                }
                
                $map = [];
                
              //  $map['n.tid'] = array('gt',0);
              
                $map['m.type'] = -1;
                
                $field = input('field');//字段
                $order = input('order');//排序方式
                if ($field && $order) {
                    $od = $field . " " . $order;
                } else {
                    $od = "m.createtime desc";
                    
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $filter = $this->request->get("filter", '');
                $filter = (array)json_decode($filter, true);
                
                if(count($filter)>0)
                {
                
                
                if (array_key_exists("nickname",$filter))
                    {
                            
                        $map['m.nickname'] = ['like',"%" . $filter['nickname'] . "%"];
                    }
    
           
                
                }
                
        
                $Money = new MoneyModel();
                $count = $Money->getListCount($map);
                $Nowpage = $offset / $limit + 1;
           
                $list = $Money->getListByWhere($map, $Nowpage, $limit, $od);
                
                if($list)
                {
                    foreach ($list as $k=>$v) {
                        

                        $list[$k]['orderinfo'] =  $v['type'] == -1 ? '申请提现' : '消费记录';
                        
                        $list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                        
                        $list[$k]['nickname'] = $v['nickname'].'/'.$v['tel'];
                        
                        if($v['type'] == -1)
                        {
                            if($v['paytype'] == 0 )
                            {
                                
                                 $list[$k]['paystatus'] = '提现未打款';
                                 
                            }else{
                                
                                 $list[$k]['paystatus'] = '提现已打款';
                                
                            }
                            
                        }else{
                            
                             $list[$k]['paystatus'] = '账单记录';
                        }
                        
                        
                        
                        
                        
                    }
                }
                
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
      public function  setmoney($id = null)
        {
                 $MoneyModel = new MoneyModel();
            if ($this->request->isPost()) {
                
                $params = $this->request->post("row/a");
                
                $id = $params['id'];
               
                $paytype = $params['paytype'];
                
                
               $moneyinfo  =  MoneyModel::getOne(array('id'=>$id));
                
            
                
                 if ($moneyinfo) {
                     
                        if($moneyinfo['paytype'] == 1)
                        {
                            
                             $this->error(__('已经打款,请勿操作'));
                            
                        }else{
                            
                            
                              $left_money = abs($moneyinfo['money']);
        
                            $uid = $moneyinfo['uid'];
                            
                            $userinfo = WxuserModel::getOne(array('id'=>$uid));
                            
                            $openid = $userinfo['openid'];
                            
                            
                             $withdrawApply = ['batch_no'=>'mfczp'.time(),'left_money'=>$left_money,'sn'=>'sndd'.time(),'real_name'=>''];
                             
                             $config = get_addon_config('zpwxsys');
                            
                            $config = ['app_id'=>$config['wxappid'],'cert_path'=>$config['cert'],'key_path'=>$config['key'],'mch_id'=>$config['couponsn']];
                            
                            $userAuth = ['openid'=>$openid];
                            
                            $paydata =  self::transfer($withdrawApply,$userAuth,$config);
                            
                              if($paydata['error'] ==0 )
                                {
                                    
                                    
                                    $MoneyModel->updateMoney($params);
                                    
                        
                                    $this->success();
                                    
                                }else{
                                    
                                    $this->error(__('商家转账接口失败'));
                                }
                        }
                        }else{
                            
                             $this->error(__('操作失败'));
                        }
                
            
            }
            
            $map = [];
            $map['id'] = $id;
       
            $moneyinfo = $MoneyModel->getOne($map);
            
            if($moneyinfo['paytype'] == 1)
            {
                
                       $this->error(__('已经打款,请勿操作'));
            }
            
            $this->view->assign('id',$id);
            $this->view->assign('moneyinfo',$moneyinfo);
            
      
            
            
            
            return $this->view->fetch();
            
            
            
            
        }
    
    /**
     * 处理打款接口
     */
     
     /**
     * @notes 商家转账到零钱
     */
    public static function transfer($withdrawApply,$userAuth,$config)
    {
        //请求URL
        $url = 'https://api.mch.weixin.qq.com/v3/transfer/batches';
        //请求方式
        $http_method = 'POST';
        //请求参数
        $data = [
            'appid' => $config['app_id'],//申请商户号的appid或商户号绑定的appid（企业号corpid即为此appid）
            'out_batch_no' => $withdrawApply['batch_no'],//商户系统内部的商家批次单号，要求此参数只能由数字、大小写字母组成，在商户系统内部唯一
            'batch_name' => '提现至微信零钱',//该笔批量转账的名称
            'batch_remark' => '提现至微信零钱',//转账说明，UTF8编码，最多允许32个字符
            'total_amount' => $withdrawApply['left_money'] * 100,//转账金额单位为“分”。转账总金额必须与批次内所有明细转账金额之和保持一致，否则无法发起转账操作
            'total_num' => 1,//一个转账批次单最多发起三千笔转账。转账总笔数必须与批次内所有明细之和保持一致，否则无法发起转账操作
            'transfer_detail_list' => [
                [//发起批量转账的明细列表，最多三千笔
                    'out_detail_no' => $withdrawApply['sn'],//商户系统内部区分转账批次单下不同转账明细单的唯一标识，要求此参数只能由数字、大小写字母组成
                    'transfer_amount' => $withdrawApply['left_money'] * 100,//转账金额单位为分
                    'transfer_remark' => '提现至微信零钱',//单条转账备注（微信用户会收到该备注），UTF8编码，最多允许32个字符
                    'openid' => $userAuth['openid'],//openid是微信用户在公众号appid下的唯一用户标识（appid不同，则获取到的openid就不同），可用于永久标记一个用户
                ]]
        ];
        if ($withdrawApply['left_money'] >= 2000) {
            if (empty($withdrawApply['real_name'])) {
                throw new \Exception('转账金额 >= 2000元，收款用户真实姓名必填');
            }
            $data['transfer_detail_list'][0]['user_name'] = self::getEncrypt($withdrawApply['real_name'],$config);
        }
 
        $token  = self::token_get($url,$http_method,$data,$config);//获取token
        $result = self::https_request($url,json_encode($data),$token);//发送请求
        $result_arr = json_decode($result,true);
        
       // var_dump($result_arr);
        //exit;
 
        if(!isset($result_arr['create_time'])) {//批次受理失败
          //  throw new \Exception($result_arr['message']);
          
          $msg = $result_arr['message'];
          
          $result_data  = ['error'=>1, 'msg'=>$msg];
        }else{
            
              $result_data  = ['error'=>0, 'msg'=>'转账成功'];
        }
        
        return $result_data;
        //批次受理成功，进行操作
 
 
    }
 
    /**
     * @notes 获取签名
     */
    public static function token_get($url,$http_method,$data,$config)
    {
        $timestamp   = time();//请求时间戳
        $url_parts   = parse_url($url);//获取请求的绝对URL
        $nonce       = $timestamp.rand('10000','99999');//请求随机串
        $body        = empty($data) ? '' : json_encode((object)$data);//请求报文主体
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ];
 
        $apiclient_cert_arr = openssl_x509_parse(file_get_contents($config['cert_path'],false, stream_context_create($stream_opts)));
        $serial_no          = $apiclient_cert_arr['serialNumberHex'];//证书序列号
        $mch_private_key    = file_get_contents($config['key_path'],false, stream_context_create($stream_opts));//密钥
        $merchant_id = $config['mch_id'];//商户id
        $canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
        $message = $http_method."\n".
            $canonical_url."\n".
            $timestamp."\n".
            $nonce."\n".
            $body."\n";
        openssl_sign($message, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);//签名
        $schema = 'WECHATPAY2-SHA256-RSA2048';
        $token = sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $merchant_id, $nonce, $timestamp, $serial_no, $sign);//微信返回token
        return $schema.' '.$token;
    }
 
    /**
     * @notes 发送请求
     */
    public static function https_request($url,$data,$token)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, (string)$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //添加请求头
        $headers = [
            'Authorization:'.$token,
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
        ];
        if(!empty($headers)){
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
 
    /**
     * @notes 敏感信息加解密
     */
    public static function getEncrypt($str,$config)
    {
        //$str是待加密字符串
        $public_key = file_get_contents($config['cert_path']);
        $encrypted = '';
        if (openssl_public_encrypt($str, $encrypted, $public_key, OPENSSL_PKCS1_OAEP_PADDING)) {
            //base64编码
            $sign = base64_encode($encrypted);
        } else {
            throw new \Exception('encrypt failed');
        }
        return $sign;
    }
 
    /**
     * @notes 商家明细单号查询
     */
    public static function detailsQuery($withdrawApply,$config)
    {
        //请求URL
        $url = 'https://api.mch.weixin.qq.com/v3/transfer/batches/out-batch-no/'.$withdrawApply['batch_no'].'/details/out-detail-no/'.$withdrawApply['sn'];
        //请求方式
        $http_method = 'GET';
        //请求参数
        $data = [];
        $token  = self::token_get($url,$http_method,$data,$config);//获取token
        $result = self::https_request($url,$data,$token);//发送请求
        $result_arr = json_decode($result,true);
        
        var_dump($result_arr);
        exit;
        return $result_arr;
    }
     
 
        
    }
