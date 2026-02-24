<?php
    
    namespace addons\zpwxsys\service;
    
    use addons\zpwxsys\service\Curl as CurlService;
    use think\Exception;
    
    /*
     * 微信授权类
     */
    
    class AccessToken
    {
        private $tokenUrl;
        const TOKEN_CACHED_KEY = 'access';
        const TOKEN_EXPIRE_IN = 7000;
        
        function __construct()
        {
            
            $config = get_addon_config('zpwxsys');
            
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
            
            
            $url = sprintf($url, $config['wxappid'], $config['wxappsecret']);
            
            
            $this->tokenUrl = $url;
        }
        
        // 建议用户规模小时每次直接去微信服务器取最新的token
        // 但微信access_token接口获取是有限制的 2000次/天
        public function get()
        {
            
            
            $token = $this->getFromCache();
            
         
            if (!$token) {
                return $this->getFromWxServer();
            } else {
                return $token;
            }
        }
        
        /*
         * 从缓存里获取token
         */
        private function getFromCache()
        {
            $token = cache(self::TOKEN_CACHED_KEY);
            if ($token && isset($token['access_token'])) {
                return $token['access_token'];
            }
            return null;
        }
        
        private function getFromWxServer()
        {
            $curlService = new CurlService();
            
         
            $token = $curlService->curl_get($this->tokenUrl);
        
            
            $token = json_decode($token, true);
     
            
            if (!$token) {
                // throw new Exception('获取AccessToken异常');
                return false;
            }
            if (!empty($token['errcode'])) {
                throw new Exception($token['errmsg']);
            }
            $this->saveToCache($token);
            return $token['access_token'];
        }
        
        private function saveToCache($token)
        {
            cache(self::TOKEN_CACHED_KEY, $token, self::TOKEN_EXPIRE_IN);
        }
        
        
        public function getRoomList()
        {
            
            $uid = Token::getCurrentUid();
            $access_token = $this->get();
            $curlService = new CurlService();
            $data['start'] = 0;
            $data['limit'] = 10;
            
            $url = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token=" . $access_token;
            
            $result = $curlService->curl_post($url, $data);
            
            
            return json_decode($result);
            
            
        }
        
        
        public function getAdminQrcode($rectid = 0)
        {
            
            $width = 430;
            $data = array();
            
            
            $data['scene'] = "rectid=" . $rectid;
            $data['page'] = "pages/index/index";
            
            //	$post_data = json_encode($data);
            
            $access_token = $this->get();
            
            $curlService = new CurlService();
            
            $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token;
            
            $result = $curlService->curl_post($url, $data);
            
            $filename = time() . 'qrcode.jpg';
            
            $filepath = ROOT_PATH . 'public' . DS . 'uploads/images/' . $filename;
            
            $imgurl = DS . 'uploads/images/' . $filename;
            file_put_contents($filepath, $result);
            
            
            return $imgurl;
            
            
            //	$result=$this->api_notice_increment($url,$post_data,'POST');
            
            
        }
    
    
    
    
        public function getJobQrcode($id)
        {
        
            $width=430;
            $data = array();
        
        
            $data['scene'] = "id=" . $id;
        	$data['page'] = "pages/jobdetail/index";
        
            $access_token = $this->get();
            
   
            $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
    
            $curlService = new CurlService();
            $result = $curlService->curl_post($url, $data);
            
     
        
            $filename = time().'qrcode.jpg';
        
            $filepath=   ROOT_PATH . 'public' . DS . 'uploads/images/'.$filename;
        
            $imgurl =    DS . 'uploads/images/'.$filename;
            
            file_put_contents($filepath, $result);
        
        
            return $imgurl;
        
        
        
        
        
        }
    
        public function getUserQrcode($uid)
        {
        
            $width=430;
            $data = array();
        
        
            $data['scene'] = "uid=" . $uid;
            $data['page'] = "pages/index/index";
        
            $access_token = $this->get();
            
   
            $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token;
    
            $curlService = new CurlService();
            $result = $curlService->curl_post($url, $data);
            
     
        
            $filename = time().'qrcode.jpg';
        
            $filepath=   ROOT_PATH . 'public' . DS . 'uploads/images/'.$filename;
        
            $imgurl =    DS . 'uploads/images/'.$filename;
            
            file_put_contents($filepath, $result);
        
        
            return $imgurl;
        
        
        
        
        
        }
        
        
        public function getQrcode()
        {
            
            $width = 430;
            $data = array();
            
            $uid = Token::getCurrentUid();
            
            $data['scene'] = "uid=" . $uid;
            $data['page'] = "pages/index/index";
            
            //	$post_data = json_encode($data);
            
            $access_token = $this->get();
            
            
            if ($access_token) {
                $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token;
                $curlService = new CurlService();
                $result = $curlService->curl_post($url, $data);
                
                $filename = time() . 'qrcode.jpg';
                
                $filepath = ROOT_PATH . 'public' . DS . 'uploads/images/' . $filename;
                
                $imgurl = DS . 'uploads/images/' . $filename;
                file_put_contents($filepath, $result);
                
                
                return $imgurl;
                
            } else {
                return false;
            }
            
            
            //	$result=$this->api_notice_increment($url,$post_data,'POST');
            
            
        }
        
        //    private function accessIn
    }