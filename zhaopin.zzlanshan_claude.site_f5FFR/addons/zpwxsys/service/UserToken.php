<?php
    
    namespace addons\zpwxsys\service;
    
    use addons\zpwxsys\model\User;
    use addons\zpwxsys\lib\enum\ScopeEnum;
    use addons\zpwxsys\lib\exception\TokenException;
    use addons\zpwxsys\lib\exception\WeChatException;
    use addons\zpwxsys\model\Lookrole as LookroleModel;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\service\AccessToken as AccessToken;
    use addons\zpwxsys\service\LookRoleRecord as LookRoleRecordService;
    
    use addons\zpwxsys\service\Curl as CurlService;
    
    use think\Exception;
    
    /**
     * 微信登录
     * 如果担心频繁被恶意调用，请限制ip
     * 以及访问频率
     */
    class UserToken extends Token
    {
        protected $code;
        protected $wxLoginUrl;
        protected $wxAppID;
        protected $wxAppSecret;
        protected $curlService;
        
        
        /*
        
            // 微信获取access_token的url地址
        'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
        "grant_type=client_credential&appid=%s&secret=%s",
        */
        
        function __construct($code)
        {
            $this->code = $code;
            
            $config = get_addon_config('zpwxsys');
            $this->wxAppID = $config['wxappid'];
            $this->wxAppSecret = $config['wxappsecret'];
            $login_url = "https://api.weixin.qq.com/sns/jscode2session?" . "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code";
            $this->curlService = new CurlService();
            $this->wxLoginUrl = sprintf($login_url, $this->wxAppID, $this->wxAppSecret, $this->code);
        }
        
        
        /**
         * 登陆
         * 思路1：每次调用登录接口都去微信刷新一次session_key，生成新的Token，不删除久的Token
         * 思路2：检查Token有没有过期，没有过期则直接返回当前Token
         * 思路3：重新去微信刷新session_key并删除当前Token，返回新的Token
         */
        public function get()
        {
            $result = $this->curlService->curl_get($this->wxLoginUrl);
            
            // 注意json_decode的第一个参数true
            // 这将使字符串被转化为数组而非对象
            
            $wxResult = json_decode($result, true);
            if (empty($wxResult)) {
                // 为什么以empty判断是否错误，这是根据微信返回
                // 规则摸索出来的
                // 这种情况通常是由于传入不合法的code
                throw new Exception('获取session_key及openID时异常，微信内部错误');
            } else {
                // 建议用明确的变量来表示是否成功
                // 微信服务器并不会将错误标记为400，无论成功还是失败都标记成200
                // 这样非常不好判断，只能使用errcode是否存在来判断
                $loginFail = array_key_exists('errcode', $wxResult);
                
                
                if ($loginFail) {
                    
                    
                    //   $this->processLoginError($wxResult);
                    
                    return false;
                    
                } else {
                    return $this->grantToken($wxResult);
                }
            }
        }
        
        
        // 判断是否重复获取
        private function duplicateFetch()
        {
            //TODO:目前无法简单的判断是否重复获取，还是需要去微信服务器去openid
            //TODO: 这有可能导致失效行为
        }
        
        // 处理微信登陆异常
        // 那些异常应该返回客户端，那些异常不应该返回客户端
        // 需要认真思考
        private function processLoginError($wxResult)
        {
            
            
            throw new WeChatException(['msg' => $wxResult['errmsg'], 'errorCode' => $wxResult['errcode']]);
        }
        
        // 写入缓存
        private function saveToCache($wxResult)
        {
            $key = self::generateToken($wxResult);
            $value = json_encode($wxResult);
            $expire_in = 7200;
            $result = cache($key, $value, $expire_in);
            
            if (!$result) {
                throw new TokenException(['msg' => '服务器缓存异常', 'errorCode' => 10005]);
            }
            return $key;
        }
        
        // 颁发令牌
        // 只要调用登陆就颁发新令牌
        // 但旧的令牌依然可以使用
        // 所以通常令牌的有效时间比较短
        // 目前微信的express_in时间是7200秒
        // 在不设置刷新令牌（refresh_token）的情况下
        // 只能延迟自有token的过期时间超过7200秒（目前还无法确定，在express_in时间到期后
        // 还能否进行微信支付
        // 没有刷新令牌会有一个问题，就是用户的操作有可能会被突然中断
        private function grantToken($wxResult)
        {
            // 此处生成令牌使用的是TP5自带的令牌
            // 如果想要更加安全可以考虑自己生成更复杂的令牌
            // 比如使用JWT并加入盐，如果不加入盐有一定的几率伪造令牌
            //        $token = Request::instance()->token('token', 'md5');
            $openid = $wxResult['openid'];
            
            $session_key = $wxResult['session_key'];
            
            
            cache('session_key', $session_key, 7200);
            $user = User::getByOpenID($openid);
            if (!$user)
                // 借助微信的openid作为用户标识
                // 但在系统中的相关查询还是使用自己的uid
            {
                $uid = $this->newUser($openid);
            } else {
                $uid = $user->id;
            }
            $cachedValue = $this->prepareCachedValue($wxResult, $uid);
            $token = $this->saveToCache($cachedValue);
            return $token;
        }
        
        
        private function prepareCachedValue($wxResult, $uid)
        {
            $cachedValue = $wxResult;
            $cachedValue['uid'] = $uid;
            $cachedValue['scope'] = ScopeEnum::User;
            return $cachedValue;
        }
        
        // 创建新用户
        private function newUser($openid)
        {
            // 有可能会有异常，如果没有特别处理
            // 这里不需要try——catch
            // 全局异常处理会记录日志
            // 并且这样的异常属于服务器异常
            // 也不应该定义BaseException返回到客户端
            $user = User::create(['openid' => $openid]);
    
    
            $UserModel = new UserModel();
    
            $AccessToken = new AccessToken();
    
            $qrcode = $AccessToken->getUserQrcode($user->id);
            
    
            $param['id'] = $user->id;
            $param['qrcode'] = $qrcode;
            
    
            $UserModel->updateUser($param);
            
            
            if ($user->id) {
                $map['isinit'] = 1;
                $map['enabled'] = 0;
                $lookrole = LookroleModel::getLookrole($map);
                
                if ($lookrole) {
                    
                    
                    $looknum = $lookrole['looknum'];
                    
                    $LookRoleRecordService = new LookRoleRecordService($user->id, $looknum);
                    
                    $LookRoleRecordService->mark = '新用户赠送';
                    
                    
                    $LookRoleRecordService->SetRecord();
                    
                    
                }
                
            }
            
            
            return $user->id;
        }
    }
