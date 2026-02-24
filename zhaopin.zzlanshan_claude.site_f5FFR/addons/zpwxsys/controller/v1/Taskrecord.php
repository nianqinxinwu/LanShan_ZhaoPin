<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Taskrecord as TaskrecordModel;
    use addons\zpwxsys\model\Job as JobModel;

    use addons\zpwxsys\service\Token;
    
    class Taskrecord extends BaseController
    {
        
        
        public function getTaskRecordList()
        {
            if (request()->isPost()) {
           
                
                $uid = Token::getCurrentUid();
                
                $odjob = "r.createtime desc";
                $mapjob['r.uid'] = $uid;
    
                $Nowpage = input('post.page');
                if ($Nowpage) $Nowpage = $Nowpage; else
                    $Nowpage = 1;
    
                $limits = $Nowpage * 10;
                $Nowpage = 1;
                
                
                $taskrecordModel = new TaskrecordModel();
                $taskrecordlist = $taskrecordModel->getTaskRecordByWhere($mapjob, $Nowpage, $limits, $odjob);
                
                $data = array('taskrecordlist' => $taskrecordlist);
                return json_encode($data);
            }
            
            
        }
        
        
        public function saveTaskRecord()
        {
            if (request()->isPost()) {
                
                
                $param = input('post.');
                
                $param['uid'] = Token::getCurrentUid();
                
                
                $taskrecord = TaskrecordModel::getTaskrecord($param);
                
                if ($taskrecord) {
                    
                    $data = json_encode(array('status' => 2, 'msg' => '您已领取过任务'));
                    
                } else {
                    
                    $param['createtime'] = time();
                    $param['status'] = 0;
                    $taskrecord = new TaskrecordModel();
                    $data = $taskrecord->insertTaskRecord($param);
                    
                }
                
                
                return $data;
            }
            
            
        }
        
        
     
        
        
    }