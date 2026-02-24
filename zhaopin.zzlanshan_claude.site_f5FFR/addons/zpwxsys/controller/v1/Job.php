<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Nav as NavModel;
    use addons\zpwxsys\model\City as CityModel;
    use addons\zpwxsys\model\Areainfo as AreaModel;
    use addons\zpwxsys\model\Note as NoteModel;
    use addons\zpwxsys\model\Jobcate as JobcateModel;
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use addons\zpwxsys\model\Job as JobModel;
    use addons\zpwxsys\model\Jobsave as JobsaveModel;
    use addons\zpwxsys\model\Jobprice as JobpriceModel;
    use addons\zpwxsys\model\Sysmsg as SysmsgModel;
    use addons\zpwxsys\model\Company as CompanyModel;
    use addons\zpwxsys\model\Askjob as AskjobModel;
    use addons\zpwxsys\model\Worktype as WorktypeModel;
    use addons\zpwxsys\model\Sysinit as SysinitModel;
    use addons\zpwxsys\model\Task as TaskModel;
     use addons\zpwxsys\service\AccessToken as AccessToken;
    use addons\zpwxsys\service\CompanyRecord as CompanyRecordService;
    use addons\zpwxsys\model\Coinrecord as CoinrecordModel;
    use addons\zpwxsys\model\Sensitiveword as SensitivewordModel;
    use addons\zpwxsys\model\Chatgroup as ChatgroupModel;
    use addons\zpwxsys\model\Chatmember as ChatmemberModel;
    use addons\zpwxsys\model\Chatmessage as ChatmessageModel;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;

    
    class Job extends BaseController
    {
        
        
        public function sendCompanyJob()
            {
                if(request()->isPost()){
                    $param = input('post.');
                    $param['uid'] = Token::getCurrentUid();
                    
                    $taskid = $param['taskid'];
                    
                    $noteid = $param['noteid'];
                    
                    $taskinfo = TaskModel::getTask(array('id'=>$taskid));
                    
                    $umap =[];
                    $umap['id'] = $noteid;
                    $umap['tid'] = $param['uid'];//归属该经纪人
                    
                    $noteinfo =  NoteModel::getNoteMap($umap);
                    
                    if($noteinfo)
                    {
                    $map['jobid'] = $taskinfo['jobid'];
                    $map['companyid'] = $taskinfo['companyid'];
                    $map['uid'] =  $noteinfo['uid'];
                    $mapjob['id'] =    $taskinfo['jobid'] ;
                    $jobinfo = JobModel::getOne($mapjob);
                    
                    $jobrecord =  JobrecordModel::getJobrecordWhere($map);
                    
                    if($jobrecord)
                    {
                        
                        $data = json_encode(array('status'=>2,'msg'=>'您已经投递过'));
                        
                    }else{
                        
                        $param2['createtime'] = time();
                        
                         $param2['jobid'] = $taskinfo['jobid'];
                        $param2['companyid'] = $taskinfo['companyid'];
                        $param2['uid'] =  $noteinfo['uid'];
                        
                        $param2['noteid'] = $noteinfo['id'];
                    
                        $param2['agentuid'] =  $param['uid'] ;
                          $param2['taskid'] =  $taskid ;
                        
                        $jobrecord = new JobrecordModel();
                        
                        
                        
                        $data = $jobrecord->sendJob($param2);
                        
               
          
                    
                    }
                    
                    }else{
                        
                        
                         $data = json_encode(array('status'=>3,'msg'=>'请先完善简历'));
                    }
                    
                    
                    return $data;
                }
                
                
                
            }
    
        
        public function getJoblist()
        {
            
        
            
            $cityid = input('post.cityid');
            
            $areaid = input('post.areaid');
            
            $jobcateid = input('post.jobcateid');
            
            $jobpriceid = input('post.priceid');
            
            
            $type = input('post.type');
            
            if (!empty($type) && $type > 0) {
                
                $mapjob['j.type'] = $type;
            }
            
              if ($cityid > 0) {
                
                $mapjob['m.cityid'] = $cityid;
            }
            
            
            if ($areaid > 0) {
                
                $mapjob['m.areaid'] = $areaid;
            }
            
            
            if ($jobcateid > 0) {
                
                $mapjob['j.worktype'] = $jobcateid;
            }
            
            if ($jobpriceid > 0) {

                $mapjob['j.jobpriceid'] = $jobpriceid;
            }

            $settle_type = input('post.settle_type');
            if (!empty($settle_type) && $settle_type > 0) {
                if ($settle_type == 99) {
                    $mapjob['j.settle_type'] = array('not in', [1, 2, 3, 4]);
                } else {
                    $mapjob['j.settle_type'] = $settle_type;
                }
            }

            $sex = input('post.sex');
            if ($sex !== null && $sex !== '' && $sex != -1) {
                $mapjob['j.sex'] = intval($sex);
            }

            $keyword = input('post.keyword');
            if (!empty($keyword)) {
                $mapjob['j.jobtitle'] = array('like', '%' . $keyword . '%');
            }

            $sortby = input('post.sortby');

            $cityid = input('post.cityid');

            if ($sortby == 'money') {
                $odjob = "j.sort desc,j.money desc,j.updatetime desc";
            } else {
                $odjob = "j.sort desc,j.updatetime  desc";
            }
            $mapjob['j.status'] = 1;
            $mapjob['j.ischeck'] = 1;
            $mapjob['m.status'] = 1;
            
            
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
            
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            $mapjob['j.toptime'] = array('lt', time());
            
            $JobModel = new JobModel();
            $joblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
            $mapjob['j.toptime'] = array('gt', time());
            
            $topjoblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
            
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $worktype = WorktypeModel::getOne(array('id' => $type));
            
            $maptype['enabled'] = 1;
            $od = 'sort desc';
            
            
            $worktypelist = WorktypeModel::getList($maptype, $od);
            
            $jobpricelist = JobpriceModel::getJobpricelist();
            
            $data = array('joblist' => $joblist, 'arealist' => $arealist, 'jobcatelist' => $jobcatelist, 'topjoblist' => $topjoblist, 'worktype' => $worktype, 'worktypelist' => $worktypelist,'jobpricelist'=>$jobpricelist);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getInitJob()
        {
            
            $cityid = input('post.cityid');
            $arealist = AreaModel::getAreaByCityid($cityid);
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $maptype['enabled'] = 1;
            $od = 'sort desc';
            $worktypelist = WorktypeModel::getList($maptype, $od);
             $data = array('arealist' => $arealist, 'jobcatelist' => $jobcatelist, 'worktypelist' => $worktypelist);
            
            
            return json_encode($data);
            
        }
        
        
        public function getNavDetail()
        {
             $id = input('post.id');
            
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            
            
            $map = array('id' => $id);
            
            $navinfo = NavModel::getOne($map);
            
            if(!$navinfo)
            {
                 $data = json_encode(array('status'=>1,'msg'=>'请求数据不存在'));
                   return json_encode($data);
                
            }
            
            $data = array('navinfo' => $navinfo,'status'=>0,'msg'=>'请求数据正常');
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getJobIndexList()
        {
            
            $cityid = input('post.cityid');
            $worktype = input('post.worktype');
            
            
            $od = "j.createtime desc";
            
            $map = array();
            
            if ($cityid > 0) {
                
                $map['m.cityid'] = $cityid;
            }
            
            if ($worktype >= 0) {
                
                $map['j.type'] = $worktype;
                
            }
            
            $map['m.status'] = 1;
            $map['j.ischeck'] = 1;
            $map['j.status'] = 1;
            
            $limits = 60;
            $Nowpage = 1;
            
            $JobModel = new JobModel();
            $joblist = $JobModel->getJobByWhere($map, $Nowpage, $limits, $od);
            
            
            $data = array('joblist' => $joblist);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getmatchjoblist()
        {
            
            
            $city = input('post.city');
            
            $uid = Token::getCurrentUid();
            $noteinfo = NoteModel::getNoteByuid($uid);
            
            $cityinfo = CityModel::getCityByName($city);
            
            
            $jobcateid = $noteinfo['jobcateid'];
            
            $mapcate['id'] = $jobcateid;
            
            $jobcate = JobcateModel::getOne($mapcate);
            
            
            if ($jobcateid > 0) {
                
                $mapjob['j.worktype'] = $jobcateid;
            }
            
            
            $cityid = input('post.cityid');
            
            $odjob = "j.updatetime desc";
            $mapjob['j.status'] = 1;
            $mapjob['m.status'] = 1;
            
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
            
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $JobModel = new JobModel();
            $joblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
            
            
            $data = array('joblist' => $joblist, 'jobcate' => $jobcate);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getNearjoblist()
        {
            
            
            $city = input('post.city');
            
            
            
            $cityinfo = CityModel::getCityByName($city);
            
            $areaid = input('post.areaid');
            
            $jobcateid = input('post.jobcateid');
            
            $jobpriceid = input('post.priceid');
            
            $type = input('post.type');
            
            $latitude = input('post.latitude');
            
            $longitude = input('post.longitude');
            
            
            if (!empty($type) && $type > 0) {
                
                $mapjob['j.type'] = $type;
            }
            
            if ($areaid > 0) {
                
                $mapjob['m.areaid'] = $areaid;
            }
            
            
            if ($jobcateid > 0) {
                
                $mapjob['j.worktype'] = $jobcateid;
            }
            
            if ($jobpriceid > 0) {
                
                $mapjob['j.jobpriceid'] = $jobpriceid;
            }
            
            
            $cityid = input('post.cityid');
            
            $odjob = " distance asc ,j.updatetime desc";
            $mapjob['j.status'] = 1;
         //   $mapjob['m.status'] = 0;
         
  
            $Nowpage = input('post.page');
            
            
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
            
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            $JobModel = new JobModel();

            $joblist = $JobModel->getNearJobByWhere($mapjob, $Nowpage, $limits, $odjob, $latitude, $longitude);

     
            
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            
            $maptype['enabled'] = 1;
            $od = 'sort desc';
            
            
            $worktypelist = WorktypeModel::getList($maptype, $od);
            
            
            $data = array('joblist' => $joblist, 'arealist' => $arealist, 'jobcatelist' => $jobcatelist, 'worktypelist' => $worktypelist);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getSearchJobList()
        {
            
            $cityid = input('post.cityid');
            $cateid = input('post.cateid');
            $priceid = input('post.priceid');
            $edu = input('post.edu');
            $express = input('post.express');
            $sex = input('post.sex');
            $special = input('post.special');
            
            
            $od = "j.createtime desc";
            
            $map = array();
            
            if ($cityid > 0) {
                
                $map['m.cityid'] = $cityid;
            }
            
            if ($cateid > 0) {
                
                $map['j.worktype'] = $cateid;
            }
            
            if ($priceid > 0) {
                
                $map['j.jobpriceid'] = $priceid;
            }
            
            
            if ($edu != "") {
                
                $map['j.education'] = $edu;
            }
            
            if ($express != "") {
                
                $map['j.express'] = $express;
            }
            
            
            if ($sex != -1) {
                
                $map['j.sex'] = $sex;
            }
            
            
            if ($special != "") {
                
                $map['j.special'] = array('like', '%' . $special . '%');
            }
            
           // $map['m.status'] = 0;
            
            $map['j.status'] = 1;
            $map['j.ischeck'] = 1;
            
            
            $limits = 1000000;
            $Nowpage = 1;
            
            if (count($map) > 0) {
                $JobModel = new JobModel();
                $joblist = $JobModel->getJobByWhere($map, $Nowpage, $limits, $od);
            } else {
                
                $joblist = [];
            }
            
            
            $data = array('joblist' => $joblist);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getJobListCount()
        {
            
            $cityid = input('post.cityid');
            $cateid = input('post.cateid');
            $priceid = input('post.priceid');
            $edu = input('post.edu');
            $express = input('post.express');
            $sex = input('post.sex');
            $special = input('post.special');
            
            
            $od = "j.createtime desc";
            
            $map = array();
            
            if ($cityid > 0) {
                
                $map['m.cityid'] = $cityid;
            }
            
            if ($cateid > 0) {
                
                $map['j.worktype'] = $cateid;
            }
            
            if ($priceid > 0) {
                
                $map['j.jobpriceid'] = $priceid;
            }
            
            
            if ($edu != "") {
                
                $map['j.education'] = $edu;
            }
            
            if ($express != "") {
                
                $map['j.express'] = $express;
            }
            
            
            if ($sex != -1) {
                
                $map['j.sex'] = $sex;
            }
            
            
            if ($special != "") {
                
                $map['j.special'] = array('like', '%' . $special . '%');
            }
    
            $map['j.status'] = 1;
            $map['j.ischeck'] = 1;
            
            $limits = 1000000;
            $Nowpage = 1;
            
            if (count($map) > 0) {
                $JobModel = new JobModel();
                $joblist = $JobModel->getJobByWhere($map, $Nowpage, $limits, $od);
                $jobcount = count($joblist);
            } else {
                
                $jobcount = 0;
            }
            
            
            $data = array('jobcount' => $jobcount);
            
            
            return json_encode($data);
            
            
        }
        
        public function getJobdetail()
        {
            
            $id = input('get.id');
            
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            
            
            $JobModel = new JobModel();
            
            $map = array('id' => $id);
            
            $jobinfo = $JobModel->getJob($map);
            
            if (!$jobinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            $mapsave = array('jobid' => $jobinfo['id']);
            $jobsave = JobsaveModel::getJobsaveWhere($mapsave);
            if ($jobsave) {
                
                $jobinfo['savestatus'] = 1;
                
            } else {
                
                $jobinfo['savestatus'] = 0;
            }
            
            $jobinfo['special'] = explode(',', $jobinfo['special']);
            
         
            
            
            $odjob = "j.updatetime desc";
            
            $mapjob['j.status'] = 1;
            $mapjob['j.ischeck'] = 1;
            $mapjob['m.status'] = 1;
            
            $mapjob['j.companyid'] = $jobinfo['companyid'];
            $limits = 10;
            $Nowpage = 1;
            
            
            $JobModel = new JobModel();
            $joblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
            
            
            $data = array('joblist' => $joblist, 'jobinfo' => $jobinfo,'status'=>0,'msg'=>'请求数据正常');
            
            
            return json_encode($data);
        }
        
        public function sendJob()
        {
            if (request()->isPost()) {

                $param = input('post.');
                $param['uid'] = Token::getCurrentUid();

                $noteinfo = NoteModel::getNoteByuid($param['uid']);

                if (!$noteinfo) {
                    return json_encode(array('status' => 3, 'msg' => '请先完善简历'));
                }

                $map['jobid'] = $param['jobid'];
                $map['companyid'] = $param['companyid'];
                $map['uid'] = $param['uid'];

                $mapjob['id'] = $map['jobid'];
                $jobinfo = JobModel::getOne($mapjob);

                if (!$jobinfo) {
                    return json_encode(array('status' => 2, 'msg' => '职位不存在'));
                }

                if ((int)$jobinfo['status'] !== 1) {
                    return json_encode(array('status' => 2, 'msg' => '该职位已下架'));
                }

                if ((int)$jobinfo['ischeck'] !== 1) {
                    return json_encode(array('status' => 2, 'msg' => '该职位未审核通过'));
                }

                $jobrecord = JobrecordModel::getJobrecordWhere($map);

                if ($jobrecord) {

                    $data = json_encode(array('status' => 2, 'msg' => '您已经投递过'));

                } else {

                    $param['createtime'] = time();
                    $param['agentuid'] = 0 ;
                    $param['taskid'] = 0 ;

                    $jobrecord = new JobrecordModel();

                    $data = $jobrecord->sendJob($param);

                    // 给报名者发系统消息
                    $msg['uid'] = $param['uid'];
                    $msg['content'] = '恭喜您，投递职位《' . $jobinfo['jobtitle'] . '》成功';
                    $msg['createtime'] = time();
                    $msg['jobtitle'] = isset($jobinfo['jobtitle']) ? $jobinfo['jobtitle'] : '';
                    $msg['money'] = isset($jobinfo['money']) ? $jobinfo['money'] : '';
                    $companyinfo = CompanyModel::getCompany($jobinfo['companyid']);
                    $msg['companyname'] = isset($companyinfo['companyname']) ? $companyinfo['companyname'] : '';
                    $jobcateinfo = JobcateModel::getOne(['id' => $jobinfo['worktype']]);
                    $msg['jobcatename'] = isset($jobcateinfo['name']) ? $jobcateinfo['name'] : '';

                    $sysmsgModel = new SysmsgModel();
                    $sysmsgModel->insertSysmsg($msg);

                    // 给发布者发系统消息 + 群聊通知
                    $userInfo = UserModel::get($param['uid']);
                    $name = $userInfo ? ($userInfo['nickname'] ?: '求职者') : '求职者';

                    $groupInfo = ChatgroupModel::where('jobid', $param['jobid'])->where('status', 1)->find();
                    if ($groupInfo) {
                        $chatmsg = new ChatmessageModel();
                        $chatmsg->sendMessage([
                            'groupid' => $groupInfo['id'],
                            'uid' => 0,
                            'content' => $name . ' 报名了岗位「' . $jobinfo['jobtitle'] . '」',
                            'msg_type' => 1,
                            'createtime' => time()
                        ]);
                    }

                    if ($companyinfo) {
                        SysmsgModel::ensureNewFields();
                        $publisherMsg = new SysmsgModel();
                        $publisherMsg->allowField(true)->save([
                            'uid' => $companyinfo['uid'],
                            'content' => $name . ' 报名了您发布的岗位「' . $jobinfo['jobtitle'] . '」',
                            'createtime' => time(),
                            'status' => 0,
                            'jobtitle' => $jobinfo['jobtitle'],
                            'link' => '/pages/selectnote/index?jobid=' . $param['jobid']
                        ]);
                    }


                }

                return $data;
            }


        }
        
        
        public function saveAskjob()
        {
            if (request()->isPost()) {

                $param = input('post.');
                $param['uid'] = Token::getCurrentUid();

                $map['jobid'] = $param['jobid'];
                
                $map['content'] = $param['content'];
                
                $jmap['id'] = $map['jobid'];
                
                $jobinfo = JobModel::getOne($jmap);
                
                $map['companyid'] = $jobinfo['companyid'];
                
                $map['uid'] = $param['uid'];
                
                $map['createtime'] = time();
                
                $askjob = new AskjobModel();
                
                $data = $askjob->saveAskjob($map);
                
                
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
                
                //  print_r($map);
                
                $jobsave = JobsaveModel::getJobsaveWhere($map);
                
                if ($jobsave) {
                    
                    // print_r($jobsave);
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
                
                
                $status = input('post.status');
    
                $Nowpage = input('post.page');
                if ($Nowpage) $Nowpage = $Nowpage; else
                    $Nowpage = 1;
    
                $limits = $Nowpage * 10;
                $Nowpage = 1;
                
                if ($status == 1) {
                    // 已选中：r.status > 0
                    $map['r.status'] = ['gt', 0];
                } elseif ($status == 0) {
                    // 发布中：status=0 且岗位未过期
                    $map['r.status'] = 0;
                    $map['j.endtime'] = ['gt', time()];
                } elseif ($status == 3) {
                    // 已过期：岗位已过期
                    $map['j.endtime'] = ['elt', time()];
                }
                // status == -1: 全部，不加筛选
                $uid = Token::getCurrentUid();
                $od = "r.createtime desc";
                $map['r.uid'] = $uid;

                JobModel::ensureZwFields();
                $Jobrecord = new JobrecordModel();
                $jobrecordlist = $Jobrecord->getMyFindList($map,  $Nowpage, $limits,$od);
    
                $settle_type_arr = array(0=>'面议', 1=>'日结', 2=>'周结', 3=>'月结', 4=>'完工结');
                $sex_arr = array(0=>'不限', 1=>'仅男', 2=>'仅女');
                $now = time();

                if($jobrecordlist)
                {

                    foreach ($jobrecordlist as $k=>$v)
                    {

                        // 报名者视角状态：已选中 / 发布中 / 已过期
                        if ($v['status'] >= 1) {
                            $jobrecordlist[$k]['status_str'] = '已选中';
                            $jobrecordlist[$k]['status_type'] = 'selected';
                        } elseif ($v['endtime'] <= $now || $v['status'] < 0) {
                            $jobrecordlist[$k]['status_str'] = '已过期';
                            $jobrecordlist[$k]['status_type'] = 'expired';
                        } else {
                            $jobrecordlist[$k]['status_str'] = '发布中';
                            $jobrecordlist[$k]['status_type'] = 'active';
                        }

                        // 工作时间段
                        $workPeriod = '';
                        if (!empty($v['work_start_date']) && !empty($v['work_end_date'])) {
                            $workPeriod = $v['work_start_date'] . '-' . $v['work_end_date'];
                        }
                        if (!empty($v['work_start_time']) && !empty($v['work_end_time'])) {
                            $workPeriod .= ' ' . $v['work_start_time'] . '-' . $v['work_end_time'];
                        }
                        $jobrecordlist[$k]['work_period'] = trim($workPeriod);

                        // 结算类型
                        $st = isset($v['settle_type']) ? intval($v['settle_type']) : 0;
                        $jobrecordlist[$k]['settle_type_str'] = isset($settle_type_arr[$st]) ? $settle_type_arr[$st] : '面议';

                        // 性别需求
                        $sx = isset($v['job_sex']) ? intval($v['job_sex']) : 0;
                        $jobrecordlist[$k]['sex_str'] = isset($sex_arr[$sx]) ? $sex_arr[$sx] : '不限';

                        // signin_phase 直接透传
                        $jobrecordlist[$k]['signin_phase'] = isset($v['signin_phase']) ? intval($v['signin_phase']) : 0;
                    }
                }
                
                
                
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

                $status = input('post.status');
                if ($status !== null && $status >= 0) {
                    $map['j.status'] = $status;
                    if ($status == 1) {
                        $map['j.ischeck'] = 1;
                    }
                }

                $Nowpage = input('post.page');
                if ($Nowpage) $Nowpage = $Nowpage; else
                    $Nowpage = 1;
    
                $limits = $Nowpage * 10;
                $Nowpage = 1;
                
                $Jobsave = new JobsaveModel();
                $jobsavelist = $Jobsave->getMySaveList($map,  $Nowpage, $limits, $od);
                
                if ($jobsavelist) {
                    
                    foreach ($jobsavelist as $k => $v) {
                        
                        $jobsavelist[$k]['createtime'] = date('Y-m-d');
                    }
                    
                    
                }
                
                $data = array('jobsavelist' => $jobsavelist);
                return json_encode($data);
            }
            
            
        }
        
        public function cancleSave()
        {
            
            if (request()->isPost()) {
                
                
                $id = input('post.id');
                
                $uid = Token::getCurrentUid();
                $map['uid'] = $uid;
                $map['id'] = $id;
                
                $jobsaveinfo = JobsaveModel::getJobsaveWhere($map);
                
                if(!$jobsaveinfo)
                {
                    
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
                        return json_encode($data);

                }
                
                $Jobsave = new JobsaveModel();
                $flag = $Jobsave->delJobsave($map);
                
                if ($flag) {
                    
                    $data = array('status' => 0, 'msg' => '取消成功');
                } else {
                    
                    $data = array('status' => 1, 'msg' => '取消失败');
                }
                
                
                return json_encode($data);
            }
            
        }
        
        public function pubJobInit()
        {
            $city = input('post.city');
            
            $uid = Token::getCurrentUid();
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $cityinfo = CityModel::getCityByName($city);
            
            $arealist = AreaModel::getAreaByCityid($cityinfo['id']);
            
            $jobpricelist = JobpriceModel::getJobpricelist();
            
            $map['enabled'] = 1;
            $od = 'sort desc';
            
            
            $worktypelist = WorktypeModel::getList($map, $od);
            
            
            $data = array('jobcatelist' => $jobcatelist, 'arealist' => $arealist, 'jobpricelist' => $jobpricelist, 'worktypelist' => $worktypelist);
            
            return json_encode($data);
            
            
        }
        
        public function selectJobInit()
        {
            $city = input('post.city');
            
            $uid = Token::getCurrentUid();
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            
            $jobpricelist = JobpriceModel::getJobpricelist();
            
            
            $data = array('jobcatelist' => $jobcatelist, 'jobpricelist' => $jobpricelist);
            
            return json_encode($data);
            
            
        }
    
    
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
            
                $data = array('error' => 1, 'msg' => 'Token异常');
            }
        
            return $data;
        }
        
        public function saveJob()
        {
            if (request()->isPost()) {


                $msg = $this->isLogin();

                if($msg['error'] == 0) {


                    $param = input('post.');

                    $companyid =  $param['companyid'] = $msg['companyid'];

                    // 校验是否已完善名片
                    $companyinfo = \think\Db::name('zpwxsys_company')->where('id', $companyid)->find();
                    if (!$companyinfo || empty($companyinfo['companyname'])) {
                        return json_encode(array('status' => 0, 'msg' => '请先完善企业信息后再发布职位'));
                    }

                    // 敏感词过滤
                    if (!empty($param['jobtitle'])) {
                        $param['jobtitle'] = SensitivewordModel::filterContent($param['jobtitle']);
                    }
                    if (!empty($param['content'])) {
                        $param['content'] = SensitivewordModel::filterContent($param['content']);
                    }

                    $param['createtime'] = $param['updatetime'] = time();


                    $job = new JobModel();
                    $param['status'] = 0; // 待审核
                    $param['toptime'] =0;

                    $data = $job->insertJob($param);

                    $CompanyRecordService = new CompanyRecordService($companyid);
                    $CompanyRecordService->jobnum = -1;

                    $CompanyRecordService->mark = '添加职位';

                    $CompanyRecordService->SetJobNumRecord();


                    return json_encode(array('status' => 1, 'msg' => '发布成功，等待审核'));

                }else{


                    return  json_encode($msg);
                }
            }


        }
        
        
        public function updateJob()
        {
            if (request()->isPost()) {
                
                $msg = $this->isLogin();
    
                if($msg['error'] == 0) {
    
                    $param = input('post.');
                    
                    $companyid = $msg['companyid'];
                    
                    $map = [];
                    
                    $map['id'] = $param['id'];
                    $map['companyid'] = $companyid;
                    $jobinfo = JobModel::getOne($map);
                    
                    if (!$jobinfo) {
                       return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                    }
                            
                    $param['companyid'] = $companyid;

                    // 敏感词过滤
                    if (!empty($param['jobtitle'])) {
                        $param['jobtitle'] = SensitivewordModel::filterContent($param['jobtitle']);
                    }
                    if (!empty($param['content'])) {
                        $param['content'] = SensitivewordModel::filterContent($param['content']);
                    }

                    $param['updatetime'] = time();
    
                    $job = new JobModel();
    
                    $data = $job->updateJob($param);
    
    
                    return $data;
    
                }else{
    
                    return  json_encode($msg);
                }
            }
            
            
        }

        /**
         * 审核职位（管理员接口）
         */
        public function auditJob()
        {
            if (request()->isPost()) {
                $this->checkSuperScope();

                $id = input('post.id');
                $action = input('post.action'); // approve 或 reject

                if (!$id || !$action) {
                    return json_encode(array('status' => 0, 'msg' => '参数错误'));
                }

                $jobinfo = JobModel::getOne(['id' => $id]);
                if (!$jobinfo) {
                    return json_encode(array('status' => 0, 'msg' => '职位不存在'));
                }

                $job = new JobModel();
                if ($action == 'approve') {
                    $job->updateJob(['id' => $id, 'status' => 1]);

                    // 审核通过后自动创建群组
                    $companyInfo = CompanyModel::where('id', $jobinfo['companyid'])->find();
                    if ($companyInfo) {
                        // 检查是否已有群
                        $existGroup = ChatgroupModel::getOne(['jobid' => $id, 'status' => 1]);
                        if (!$existGroup) {
                            $groupData = [
                                'jobid' => $id,
                                'group_name' => $jobinfo['jobtitle'],
                                'companyid' => $jobinfo['companyid'],
                                'owner_uid' => $companyInfo['uid'],
                                'max_member' => 500,
                                'member_count' => 1,
                                'status' => 1,
                                'createtime' => time(),
                                'updatetime' => time()
                            ];
                            $chatgroup = new ChatgroupModel();
                            $chatgroup->createGroupForJob($groupData, $companyInfo['uid']);
                        }
                    }

                    return json_encode(array('status' => 1, 'msg' => '审核通过'));
                } else {
                    $job->updateJob(['id' => $id, 'status' => -1]);
                    return json_encode(array('status' => 1, 'msg' => '已拒绝'));
                }
            }
        }

public function getqrcodejob()
    {
        $id = input('get.id');
            
        $validate = new IDMustBePositiveInt();
        $validate->goCheck();
        
        $JobModel  = new JobModel();
         
        $map = array('id'=>$id);
        
        $jobinfo =  $JobModel->getJob($map);//获取职位详情
        
         if (!$jobinfo) {
                       return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                    }

        $AccessToken = new AccessToken();
        
        $qrcode = $AccessToken->getJobQrcode($id);
        
        $qrcode = cdnurl($qrcode ,true);
        
        $sysinfo = SysinitModel::getSysinfo();
        
        $sharebg = cdnurl($sysinfo['sharebg'] ,true);
        
        
        
       $data = array('qrcode'=>$qrcode,'sharebg'=>$sharebg,'status'=>0,'msg'=>'请求数据正常');
         
         
  
     return json_encode($data);


    }

        public function confirmWork()
        {
            if (request()->isPost()) {
                $uid = Token::getCurrentUid();
                $id = input('post.id');

                $map = [];
                $map['id'] = $id;
                $map['uid'] = $uid;

                $Jobrecord = new JobrecordModel();
                $record = $Jobrecord->where($map)->find();

                if (!$record) {
                    return json_encode(array('status' => 0, 'msg' => '记录不存在'));
                }

                // 允许 status=1(同意面试) 或 status=3(录用成功) 进行工作确认
                if (!in_array(intval($record['status']), [1, 3])) {
                    return json_encode(array('status' => 0, 'msg' => '当前状态不允许工作确认'));
                }

                $res = $Jobrecord->where(['id' => $id])->update(['status' => 4]);
                if ($res !== false) {
                    return json_encode(array('status' => 1, 'msg' => '工作确认成功'));
                } else {
                    return json_encode(array('status' => 0, 'msg' => '操作失败'));
                }
            }
        }

        public function doSignIn()
        {
            if (request()->isPost()) {
                $uid = Token::getCurrentUid();
                $id = input('post.id');

                $map = [];
                $map['id'] = $id;
                $map['uid'] = $uid;
                $map['status'] = 4;

                $Jobrecord = new JobrecordModel();
                $record = $Jobrecord->where($map)->find();

                if (!$record) {
                    return json_encode(array('status' => 0, 'msg' => '记录不存在或状态不正确'));
                }

                if ($record['signed_in'] == 1) {
                    return json_encode(array('status' => 0, 'msg' => '已签到，请勿重复操作'));
                }

                $res = $Jobrecord->where(['id' => $id])->update([
                    'signin_time' => date('Y-m-d H:i:s'),
                    'signed_in' => 1
                ]);
                if ($res !== false) {
                    // 签到奖励积分
                    CoinrecordModel::addPoints($uid, 1, 'signin', $id, '签到奖励');
                    return json_encode(array('status' => 1, 'msg' => '签到成功'));
                } else {
                    return json_encode(array('status' => 0, 'msg' => '签到失败'));
                }
            }
        }

        public function getZwJobdetail()
        {
            $id = input('get.id');

            $validate = new IDMustBePositiveInt();
            $validate->goCheck();

            // Ensure new fields exist (non-blocking)
            try {
                JobModel::ensureZwFields();
            } catch (\Exception $e) {
                // ignore — new fields not required for basic display
            }

            $JobModel = new JobModel();

            $map = array('id' => $id);

            $jobinfo = $JobModel->getJob($map);

            if (!$jobinfo) {
                return json_encode(['status' => 1, 'msg' => '请求数据不存在']);
            }

            // Convert model to array for safe manipulation
            $jobinfo = is_object($jobinfo) ? $jobinfo->toArray() : $jobinfo;

            // Check save status
            $mapsave = array('jobid' => $jobinfo['id']);
            $jobsave = JobsaveModel::getJobsaveWhere($mapsave);
            $jobinfo['savestatus'] = $jobsave ? 1 : 0;

            // Parse special tags
            if (!empty($jobinfo['special']) && is_string($jobinfo['special'])) {
                $jobinfo['special'] = explode(',', $jobinfo['special']);
            } else {
                $jobinfo['special'] = [];
            }

            // Apply count
            $rmap['jobid'] = $jobinfo['id'];
            $apply_count = JobrecordModel::getJobrecordToal($rmap);

            // Company info from eager-loaded relation
            $companyinfo = isset($jobinfo['companyinfo']) ? $jobinfo['companyinfo'] : [];
            if (is_object($companyinfo)) {
                $companyinfo = $companyinfo->toArray();
            }

            // Publisher info (from standalone company query for full data with CDN URLs)
            $publisherinfo = [];
            if ($jobinfo['companyid']) {
                $companyFull = CompanyModel::getCompany($jobinfo['companyid']);
                if ($companyFull) {
                    $publisherinfo['thumb'] = isset($companyFull['thumb']) ? $companyFull['thumb'] : '';
                    $publisherinfo['mastername'] = isset($companyFull['mastername']) ? $companyFull['mastername'] : '';
                    $publisherinfo['is_verified'] = isset($companyFull['is_verified']) ? $companyFull['is_verified'] : 0;
                    $publisherinfo['companyname'] = isset($companyFull['companyname']) ? $companyFull['companyname'] : '';
                    $publisherinfo['id'] = isset($companyFull['id']) ? $companyFull['id'] : 0;
                    // Use full company data for address fallback
                    $companyinfo = is_object($companyFull) ? $companyFull->toArray() : (array)$companyFull;
                }
            }
            // Ensure companyinfo is always included in jobinfo for frontend
            $jobinfo['companyinfo'] = $companyinfo;

            // Fallback: if no job_address, use company address
            if (empty($jobinfo['job_address']) && !empty($companyinfo['address'])) {
                $jobinfo['job_address'] = $companyinfo['address'];
            }
            if (empty($jobinfo['job_lat']) && !empty($companyinfo['lat'])) {
                $jobinfo['job_lat'] = $companyinfo['lat'];
            }
            if (empty($jobinfo['job_lng']) && !empty($companyinfo['lng'])) {
                $jobinfo['job_lng'] = $companyinfo['lng'];
            }

            $data = array(
                'status' => 0,
                'msg' => '请求数据正常',
                'jobinfo' => $jobinfo,
                'publisherinfo' => $publisherinfo,
                'apply_count' => $apply_count
            );

            return json_encode($data);
        }

        public function zwSaveJob()
        {
            if (request()->isPost()) {

                $msg = $this->isLogin();

                if ($msg['error'] == 0) {

                    $param = input('post.');

                    $companyid = $param['companyid'] = $msg['companyid'];

                    // Ensure new fields exist
                    JobModel::ensureZwFields();

                    // Check company info
                    $companyinfo = \think\Db::name('zpwxsys_company')->where('id', $companyid)->find();
                    if (!$companyinfo || empty($companyinfo['companyname'])) {
                        return json_encode(array('status' => 0, 'msg' => '请先完善企业信息后再发布职位'));
                    }

                    // Sensitive word filter
                    if (!empty($param['jobtitle'])) {
                        $param['jobtitle'] = SensitivewordModel::filterContent($param['jobtitle']);
                    }
                    if (!empty($param['content'])) {
                        $param['content'] = SensitivewordModel::filterContent($param['content']);
                    }
                    if (!empty($param['requirements'])) {
                        $param['requirements'] = SensitivewordModel::filterContent($param['requirements']);
                    }
                    if (!empty($param['tips'])) {
                        $param['tips'] = SensitivewordModel::filterContent($param['tips']);
                    }

                    $param['createtime'] = $param['updatetime'] = time();

                    $job = new JobModel();
                    $param['status'] = 0; // Pending review
                    $param['toptime'] = 0;

                    $data = $job->insertJob($param);

                    $CompanyRecordService = new CompanyRecordService($companyid);
                    $CompanyRecordService->jobnum = -1;
                    $CompanyRecordService->mark = '添加职位';
                    $CompanyRecordService->SetJobNumRecord();

                    return json_encode(array('status' => 1, 'msg' => '发布成功，等待审核'));

                } else {

                    return json_encode($msg);
                }
            }
        }

        public function doSignOut()
        {
            if (request()->isPost()) {
                $uid = Token::getCurrentUid();
                $id = input('post.id');

                $map = [];
                $map['id'] = $id;
                $map['uid'] = $uid;
                $map['status'] = 4;

                $Jobrecord = new JobrecordModel();
                $record = $Jobrecord->where($map)->find();

                if (!$record) {
                    return json_encode(array('status' => 0, 'msg' => '记录不存在或状态不正确'));
                }

                if ($record['signed_in'] != 1) {
                    return json_encode(array('status' => 0, 'msg' => '尚未签到，无法签退'));
                }

                $res = $Jobrecord->where(['id' => $id])->update([
                    'signout_time' => date('Y-m-d H:i:s'),
                    'status' => 5
                ]);
                if ($res !== false) {
                    return json_encode(array('status' => 1, 'msg' => '签退成功'));
                } else {
                    return json_encode(array('status' => 0, 'msg' => '签退失败'));
                }
            }
        }


    }