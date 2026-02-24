<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\service\AppToken;
    use addons\zpwxsys\service\UserToken;
    use addons\zpwxsys\service\Token as TokenService;
    use addons\zpwxsys\validate\TokenGet;
    use addons\zpwxsys\lib\exception\ParameterException;

    
    /**
     * 获取令牌，相当于登录
     */
    class Token
    {
        /**
         * 用户获取令牌（登陆）
         * @url /token
         * @POST code
         * @note 虽然查询应该使用get，但为了稍微增强安全性，所以使用POST
         */
        public function getToken()
        {
            (new TokenGet())->goCheck();
            $param = input('post.');
            $code = $param['code'];
            $wx = new UserToken($code);
            $token = $wx->get();
            
            $data = array('token' => $token);
            
            return json_encode($data);
            
        }
        
     
        
        
        /**
         * 第三方应用获取令牌
         * @url /app_token?
         * @POST ac=:ac se=:secret
         */
        public function getAppToken($ac = '', $se = '')
        {
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Access-Control-Allow-Methods: GET');
         
            $app = new AppToken();
            $token = $app->get($ac, $se);
            return ['token' => $token];
        }
        
        public function verifyToken()
        {
            
            
            $token = input('post.token');
            
            if (!$token) {
                throw new ParameterException(['token不允许为空']);
            }
            $valid = TokenService::verifyToken($token);
            
            $data = array('isValid' => $valid);
            
            return json_encode($data);
            
            
        }
        
        
        
    }