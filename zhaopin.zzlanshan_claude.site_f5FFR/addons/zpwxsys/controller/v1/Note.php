<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\City as CityModel;
    use addons\zpwxsys\model\Areainfo as AreaModel;
    use addons\zpwxsys\model\Jobcate as JobcateModel;
    use addons\zpwxsys\model\Job as JobModel;
    use addons\zpwxsys\model\Company as CompanyModel;
    use addons\zpwxsys\model\Looknote as LooknoteModel;
    use addons\zpwxsys\model\Jobprice as JobpriceModel;
    use addons\zpwxsys\model\Note as NoteModel;
    use addons\zpwxsys\model\Current as CurrentModel;
    use addons\zpwxsys\model\Worktype as WorktypeModel;
    use addons\zpwxsys\model\Helplab as HelplabModel;
    use addons\zpwxsys\model\Sysmsg as SysmsgModel;
    use addons\zpwxsys\model\Invaterecord as InvaterecordModel;
    use addons\zpwxsys\model\Edu as EduModel;
    use addons\zpwxsys\model\Express as ExpressModel;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\service\Lookrole as LookroleService;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;
    use app\common\library\Upload;
    
    class Note extends BaseController
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
            
                $data = array('error' => 1, 'msg' => 'Token异常');
            }
        
            return $data;
        }
        
        
        public function getNotelist()
        {
            
            
            $cityid = input('get.cityid');
            
            
            $areaid = input('get.areaid');
            
            $eduid = input('get.eduid');
            
            $cateid = input('get.cateid');
            
            $express = input('get.express');
            
            $sex = input('get.sex');
            
            $money = input('get.money');
            
            
            if ($areaid > 0) {
                
                $map['n.areaid'] = $areaid;
            }
            
            
            if ($cateid > 0) {
                
                $map['n.jobcateid'] = $cateid;
            }
            
            if ($eduid != "") {
                
                $map['n.education'] = $eduid;
            }
            
            if ($express != "") {
                
                $map['n.express'] = $express;
            }
            
            
            if ($sex != "") {
                
                $map['n.sex'] = $sex;
            }
            
            
            if ($money != "") {
                
                $map['n.money'] = $money;
            }
            
            $od = "n.refreshtime desc";
            
            $map['n.cityid'] = $cityid;
            
            
            $Nowpage = input('get.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
            
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $map['n.status'] = 1;
            $map['n.ishidden'] = 0;
            
            $NoteModel = new NoteModel();
            
            $notelist = $NoteModel->getNoteByWhere($map, $Nowpage, $limits, $od);
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $data = array('notelist' => $notelist, 'arealist' => $arealist, 'jobcatelist' => $jobcatelist);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getMatchNotelist()
        
        {
            
            
            $city = input('post.city');
            
            $jobid = input('post.jobid');
            
            $rmap['id'] = $jobid;
            
            $jobinfo = JobModel::getOne($rmap);
            
            if (!$jobinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            
            $cityinfo = CityModel::getCityByName($city);
            
            
            $cityid = $cityinfo['id'];
            $od = "n.refreshtime desc";
            $map['n.cityid'] = $cityid;
            $map['n.status'] = 1;
            $map['n.ishidden'] = 0;
            $map['n.jobcateid'] = $jobinfo['worktype'];
            $limits = 1000;
            $Nowpage = 1;
            
            
            $NoteModel = new NoteModel();
            
            $notelist = $NoteModel->getNoteByWhere($map, $Nowpage, $limits, $od);
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $data = array('notelist' => $notelist, 'jobinfo' => $jobinfo, 'notecount' => count($notelist),'status'=>0,'msg'=>'请求数据正常');
            
            
            return json_encode($data);
            
            
        }
        
        
        public function myLookNote()
        {
            
            
            $uid = Token::getCurrentUid();
            $od = "k.createtime desc";
            $map['k.uid'] = $uid;
            $limits = 1000;
            $Nowpage = 1;
            
            
            $LooknoteModel = new LooknoteModel();
            
            $looknotelist = $LooknoteModel->getListByWhere($map, $Nowpage, $limits, $od);
            
            
            $data = array('looknotelist' => $looknotelist);
            
            
            return json_encode($data);
        }
        
        
        public function getNoteListCount()
        {
            
            $cateid = input('post.cateid');
            $priceid = input('post.priceid');
            $edu = input('post.eduid');
            $express = input('post.express');
            $sex = input('post.sex');
            
            
            $od = "n.createtime desc";
            
            $map = array();
            
            $map['n.ishidden'] = 0;
            
            if ($cateid > 0) {
                
                $map['n.jobcateid'] = $cateid;
            }
            
            if ($priceid != "") {
                
                $map['n.money'] = $priceid;
            }
            
            
            if ($edu != "") {
                
                $map['n.education'] = $edu;
            }
            
            if ($express != "") {
                
                $map['n.express'] = $express;
            }
            
            
            if ($sex != "") {
                
                $map['n.sex'] = $sex;
            }
            
            
            $limits = 1000000;
            $Nowpage = 1;
            
            //       print_r($map);
            
            if (count($map) > 0) {
                $NoteModel = new NoteModel();
                $notelist = $NoteModel->getNoteByWhere($map, $Nowpage, $limits, $od);
                $notecount = count($notelist);
            } else {
                
                $notecount = 0;
            }
            
            
            $data = array('notecount' => $notecount);
            
            
            return json_encode($data);
            
        }
        
        public function getAgentNotelist()
        {
            
            $uid = Token::getCurrentUid();
            
            $od = "n.createtime desc";
            $map['n.tid'] = $uid;
            //$map['n.status'] = 0;
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
    
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $NoteModel = new NoteModel();
            
            $notelist = $NoteModel->getNoteByWhere($map, $Nowpage, $limits, $od);
            
            
            $data = array('notelist' => $notelist);
            
            
            return json_encode($data);
            
            
        }
        
        public function getNotedetail()
        {
            
            $id = input('get.id');
            
            $ctoken = input('get.ctoken');
        
        
            $companyid = Token::verifyToken($ctoken);
            
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            
            $noteinfo = NoteModel::getNote($id);
            
            

            if ($companyid > 0) {
                $map['noteid'] = $id;
                $map['companyid'] = $companyid;
                $islooknote = LooknoteModel::getOne($map);
                $noteinfo = NoteModel::getNote($id);
                
                
                 if (!$noteinfo) {
                     return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                 }
                
                if ($noteinfo['uid'] > 0) {
                    
                    if (!$islooknote) {
                        
                        $LooknoteModel = new LooknoteModel();
                        $param['noteid'] = $id;
                        $param['companyid'] = $companyid;
                        $param['uid'] = $noteinfo['uid'];
                        $param['createtime'] = time();
                        $LooknoteModel->saveRecord($param);
                        
                    }
                    
                }
                
                
            }
            
            $uid = Token::getCurrentUid();
            
         
            
            
            $LookroleService = new LookroleService($uid, $companyid, $id);
            
            $isLook = $LookroleService->CheckIsLookNote();
            
            if (!$isLook) $noteinfo['tel'] = '';
            
            
            $amap['id'] = $noteinfo['areaid'];
            $areainfo = AreaModel::getAreaByIdRow($amap);
            
            $noteinfo['areaname'] = $areainfo['name'];
            
            
            $noteinfo['age'] = date('Y') - $noteinfo['birthday'];
            
            $edulist = EduModel::getEduByUidSelect($noteinfo['uid']);
            $expresslist = ExpressModel::getExpressByUidSelect($noteinfo['uid']);
            
            $worktype = WorktypeModel::getOne($noteinfo['worktypeid']);
            
            $helplab = HelplabModel::getOne($noteinfo['helplabid']);
            
            $current = CurrentModel::getOne($noteinfo['currentid']);
            
            $data = array('expresslist' => $expresslist, 'edulist' => $edulist, 'noteinfo' => $noteinfo, 'isLook' => $isLook, 'worktype' => $worktype, 'helplab' => $helplab, 'current' => $current,'status'=>0,'msg'=>'请求数据正常');
            
            
            return json_encode($data);
        }
        
        public function getPubnoteinit()
        {
            
            $city = input('post.city');
            
            $uid = Token::getCurrentUid();
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            $cityinfo = CityModel::getCityByName($city);
            
            $arealist = AreaModel::getAreaByCityid($cityinfo['id']);
            
            
            $map['enabled'] = 1;
            $od = 'sort desc';
            
            $currentlist = CurrentModel::getList($map, $od);
            
            $worktypelist = WorktypeModel::getList($map, $od);
            
            $helplablist = HelplabModel::getList($map, $od);
            
            
            $jobpricelist = JobpriceModel::getJobpricelist();

            $noteinfo = NoteModel::getNoteByuid($uid);
            $edulist = [];
            $expresslist = [];
            if($noteinfo)
            {    
            	$edulist = EduModel::getEduByUidSelect($noteinfo['uid']);
            	$expresslist = ExpressModel::getExpressByUidSelect($noteinfo['uid']);
            }
            
            $data = array('edulist'=>$edulist,'expresslist'=>$expresslist,'jobcatelist' => $jobcatelist, 'arealist' => $arealist, 'noteinfo' => $noteinfo, 'currentlist' => $currentlist, 'worktypelist' => $worktypelist, 'helplablist' => $helplablist, 'jobpricelist' => $jobpricelist);
            
            return json_encode($data);
            
        }
        
        
        public function getPubeduinit()
        {
            
            
            $uid = Token::getCurrentUid();
            
            
            $edulist = EduModel::getEduByUidAll($uid);
            
            
            return $edulist;
            
        }
        
        
        public function getPubexpressinit()
        {
            
            
            $uid = Token::getCurrentUid();
            
            
            $expresslist = ExpressModel::getExpressByUidAll($uid);
            
            
            return $expresslist;
            
        }
        
        
        public function saveNote()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                
                $param['uid'] = Token::getCurrentUid();
                
                $noteinfo = NoteModel::getNoteByuid($param['uid']);
                
                
                $note = new NoteModel();
                
                if (!$noteinfo) {
                    
                    $param['createtime'] = $param['refreshtime'] = time();
                    $data = $note->insertNote($param);
                    
                    $userinfo = UserModel::getByUserWhere(array('id' => $param['uid']));
                    
                    $tid = $userinfo['tid'];
                    
                   
                    
                } else {
                    $param['id'] = $noteinfo['id'];
                    
                    $data = $note->updateNote($param);
                }
                
                return $data;
            }
            
            
        }
        
        
        public function saveContent()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                
                $param['uid'] = Token::getCurrentUid();
                
                $noteinfo = NoteModel::getNoteByuid($param['uid']);
                
                if (!$noteinfo) {
                    return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                }
                
                
                $note = new NoteModel();
                
                
                $param['id'] = $noteinfo['id'];
                
                $data = $note->updateNote($param);
                
                
                return $data;
            }
            
            
        }
        
        
        public function saveEdu()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                
                $uid = Token::getCurrentUid();
                
                
                $edu = new EduModel();
    
                $insertData =[];
                if ($param['begindateschool'] != '') {
                    $par['uid'] = $uid;
                    $par['begindateschool'] = $param['begindateschool'];
                    $par['enddateschool'] = $param['enddateschool'];
                    $par['school'] = $param['school'];
                    $par['educationname'] = $param['educationname'];
                    $par['vocation'] = $param['vocation'];
                    
                    
                    $insertData[] = $par;
                    
                    
                }
                
                if ($param['ln'] == 1) {
                    $par['uid'] = $uid;
                    $par['begindateschool'] = $param['begindateschool2'];
                    $par['enddateschool'] = $param['enddateschool2'];
                    $par['school'] = $param['school2'];
                    $par['educationname'] = $param['educationname2'];
                    $par['vocation'] = $param['vocation2'];
                    
                    
                    $insertData[] = $par;
                }
                
                $edu->delEduByuid($uid);
                
                $data = $edu->insertEdu($insertData);
                
                
                return $data;
            }
            
            
        }
        
        
        public function saveExpress()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                
                $uid = Token::getCurrentUid();
                
                $express = new ExpressModel();
    
                $insertData =[];
                if ($param['begindatejob'] != '') {
                    $par['uid'] = $uid;
                    $par['begindatejob'] = $param['begindatejob'];
                    $par['enddatejob'] = $param['enddatejob'];
                    $par['companyname'] = $param['companyname'];
                    $par['jobtitle'] = $param['jobtitle'];
                    
                    
                    $insertData[] = $par;
                    
                    
                }
                
                if ($param['ln'] == 1) {
                    $par['uid'] = $uid;
                    $par['begindatejob2'] = $param['begindatejob2'];
                    $par['enddatejob2'] = $param['enddatejob2'];
                    $par['companyname2'] = $param['companyname2'];
                    $par['jobtitle2'] = $param['jobtitle2'];
                    
                    
                    $insertData[] = $par;
                }
                
                $express->delExpressByuid($uid);
                
                $data = $express->insertExpress($insertData);
                
                
                return $data;
            }
            
            
        }
        
        
        public function sendInvatejob()
        {
            if (request()->isPost()) {
                
                $msg = $this->isLogin();
                if($msg['error'] == 0 ) {
    
                    $param = input('post.');
                    $param['uid'] = Token::getCurrentUid();
    
                    $map['noteid'] = $param['noteid'];
                    
                    
                    $map['companyid'] = $param['companyid'] = $msg['companyid'];
    
    
                    $invaterecord = InvaterecordModel::getSendinvatejobWhere($map);
    
                    if ($invaterecord) {
        
                        $data = json_encode(array('status' => 2, 'msg' => '您已邀请过'));
        
                    } else {
        
                        $param['createtime'] = time();
        
                        $invaterecord = new InvaterecordModel();
        
                        $data = $invaterecord->sendinvatejob($param);
        
        
                        $noteinfo = NoteModel::getNote($map['noteid']);
        
                        $companyinfo = CompanyModel::getCompany($map['companyid']);
        
                        $msg['uid'] = $noteinfo['uid'];
                        $msg['content'] = '恭喜您，企业《' . $companyinfo['companyname'] . '》邀请您面试了,请您及时联系!';
                        $msg['createtime'] = time();
                        $msg['link'] = '/pages/companydetail/index?id=' . $map['companyid'];
                        $msg['jobtitle'] = '';
                        $msg['money'] = '';
                        $msg['companyname'] = isset($companyinfo['companyname']) ? $companyinfo['companyname'] : '';
                        $msg['jobcatename'] = '';

                        $sysmsgModel = new SysmsgModel();

                        $sysmsgModel->insertSysmsg($msg);
        
        
                    }
    
    
                    return $data;
    
                }else{
                    $data = json_encode(array('status' => 3, 'msg' => '您已邀请过'));
                    return $data;
                    
                }
            }
            
            
        }
        
        
        public function checkNote()
        {
            
            if (request()->isPost()) {
                
                
                $param['uid'] = Token::getCurrentUid();
                
                $time = strtotime(date('Y-m-d'));
                
                $noteinfo = NoteModel::getNoteByuid($param['uid']);
                
                $note = new NoteModel();
                
                if (!$noteinfo) {
                    $data = json_encode(array('msg' => '请先完善简历', 'status' => 2));
                    
                } else {
                    
                    $data = json_encode(array('msg' => '数据正常', 'status' => 1));
                }
                
                return $data;
            }
            
            
        }
        
        
        public function noteRefresh()
        {
            
            if (request()->isPost()) {
                
                
                $param['uid'] = Token::getCurrentUid();
                
                $time = strtotime(date('Y-m-d'));
                
                $noteinfo = NoteModel::getNoteByuid($param['uid']);
                
                $note = new NoteModel();
                
                if (!$noteinfo) {
                    $data = json_encode(array('msg' => '请先完善简历', 'status' => 2));
                    
                } else {
                    
                    if ($noteinfo['refreshtime'] > $time) {
                        
                        $data = json_encode(array('msg' => '每天只可刷新一次哦', 'status' => 1));
                        
                    } else {
                        $param['id'] = $noteinfo['id'];
                        
                        $param['refreshtime'] = time();
                        
                        $data = $note->updateNote($param);
                        
                    }
                }
                
                return $data;
            }
            
            
        }
        
        public function uploadImg()
        {
            
            
            $upload = new Upload();
            
            $file = request()->file('file');
            $upload->setFile($file);
            $fileinfo = $upload->upload();
            $data = array('imgpath' => $fileinfo['url']);
            return json_encode($data);
            
            
        }
        
        
    }