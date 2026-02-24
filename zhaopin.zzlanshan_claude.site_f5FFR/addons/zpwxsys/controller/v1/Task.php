<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Task as TaskModel;
    use addons\zpwxsys\model\Taskrecord as TaskrecordModel;
    use addons\zpwxsys\model\Job as JobModel;
    use addons\zpwxsys\model\City as CityModel;
    use addons\zpwxsys\model\Areainfo as AreaModel;
    use addons\zpwxsys\model\Jobcate as JobcateModel;
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use addons\zpwxsys\model\Jobsave as JobsaveModel;
    use addons\zpwxsys\service\Token as TokenService;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;
    
    class Task extends BaseController
    {
       
        public function getTasklist()
        {
            
            
            $city = input('post.city');
            
            $cityinfo = CityModel::getCityByName($city);
            
            
            $cityid = input('post.cityid');
            
            $areaid = input('post.houseareaid');
            
            $housetype = input('post.housetype');
        
            $odjob = "t.createtime desc";
            $mapjob['t.status'] = 1;
            
            if($areaid>0)
            {
                
                $mapjob['j.areaid'] = $areaid;
            }
            
             if($housetype>0)
            {
                
                $mapjob['j.worktype'] = $housetype;
            }
            
            
            $limits = 100;
            $Nowpage = 1;
            
            
            $TaskModel = new TaskModel();
            $tasklist = $TaskModel->getTaskByWhere($mapjob, $Nowpage, $limits, $odjob);
            
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $data = array('tasklist' => $tasklist, 'arealist' => $arealist, 'jobcatelist' => $jobcatelist);
            
            
            return json_encode($data);
            
            
        }
        
        
          /*
         * 检测是否登录
         */
        public function  isLogin(){
    
            $ctoken = input('post.ctoken');
    
    
            $valid = TokenService::verifyToken($ctoken);
            if($valid)
            {
                $companyid = Token::getCurrentCid($ctoken);
        
                if($companyid)
                    $data = array('error' => 0, 'msg' => '正常','companyid'=>$companyid);
                else
                    $data = array('error' => 1, 'msg' => '数据异常');
        
            }else{
        
                $data = array('error' => 1, 'msg' => 'Token异常');
            }
            
            return $data;
        }
        
        public function getMyTasklist()
        {
            if (request()->isPost()) {
                
                
              $msg = $this->isLogin();
              
              
              if($msg['error']  == 0 ) {
                
                 $companyid = $msg['companyid'];
                $uid = Token::getCurrentUid();
                
                
                $odjob = "t.createtime desc";
                $mapjob['t.companyid'] = $companyid;
                $limits = 50;
                $Nowpage = 1;
                
                
                $TaskModel = new TaskModel();
                $tasklist = $TaskModel->getTaskByWhere($mapjob, $Nowpage, $limits, $odjob);
                
                 $TaskrecordModel = new TaskrecordModel();
                if($tasklist)
                {
                    foreach ($tasklist as $k=>$v)
                    {
                        
                     $tasklist[$k]['taskcount'] = $TaskrecordModel->getTaskCount(['taskid'=>$v['id']]);   
                    }
                }
                
                
                
                $data = array('tasklist' => $tasklist);
                return json_encode($data);
                
              }else{
                  
                   return json_encode($msg);
                  
              }
            }
            
            
        }
        
        public function getTaskdetail()
        {
            
                if (request()->isPost()) {
                    $id = input('post.id');
                    
                    $map = array('id' => $id);
                    
                    $TaskModel = new TaskModel();
                    
                    
                    $taskinfo = $TaskModel->getTaskDetail($map);
                    
                    $taskinfo['totalmoney'] = $taskinfo['num'] * $taskinfo['money'];
                    
                    if (!$taskinfo) {
                       return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                    }
                    
                    
                    return json_encode($taskinfo);
            
                 }
        }
        
        public function sendJob()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                $param['uid'] = Token::getCurrentUid();
                
                $map['jobid'] = $param['jobid'];
                $map['companyid'] = $param['companyid'];
                $map['uid'] = $param['uid'];
                
                $jobrecord = JobrecordModel::getJobrecordWhere($map);
                
                if ($jobrecord) {
                    
                    $data = json_encode(array('status' => 2, 'msg' => '您已经投递过'));
                    
                } else {
                    
                    $param['createtime'] = time();
                    
                    $jobrecord = new JobrecordModel();
                    
                    $data = $jobrecord->sendJob($param);
                }
                
                
                return $data;
            }
            
            
        }
        
        
        public function jobSave()//收藏职位
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                $param['uid'] = Token::getCurrentUid();
                
                $map['jobid'] = $param['jobid'];
                $map['companyid'] = $param['companyid'];
                $map['uid'] = $param['uid'];
                
                $jobsave = JobsaveModel::getJobsaveWhere($map);
                
                if ($jobsave) {
                    
                    $jobsavemodel = new JobsaveModel();
                    $jobsavemodel->delJobsave($map);
                    
                    $data = json_encode(array('status' => 2, 'msg' => '取消收藏'));
                    
                } else {
                    
                    $param['createtime'] = time();
                    
                    $jobsave = new JobsaveModel();
                    
                    $data = $jobsave->jobSave($param);
                }
                
                
                return $data;
            }
            
            
        }
        
        
        public function myFindjob()
        {
            if (request()->isPost()) {
                
                
                $uid = Token::getCurrentUid();
                $od = "r.createtime desc";
                $map['r.uid'] = $uid;
                $Jobrecord = new JobrecordModel();
                $jobrecordlist = $Jobrecord->getMyFindList($map, $od);
                
                $data = array('jobrecordlist' => $jobrecordlist);
                return json_encode($data);
            }
            
            
        }
        
        
        public function mySaveJob()
        {
            if (request()->isPost()) {
                
                
                $uid = Token::getCurrentUid();
                $od = "r.createtime desc";
                $map['r.uid'] = $uid;
                $Jobsave = new JobsaveModel();
                $jobsavelist = $Jobsave->getMySaveList($map, $od);
                
                $data = array('jobsavelist' => $jobsavelist);
                return json_encode($data);
            }
            
            
        }
        
        public function pubTaskInit()
        {
            
             
            if (request()->isPost()) {
                
                
             $msg = $this->isLogin();
              
              
              if($msg['error']  == 0 ) {
            
                $uid = Token::getCurrentUid();
                
                $companyid = $msg['companyid'];
                
                $map['companyid'] = $companyid;
                
                $joblist = JobModel::getJobbyCompanyId($map);
                
                
                $data = array('joblist' => $joblist);
                
                return json_encode($data);
            
            
              }else{
                  
                   return json_encode($msg);
              }
              
            }
            
            
        }
        
        public function saveTask()
        {
            if (request()->isPost()) {
                $msg = $this->isLogin();
                
                if($msg['error']  == 0 ) {
                    $param = input('post.');
                    
                    $companyid = $msg['companyid'];
                    $param['companyid'] = $companyid;
                    $param['createtime'] = time();
                    
                    
                    $task = new TaskModel();
                    
                    $data = $task->insertTask($param);
                    
                    
                    return $data;
                
                }else{
                  
                   return json_encode($msg);
              }
            }
            
            
        }
        
        
        public function updateTask()
        {
            if (request()->isPost()) {
                
                
                $param = input('post.');
                
                $msg = $this->isLogin();
              
              if($msg['error']  == 0 ) {
                
                $companyid = $msg['companyid'];
                $param['companyid'] = $companyid;
                $taskinfo  = TaskModel::getTask(['id'=>$param['id'],'companyid'=>$companyid]);
                
                if (!$taskinfo) {
                     return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                }
                
                $task = new TaskModel();
                
                $data = $task->updateTask($param);
                
              }else{
                  
                    return json_encode(['status'=>1,'msg'=>'Token异常']);

              }
                
                
                return $data;
            }
            
            
        }
        
        
    }