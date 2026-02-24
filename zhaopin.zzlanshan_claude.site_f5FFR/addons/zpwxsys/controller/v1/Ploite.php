<?php
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    
    use addons\zpwxsys\model\Ploite as PloiteModel;
 
    use addons\zpwxsys\service\Token;

    
    class Ploite extends BaseController
    {
        
        public function savePloite()
        {
    
            if(request()->isPost()){
        
        
                $param = input('post.');
                
                $param['uid'] = Token::getCurrentUid();
        
                $param['createtime'] = time();
                
                $param['enabled'] = 0 ;
                
                $PloiteModel = new PloiteModel();
        
                $data = $PloiteModel->insertPloite($param);
        
                return $data;
            }
    
    
        }
        
    }