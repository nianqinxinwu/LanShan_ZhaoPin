<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Lookrecord as LookrecordModel;
    use addons\zpwxsys\service\LookRoleRecord as LookRoleRecordService;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;
    
    class Lookrolerecord extends BaseController
    {
       
        public function dealLookroleRecord()
        {
            
            
            $noteid = input('post.noteid');
            $rod = 'create_time desc';
            
            $rmap['uid'] = Token::getCurrentUid();
            $uid = $rmap['uid'];
            
            
            $lookrolerecodservice = new LookRoleRecordService($rmap['uid'], 0);
            
            
            $lookrolerecordlist = $lookrolerecodservice->GetFirstRecord();
            
            
            $totallooknum = 0;
            
            if ($lookrolerecordlist) {
                $totallooknum = $lookrolerecordlist[0]['totallooknum'];
                
            }
            
            
            if ($totallooknum > 0) {
                
                $lookrolerecodservice->looknum = -1;
                
                $lookrolerecodservice->SetRecord();
                
                
                $LookrecordModel = new LookrecordModel();
                
                $map['uid'] = $uid;
                
                $map['jobid'] = $noteid;
                
                
                $is_exist = $LookrecordModel->where($map)->find();
                
                if (!$is_exist) {
                    
                    $LookrecordModel->uid = $uid;
                    
                    $LookrecordModel->jobid = $noteid;
                    
                    $LookrecordModel->status = 0;
                    
                    $LookrecordModel->save();
                    
                    $data = array('error' => 0, 'msg' => '增加成功'
                    
                    );
                    
                } else {
                    
                    $data = array('error' => 1, 'msg' => '请勿重复操作'
                    
                    );
                    
                    
                    //数据已经存在
                }
                
                
            } else {
                
                
                $data = array('error' => 1, 'msg' => '余额不足'
                
                );
            }
            
            
            return json_encode($data);
            
            
        }
        
        
    }