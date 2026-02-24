<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\City as CityModel;
    use addons\zpwxsys\model\Areainfo as AreaModel;
    use addons\zpwxsys\model\Note as NoteModel;
    use addons\zpwxsys\model\Jobcate as JobcateModel;
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use addons\zpwxsys\model\Jobprice as JobpriceModel;
    use addons\zpwxsys\model\Jobpart as JobModel;
    use addons\zpwxsys\model\Jobsave as JobsaveModel;
    use addons\zpwxsys\model\Askjob as AskjobModel;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;

    class Jobpart extends BaseController
    {
       
        public function getJoblist()
        {
            
            
            $city = input('post.city');
            
            
            
            $cityinfo = CityModel::getCityByName($city);
            
            $areaid = input('post.areaid');
            
            $jobcateid = input('post.jobcateid');
            
            $jobpriceid = input('post.priceid');
            
            $type = input('post.type');
            
            if (!empty($type)) {
                
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
            
            $odjob = "j.updatetime desc";
            $mapjob['j.status'] = 0;
            
            
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
            
            
            $data = array('joblist' => $joblist, 'arealist' => $arealist, 'jobcatelist' => $jobcatelist, 'topjoblist' => $topjoblist);
            
            
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
        
        public function getJobdetail($id)
        {
            
            
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            
            
            $JobModel = new JobModel();
            
            $map = array('id' => $id);
            
            $jobinfo = $JobModel->getJob($map);
            $mapsave = array('jobid' => $jobinfo['id']);
            $jobsave = JobsaveModel::getJobsaveWhere($mapsave);
            if ($jobsave) {
                
                $jobinfo['savestatus'] = 1;
                
            } else {
                
                $jobinfo['savestatus'] = 0;
            }
            
            $jobinfo['special'] = explode(',', $jobinfo['special']);
            
            if (!$jobinfo) {
                   return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            
            $odjob = "j.updatetime desc";
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
                
                if ($noteinfo) {
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
                    
                } else {
                    
                    
                    $data = json_encode(array('status' => 3, 'msg' => '请先完善简历'));
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
                
                if (!$jobinfo) {
                   return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                }
                
                
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
                    
                    print_r($jobsave);
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
                
                if ($status >= 0) {
                    
                    $map['r.status'] = $status;
                }
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
                
                if (!$jobsavelist->isEmpty()) {
                    
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
            
            
            $data = array('jobcatelist' => $jobcatelist, 'arealist' => $arealist, 'jobpricelist' => $jobpricelist);
            
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
        
        public function saveJob()
        {
            if (request()->isPost()) {
                
                
                $param = input('post.');
                
                $param['uid'] = Token::getCurrentUid();
                
                $param['createtime'] = $param['updatetime'] = time();
                
                
                $job = new JobModel();
                
                $data = $job->insertJob($param);
                
                
                return $data;
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
    
                    $param['updatetime'] = time();
    
                    $job = new JobModel();
    
                    $data = $job->updateJob($param);
    
    
                    return $data;
    
                }else{
    
                    return  json_encode($msg);
                }
            }
            
            
        }
        
        
    }