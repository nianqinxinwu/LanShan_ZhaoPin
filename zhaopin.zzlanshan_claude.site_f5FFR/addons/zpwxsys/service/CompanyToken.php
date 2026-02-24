<?php
    
    namespace addons\zpwxsys\service;
    use addons\zpwxsys\lib\enum\ScopeEnum;
    use addons\zpwxsys\lib\exception\TokenException;
    
    class CompanyToken extends Token
    {
        public function get($companyaccount)
        {
                
                $scope = ScopeEnum::User;
                $companyid = $companyaccount['companyid'];
                $values = ['cscope' => $scope, 'companyid' => $companyid];
                $token = $this->saveToCache($values);
                return $token;
            
        }
        
        private function saveToCache($values)
        {
            $wxResult['session_key'] = time();
            $token = self::generateToken($wxResult);
            $expire_in = 7200;
            $result = cache($token, json_encode($values), $expire_in);
            if (!$result) {
                throw new TokenException(['msg' => '服务器缓存异常', 'errorCode' => 10005]);
            }
            return $token;
        }
    }