<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\service\Token;
    
    class Fxsys extends BaseController
    {
        
        
        public function fxBinduser()
        {
            
            $tid = input('post.tid');
            
            $uid = Token::getCurrentUid();
            
            $map['id'] = $uid;
            
            
            $userinfo = UserModel::getByUserWhere($map);
            
            if($userinfo)
            {
            
            $data = [];
            
            $data['id'] = $uid;
            
            
            if ($userinfo['tid'] == 0) {
                
                
                $map1['id'] = $tid;//查直接上级信息
                
                $userinfo1 = UserModel::getByUserWhere($map1);
                
                if ($userinfo1['tid'] > 0) {
                    
                    $data['fxuid1'] = $userinfo1['tid'];
                    
                    if ($userinfo1['fxuid2'] > 0) {
                        
                        $data['fxuid2'] = $userinfo1['fxuid2'];
                    }
                    
                }
                
                $data['tid'] = $tid;
                
                
                $user = new UserModel();
                
                
                $user->updateUser($data);
                
            }
                 
                 $data = array('status' => 0,'msg'=>'请求数据正常');
            }else{
                
                 $data = array('status' => 1,'msg'=>'请求数据不存在');
            }
            
     
            
            
            return json_encode($data);
            
            
        }
        
        
        public function rectBinduser()
        {
            
            $rectid = input('post.rectid');
            
            
            $uid = Token::getCurrentUid();
            
            $map['id'] = $uid;
            
            $data['id'] = $uid;
            
            $userinfo = UserModel::getByUserWhere($map);
            
            if($userinfo)
            {
            
            
            if ($userinfo['rectid'] == 0) {
                
                
                $data['rectid'] = $rectid;
                
                $user = new UserModel();
                
                
                $user->updateUser($data);
                
            }
        
            
            
                $data = array('status' => 0,'msg'=>'请求数据正常');
            }else{
                
                 $data = array('status' => 1,'msg'=>'请求数据不存在');
            }
            
            
            return json_encode($data);
            
            
        }
        
        
    }