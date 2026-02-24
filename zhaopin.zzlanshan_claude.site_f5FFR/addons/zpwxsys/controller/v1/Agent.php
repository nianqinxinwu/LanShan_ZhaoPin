<?php


namespace addons\zpwxsys\controller\v1;


use addons\zpwxsys\controller\BaseController;
use addons\zpwxsys\model\Agent as AgentModel;
use addons\zpwxsys\model\Note as NoteModel;
use addons\zpwxsys\model\Company as CompanyModel;
use addons\zpwxsys\model\User as UserModel;
use addons\zpwxsys\model\Fxrecord as FxrecordModel;
use addons\zpwxsys\model\Money as MoneyModel;
use addons\zpwxsys\validate\IDMustBePositiveInt;
use app\lib\exception\MissException;
use addons\zpwxsys\service\Token;
use addons\zpwxsys\service\AccessToken;

class Agent extends BaseController
{
   
  
  
     public function  myAgentTeam()
       {
           $uid = Token::getCurrentUid();
       
           $map_one['u.tid'] = $uid;
           
        
           
           $UserModel = new UserModel();
           //一级团队
           $agentlevel_one = $UserModel->getLevelAgentList($map_one);
           
       
           
           //二级团队
           $map_two['u.fxuid1'] = $uid;
           $agentlevel_two = $UserModel->getLevelAgentList($map_two);
           
    
           
           
           $data = array('error'=>0, 'agentlevel_one'=>$agentlevel_one,'agentlevel_two'=>$agentlevel_two);
           
           return json_encode($data);
           
          
           
       }
   public function agentInit(){
       
       $uid = Token::getCurrentUid();
   
        $map['uid'] = $uid;
        $agentinfo =  AgentModel::getAgentByuid($map);
        
         $lastmoney =  MoneyModel::getLastOne($map);//最新的余额
         
              
          if($lastmoney)
               {
                   
                  $totalmoney = $lastmoney[0]['totalmoney'];
                  
                  $usermoney = $totalmoney;
                   
               }else{
                   
                  $totalmoney = '0.00';
                  
                  $usermoney =  0;
                  
               }
        
         $data = array('status'=>0,'agentinfo'=>$agentinfo,'totalmoney'=>$totalmoney,'usermoney'=>$usermoney);
         
         
        
       return json_encode($data);
       
   }
    
    public function checkAgent()
    {
        $uid = Token::getCurrentUid();
   
        $map['uid'] = $uid;
        $agentinfo =  AgentModel::getAgentByuid($map);
        
        if(!$agentinfo)
        {
            $data = array('status'=>0,'msg'=>'未入驻');
            
        }else{
            if($agentinfo['status'] == 0)
            {
                $data = array('status'=>2,'msg'=>'您提交信息正在审核');
                
            }else{
                
                
                 $data = array('status'=>1,'msg'=>'已经成功入驻');
            }
            
           
        }
       
        
        
        return json_encode($data);
        
        
        
    }
    
    public function Getfxrecord()
    {
        
        
        if(request()->isPost()){

            $uid = Token::getCurrentUid();
   
            
            $fxrecordlist =  FxrecordModel::getList($uid);
            
            if($fxrecordlist)
            {
                
                foreach ($fxrecordlist as $k=>$v)
                {
                    
                    if($v['type'] == 0)
                    {
                        $noteinfo = NoteModel::getNote($v['orderid']);
                        
                        $fxrecordlist[$k]['tjname'] = $noteinfo['name'];
                        
                    }else{
                        
                        $companyinfo = CompanyModel::getCompany($v['orderid']);
                        
                        $fxrecordlist[$k]['tjname'] = $companyinfo['companyname'];
                    }
                    
                    
                      $fxrecordlist[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                    
                }
            }
          
           
                
                
           $data = json_encode(array('status'=>0,'fxrecordlist'=>$fxrecordlist));
                
          
            
            return $data;
        }
        
        
    }
    
    
    public function agentFxrecord()
    {
        
        if(request()->isPost()){
            extract(input());
            $param = input('post.');
            
            $param['uid'] = Token::getCurrentUid();
            
            $userdata['tid'] = $param['uid'];
            
            $map['uid'] = $param['uid'];
            
            $agentinfo =  AgentModel::getAgentByuid($map);
          
            $agent = new AgentModel();
            
            if($agentinfo)
            {
                $od = "create_time desc";
    
                $Nowpage = input('post.page');
                if ($Nowpage) $Nowpage = $Nowpage; else
                    $Nowpage = 1;
    
                $limits = $Nowpage * 10;
                $Nowpage = 1;
    
                $userlist = UserModel::getByUserlistWhere($userdata, $Nowpage, $limits, $od);
                
                if($userlist)
                {
                    
                     foreach ($userlist as $k=>$v)
                        {
                           
                          $noteinfo =  NoteModel::getNoteByuid($v['id']);
                          
                          if($noteinfo)
                          {
                              
                              $userlist[$k]['status'] = '简历已填写';
                              
                          }else{
                              
                              $userlist[$k]['status'] = '简历未填写';
                              
                          }
                           
                           
                        }
                    
                    
                }
                
                
                
                $data = json_encode(array('status'=>0,'userlist'=>$userlist));
                
            }else{
                
               $data = json_encode(array('status'=>1,'msg'=>'您还不是经纪人'));
                
            }
            
            return $data;
        }
        
        
    }
    
    
    
      public function saveAgent()
    {
         if(request()->isPost()){
            extract(input());
            $param = input('post.');
            
            $param['uid'] = Token::getCurrentUid();
            
            $userdata['id'] = $param['uid'];
            
            
            
            
            $map['uid'] = $param['uid'];
            
            $agentinfo =  AgentModel::getAgentByuid($map);
          
            $agent = new AgentModel();
            
            if(!$agentinfo)
            {
                
                $userinfo = UserModel::getByUserWhere($userdata);
                
                
                
                
                $AccessToken = new AccessToken();
        
                $qrcode = $AccessToken->getQrcode();
                
                $param['qrcode'] = $qrcode;
                
                $param['tid'] = $userinfo['tid'];
                $param['fxuid1'] = $userinfo['fxuid1'];
                $param['fxuid2'] = $userinfo['fxuid2'];
                $param['createtime'] = time();
                
                $param['status'] = 0;
                
                $data = $agent->insertAgent($param);
                
            }else{
                
               $data = json_encode(array('status'=>1,'msg'=>'您的信息已提交过'));
                
            }
            
            return $data;
        }
        
        
    }
    
    


 
}