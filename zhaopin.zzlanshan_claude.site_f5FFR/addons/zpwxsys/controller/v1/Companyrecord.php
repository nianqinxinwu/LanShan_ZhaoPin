<?php
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Lookcompanyrecord as LookcompanyrecordModel;
    use addons\zpwxsys\model\Note as NoteModel;

    use addons\zpwxsys\service\CompanyRecord as CompanyRecordService;
    use addons\zpwxsys\service\Token;
    
    class Companyrecord extends BaseController
    {
    
        /*
* 检测是否登录
*/
        public function  isLogin(){
        
            $ctoken = input('post.ctoken');
        
        
            $valid = Token::verifyToken($ctoken);
            if($valid)
            {
                $companyid = Token::getCurrentCid($ctoken);
            
                if($companyid)
                    $data = array('error' => 0, 'msg' => '正常','companyid'=>$companyid);
                else
                    $data = array('error' => 1, 'msg' => '数据异常');
            
            }else{
            
                $data = array('error' => 2, 'msg' => 'Token异常');
            }
        
            return $data;
        }
        
        
        public function dealLookRecord()
        {
            
            
            $msg = $this->isLogin();
            
            if($msg['error'] == 0) {
    
                $noteid = input('post.noteid');
                
                
                 $noteinfo = NoteModel::getNote($noteid);
                 
                 if(!$noteinfo)
                 {
                     
                     
                    $data = array('error' => 1, 'msg' => '求职者数据不存在');
                    return json_encode($data);
                    
                 }
                
                
                $companyid =$msg['companyid'];
                $rod = 'create_time desc';
    
                $rmap['uid'] = Token::getCurrentUid();
                $uid = $rmap['uid'];
    
    
                $CompanyRecordService = new CompanyRecordService($companyid);
    
    
                $CompanyRecordList = $CompanyRecordService->GetFirstRecord();
    
    
                $totallooknum = 0;
    
                $totalnotenum = 0;
    
                if ($CompanyRecordList) {
                    $totalnotenum = $CompanyRecordList[0]['totalnotenum'];
        
                }
    
    
                if ($totalnotenum > 0) {
        
                 
        
        
                    $LookrecordModel = new LookcompanyrecordModel();
        
                    $map['companyid'] = $companyid;
        
                    $map['noteid'] = $noteid;
        
        
                    $is_exist = $LookrecordModel->where($map)->find();
                    
                    
        
                    if (!$is_exist) {
                        
                           $CompanyRecordService->notenum = -1;
        
                    $CompanyRecordService->SetNoteNumRecord();
            
                        $LookrecordModel->companyid = $companyid;
            
                        $LookrecordModel->noteid = $noteid;
                        $LookrecordModel->createtime = time();
            
                        $LookrecordModel->status = 0;
            
                        $LookrecordModel->save();
                        
                        
                        $data = array('error' => 0, 'msg' => '增加成功','tel'=>$noteinfo['tel']
            
                        );
                        
                        
            
                    } else {
                

                        
            
                        $data = array('error' => 0, 'msg' => '已水费过','tel'=>$noteinfo['tel']
            
                        );
            
            
                        //数据已经存在
                    }
        
        
                } else {
        
        
                    $data = array('error' => 1, 'msg' => '余额不足'
        
                    );
                }
    
    
                return json_encode($data);
    
            }else{
                return json_encode($msg);
            }
            
            
        }
        
        
    }