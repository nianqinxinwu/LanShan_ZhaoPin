<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\City as CityModel;
    use addons\zpwxsys\model\Areainfo as AreaModel;
    use addons\zpwxsys\model\Jobcate as JobcateModel;
    use addons\zpwxsys\model\Company as CompanyModel;
    use addons\zpwxsys\model\Companyaccount as CompanyaccountModel;
    use addons\zpwxsys\model\Job as JobModel;
    use addons\zpwxsys\model\Mygz as MygzModel;
    use addons\zpwxsys\model\Sysmsg as SysmsgModel;
    use addons\zpwxsys\model\Comment as CommentModel;
    use addons\zpwxsys\model\Companyrecord as CompanyrecordModel;
    use addons\zpwxsys\service\CompanyRecord as CompanyRecordService;
    use addons\zpwxsys\model\Coinrecord as CoinrecordModel;
    use addons\zpwxsys\model\Companyrole as CompanyroleModel;
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use addons\zpwxsys\model\Task as TaskModel;
    use addons\zpwxsys\model\Projobrecord as ProjobrecordModel;
    use addons\zpwxsys\model\Chatgroup as ChatgroupModel;
    use addons\zpwxsys\model\Chatmember as ChatmemberModel;
    use addons\zpwxsys\model\Chatmessage as ChatmessageModel;
    
    
    use addons\zpwxsys\service\CompanyToken as CompanyToken;
    use addons\zpwxsys\service\Token as TokenService;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;
    use addons\zpwxsys\model\User as UserModel;
    use app\common\library\Upload;
    use think\Db;
    
    /*
     * 小程序端企业所有接口
     *
     */
    
    class Company extends BaseController
    {
        
        /*
         * 获取企业列表信息
         * return array companylist
         */
        
        public function getCompanylist()
        {
            
            $cityid = input('post.cityid');
            
            $areaid = input('post.areaid');
            
            $priceid = input('post.priceid');
            $cateid = input('post.cateid');
            
            $od = "g.sort desc ,g.createtime desc";
        
            $map = [];
            $map['g.cityid']  = $cityid;
            $map['g.status'] = 1;
            if($areaid>0)
            {
                $map['g.areaid'] = $areaid;
            }
            
            if($priceid !=-1)
            {
                
                  $map['g.companytype'] = $priceid;
            }
            
             if($cateid !='不限')
            {
                
                  $map['g.companyworker'] = $cateid;
            }
            
         
           $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
            
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $companyModel = new CompanyModel();
            
            $list = $companyModel->getCompanyByWhere($map, $Nowpage, $limits, $od);
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            $data = array('companylist' => $list, 'arealist' => $arealist);
            
            return json_encode($data);
        }
        

   public function mysendorder()
    {
        
        
        if(request()->isPost()){


           $uid = Token::getCurrentUid();
    
            $Nowpage = input('post.page');
            $status = input('post.status');
            
            
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
    
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $JobrecordModel = new JobrecordModel();
          
            $totalcount = $JobrecordModel->getgetMyTaskFindCount(array('r.agentuid'=>$uid));
        
            $totalcount_0 =  $JobrecordModel->getgetMyTaskFindCount(array('r.agentuid'=>$uid,'r.status'=>0));
            $totalcount_1 =  $JobrecordModel->getgetMyTaskFindCount(array('r.agentuid'=>$uid,'r.status'=>1));
            $totalcount_2 =  $JobrecordModel->getgetMyTaskFindCount(array('r.agentuid'=>$uid,'r.status'=>2));
            $totalcount_3 =  $JobrecordModel->getgetMyTaskFindCount(array('r.agentuid'=>$uid,'r.status'=>3));
            $totalcount_4 =  $JobrecordModel->getgetMyTaskFindCount(array('r.agentuid'=>$uid,'r.status'=>['gt',3]));
            
          
          
           $od="r.createtime desc";
           $map['r.agentuid'] = $uid;
           if($status>-1)
           {
               if($status<=3) {
                   $map['r.status'] = $status;
               }else{
                   
                   $map['r.status'] = ['gt',3];
               }
           }
           $Jobrecord = new JobrecordModel();
           $notelist = $Jobrecord->getMyTaskFindList($map, $Nowpage, $limits,$od);
           
           
           
           
           //  $status_arr = array(0=>'新提交',1=>'面试成功',-1=>'面试失败',2=>'录用成功',-2=>'录用失败',3=>'试用成功',-3=>'试用失败',4=>'已完成',-4=>'订单失败');
           
       $status_arr = array(0=>'新提交',1=>'同意面试',-1=>'拒绝面试',2=>'面试成功',-2=>'面试失败',3=>'录用成功',-3=>'录用失败',4=>'试用成功',-4=>'试用失败',5=>'已完成',-5=>'订单失败',6=>'已完成/已分佣',-6=>'分佣失败');
        
        if($notelist)
        {
        
        foreach ($notelist as $k=>$v) 
        {
            
           // $list[$k]['createtime'] = date('Y-m-d H:m:s',$v['createtime']);
            
            $notelist[$k]['status'] =$status_arr[$v['status']];

            
        }
           
        }
           
           
           
           
            $data = array('notelist'=>$notelist,'totalcount'=>$totalcount,'totalcount_0'=>$totalcount_0 ,'totalcount_1'=>$totalcount_1,'totalcount_2'=>$totalcount_2,'totalcount_3'=>$totalcount_3,'totalcount_4'=>$totalcount_4);
            return json_encode($data);
        }
          
     
        
        
    }
    
    
    
      public function cancleTask()
        {
            
            
            if (request()->isPost()) {
                
                
                
    
                $param = input('post.');
                
                 $msg = $this->isLogin();
                
                if($msg['error']  == 0 ) { 
                    
                    $companyid = $msg['companyid'];
                    $params['id'] = $param['id'];
                    $params['status'] =0;
                    $params['companyid'] = $companyid;
                    $map = [];
                    
                    $map['id'] = $param['id'];
                    $map['companyid'] = $companyid;
                    
                    $taskinfo = TaskModel::getTask($map);
                    
                    if($taskinfo)
                    {
                    
                        $TaskModel = new TaskModel();
                        
                        $TaskModel->updateTask($params);
                        
                        $data = array('status' => 0, 'msg' => '关闭成功');
                    
                    }else{
                        
                         $data = array('status' => 1, 'msg' => '请求数据不存在');
                    }
                    
                }else{
                    
                    $data = array('status' => 1, 'msg' => 'Token异常');

                }
                
                return json_encode($data);
            }
            
            
        }
        
        
          public function upTask()
        {
            
            
            if (request()->isPost()) {
                
                    $param = input('post.');
                    
                    $msg = $this->isLogin();
                    
                  if($msg['error']  == 0 ) { 
                    $companyid = $msg['companyid'];
                    $params['status'] =1;
                    $params['id'] = $param['id'];
                    $params['companyid'] =$companyid;
                    
                    $map = [];
                    
                    $map['id'] = $param['id'];
                    $map['companyid'] = $companyid;
                    
                    $taskinfo = TaskModel::getTask($map);
                    
                     if($taskinfo)
                    {
                        $TaskModel = new TaskModel();
                        $TaskModel->updateTask($params);
                        
                        $data = array('status' => 0, 'msg' => '开启成功');
                    
                    }else{
                        
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
    
                        
                    }
                
                }else{
                    
                     $data = array('status' => 1, 'msg' => 'Token异常');

                }
                
                
                return json_encode($data);
            }
            
            
        }
        
    
        
 public function sendorderdetail()
    {
        
        if(request()->isPost()){
            extract(input());
            $param = input('post.');
            
            
             $msg = $this->isLogin();
                
                if($msg['error']  == 0 ) {
    
                $companyid = $msg['companyid'];
                
                $status_arr = array(0=>'新提交',1=>'同意面试',-1=>'拒绝面试',2=>'面试成功',-2=>'面试失败',3=>'录用成功',-3=>'录用失败',4=>'试用成功',-4=>'试用失败',5=>'已完成',-5=>'订单失败',6=>'已完成/已分佣',-6=>'分佣失败');
           
               $cmap['r.id'] = $param['id'];
               $cmap['r.companyid'] = $companyid;
                $JobrecordModel = new JobrecordModel();
                
                $guestinfo = $JobrecordModel->getOne($cmap);
                
                if(!$guestinfo)
                {
                    
                    $data = array('status' => 1, 'msg' => '请求数据不存在');
                    return json_encode($data);
                    
                }
                
                $guestinfo['pstatus'] = abs($guestinfo['status']);
                
                
                if(abs($guestinfo['status'])>4)
                {
                    
                    $guestinfo['status_str'] = $status_arr[$guestinfo['status']];
                }else{
                    
                     $guestinfo['status_str'] = '处理';
                }
                
                
                $map['gid'] = $guestinfo['id'];
                
                
                
                $proguestlist = ProjobrecordModel::getlist($map);
                
                if($proguestlist)
                {
                    foreach ($proguestlist as $k=>$v)
                    {
                        
                        $proguestlist[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                    }
                    
                }
                
                $data = array('guestinfo'=>$guestinfo,'proguestlist'=>$proguestlist,'status'=>0,'msg'=>'请求数据正常');
            
                }else{
                   
                   $data = array('status'=>1,'msg'=>'Token异常');
                }
            
         return json_encode($data);
            
            
            
        }
        
    }
    
    
    
 public function sendagentorderdetail()
        {
        
            if(request()->isPost()){

                    $param = input('post.');
                    $agentuid = Token::getCurrentUid();
    
                    $status_arr = array(0=>'新提交',1=>'同意面试',-1=>'拒绝面试',2=>'面试成功',-2=>'面试失败',3=>'录用成功',-3=>'录用失败',4=>'试用成功',-4=>'试用失败',5=>'已完成',-5=>'订单失败',6=>'已完成/已分佣',-6=>'分佣失败');
                
                    $cmap['r.id'] = $param['id'];
                    $cmap['r.agentuid'] = $agentuid;
                    $JobrecordModel = new JobrecordModel();
                
                    $guestinfo = $JobrecordModel->getOne($cmap);
                
                    if(!$guestinfo)
                    {
                    
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
                        return json_encode($data);
                    
                    }
                
                    $guestinfo['pstatus'] = abs($guestinfo['status']);
                
                
                    if(abs($guestinfo['status'])>4)
                    {
                    
                        $guestinfo['status_str'] = $status_arr[$guestinfo['status']];
                    }else{
                    
                        $guestinfo['status_str'] = '处理';
                    }
                
                
                    $map['gid'] = $guestinfo['id'];
                
                
                
                    $proguestlist = ProjobrecordModel::getlist($map);
                
                    if($proguestlist)
                    {
                        foreach ($proguestlist as $k=>$v)
                        {
                        
                            $proguestlist[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                        }
                    
                    }
                
                    $data = array('guestinfo'=>$guestinfo,'proguestlist'=>$proguestlist,'status'=>0,'msg'=>'请求数据正常');
                
          
            
                return json_encode($data);
            
            
            
            }
        
        }

    
    /*
     * 提交评论
     */
        public function saveComment()
        {
            if(request()->isPost()) {
    
                $param = input('post.');
    
                $param['uid'] = Token::getCurrentUid();
    
                $JobrecordModel = new JobrecordModel();
    
                $jobrecordinfo = $JobrecordModel->getOne(array('r.id'=>$param['id'],'r.uid'=>$param['uid']));
                
                
                if($jobrecordinfo) {
                    $param_data['companyid'] = $jobrecordinfo['companyid'];
                    $param_data['content'] = $param['content'];
                    $param_data['score'] = $param['score'];
                    $param_data['pid'] = $param['id'];
                    $param_data['uid'] = $param['uid'];
                    $param_data['create_time'] = time();
                    $param_data['type'] = 0 ;
                    $param_data['piclist'] = isset($param['piclist']) ? $param['piclist'] : '';
                    if ($jobrecordinfo['status'] < 5) {
                    
                     $data = ['error'=>1,'msg'=>'没有权限评论'];
        
                    }elseif($jobrecordinfo['status'] == 6)
                    {
                        $data = ['error'=>1,'msg'=>'您已评论过'];
                    
                    }else{

                     //

                        $CommentModel = new CommentModel();

                        $data = $CommentModel->insertComment($param_data);
                        if($data['status'] == 0)
                        {

                            $JobrecordModel->updateJobrecord(array('id'=>$param['id'],'status'=>6));
                            $data['msg'] = '评价提交成功';
                        } else {
                            $data['msg'] = '评价提交失败';
                        }


                    }
                    
    
                }
                
                return json_encode($data);
            }
            
        }
    
          //面试同意|拒绝
    
    public function doAgree(){
        
        if(request()->isPost()){
        
            $param = input('post.');
            
           $msg = $this->isLogin();
                
                if($msg['error']  == 0 ) { 
    
            
                        $JobrecordModel = new JobrecordModel();
                        $companyid = $param['companyid'] = $msg['companyid'];
                        
                        $jobrecordinfo = $JobrecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));
            
                         $JobrecordModel->updateJobrecord($param);
            
                        if($param['status'] == 1 || $param['status'] == -1 )
                        {
                            
                            $id = $param['id'];
                            
                            
                            
                            $ProjobrecordModel = new ProjobrecordModel();
                            
                            $param_pro['gid'] = $id;
                            
                            if($param['status'] == 1 )
                            {
                            $param_pro['title'] = '同意面试';
                            
                            $param_pro['content'] = $param['content'].'【已同意面试】.';
                            
                            
                                $msg['content'] = '恭喜您，'.$jobrecordinfo['companyname'].'同意了您面试《'.$jobrecordinfo['jobtitle'].'》请求,请及时前往。';
                            }else{
                                $param_pro['title'] = '拒绝面试';
                            
                                $param_pro['content'] = $param['content'].'【已拒绝面试】.';
                                
                                $msg['content'] = '很遗憾，'.$jobrecordinfo['companyname'].'拒绝了您面试《'.$jobrecordinfo['jobtitle'].'》请求,再接再厉,海量职位等您来。';
                                
                            }
                            $param_pro['createtime'] = time();
                            
                            
                            $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);
                            
                            
                            
                               //发送给求职者
                            $msg['uid'] =  $jobrecordinfo['uid'];
                        
                            $msg['createtime'] = time();
                            
                            $sysmsgModel= new SysmsgModel();

                            $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                            $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                            $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                            $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                            $sysmsgModel->insertSysmsg($msg);


                        }else{

                            $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
                        }
            
                }else{
                    
                    $rdata = json_encode(array('status'=>1,'msg'=>'Token异常'));
                    
                }

            return $rdata;
        }
        
        
    }
    
    
    
    
        //面试通过|失败
    
         public function doInvatePass(){
        
        if(request()->isPost()){
            extract(input());
            $param = input('post.');

            $msg = $this->isLogin();
                
            if($msg['error']  == 0 ) { 
    
            
            $companyid = $param['companyid'] = $msg['companyid'];

            
            $InvaterecordModel = new JobrecordModel();
            
            $jobrecordinfo = $InvaterecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));

             $InvaterecordModel->updateJobrecord($param);
            
            if($param['status'] == 1 || $param['status'] == -1 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 1)
                {
                $param_pro['title'] = '面试通过';
                
                $param_pro['content'] = $param['content'].'【已通过面试】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》已通过,即将录用。';
                }else{
                    $param_pro['title'] = '面试失败';
                
                    $param_pro['content'] = $param['content'].'【面试失败】.';

                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未通过,再接再厉,海量职位等您来。。';



                }
                $param_pro['createtime'] = time();
                 $param_pro['type'] = 1;

                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);



                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];

                $msg['createtime'] = time();

                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);




            }else{
                
                $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
            }
            
            }else{
                
                $rdata = json_encode(array('status'=>1,'msg'=>'Token异常'));
            }

            return $rdata;
        }
        
        
    }
    
    
    
      //录用通过|失败
    
         public function doInvateTypein(){
        
        if(request()->isPost()){
            extract(input());
            $param = input('post.');


            $msg = $this->isLogin();
                
            if($msg['error']  == 0 ) { 
    
            
            $companyid = $param['companyid'] = $msg['companyid'];

            
            $InvaterecordModel = new JobrecordModel();
            
            $jobrecordinfo = $InvaterecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));

             $InvaterecordModel->updateJobrecord($param);
            
            if($param['status'] == 2 || $param['status'] == -2 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 2)
                {
                $param_pro['title'] = '录用成功';
                
                $param_pro['content'] = $param['content'].'【录用成功】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》录用成功,即将上班试用。';
                }else{
                    $param_pro['title'] = '录用失败';
                
                    $param_pro['content'] = $param['content'].'【录用失败】.';

                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未录用成功,再接再厉,海量职位等您来。。';



                }
                $param_pro['createtime'] = time();
                 $param_pro['type'] = 1;

                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);



                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];

                $msg['createtime'] = time();

                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);




            }else{

                $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
            }

            }else{
                
                
                 $rdata = json_encode(array('status'=>1,'msg'=>'Token异常'));
            }
            return $rdata;
        }
        
        
    }
    
    
    
    
    //试用通过|失败
    
         public function doInvateTry(){
        
        if(request()->isPost()){
            extract(input());
            $param = input('post.');


            $msg = $this->isLogin(); 
                
            if($msg['error']  == 0 ) { 
    
            
            $companyid = $param['companyid'] = $msg['companyid'];

            
            $InvaterecordModel = new JobrecordModel();
            
            $jobrecordinfo = $InvaterecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));

             $InvaterecordModel->updateJobrecord($param);
            
            if($param['status'] == 3 || $param['status'] == -3 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 3)
                {
                $param_pro['title'] = '试用成功';
                
                $param_pro['content'] = $param['content'].'【试用成功】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》试用成功,即将正式入职。';
                }else{
                    $param_pro['title'] = '试用失败';
                
                    $param_pro['content'] = $param['content'].'【试用失败】.';
                    
                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未试用成功,再接再厉,海量职位等您来。。';
                    
                
                    
                }
                $param_pro['createtime'] = time();
                 $param_pro['type'] = 1;

                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);



                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];

                $msg['createtime'] = time();

                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);



            }else{

                $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
            }
            }else{
                
                 $rdata = json_encode(array('status'=>1,'msg'=>'Token异常'));

            }

            return $rdata;
        }
        
        
    }
    
    
    
     //全部通过|失败
    
         public function doInvateDone(){
        
        if(request()->isPost()){

            $param = input('post.');


            $msg = $this->isLogin(); 
                
            if($msg['error']  == 0 ) { 
    
            
            $companyid = $param['companyid'] = $msg['companyid'];

            
            $InvaterecordModel = new JobrecordModel();
            
            $jobrecordinfo = $InvaterecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));

             $InvaterecordModel->updateJobrecord($param);
            
            if($param['status'] == 4 || $param['status'] == -4 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 4)
                {
                $param_pro['title'] = '完成正式入职';
                
                $param_pro['content'] = $param['content'].'【完成正式入职】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》完成正式入职。';
                }else{
                    $param_pro['title'] = '试用失败';
                
                    $param_pro['content'] = $param['content'].'【试用失败】.';
                    
                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未能正式入职,再接再厉,海量职位等您来。。';
                    
                
                    
                }
                $param_pro['createtime'] = time();
                
                $param_pro['type'] = 1;
                
                
                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);
                
                
                
                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];
            
                $msg['createtime'] = time();
                
                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);

                //发送订阅消息给求职者
                
                /*
                 $Subscribeinfo = SubscribeModel::getOne(array('uid'=>$msg['uid'],'type'=>'invate','status'=>0));
                  
                             
                if($Subscribeinfo)
                    {
                    
                      $AccessToken = new AccessToken();
                            
                           $temp_id = $Subscribeinfo['tmpid'];
                           $openid = $Subscribeinfo['openid'];
                           
                           
                          
                          
                         $msgdata = [
                        "thing5"=>[
                            'value' =>  '【'.$param_pro['title'] .'】'.$jobrecordinfo['companyname']
                        ],
                        "thing1"=>[
                            'value' =>   $jobrecordinfo['jobtitle']
                        ]
                     ];
                
                            
                         $AccessToken->sendmsg($temp_id,$openid,$msgdata);
                         
                   
                         
                         
                         $SubscribeModel= new SubscribeModel();
                         
                         $sparam['id'] = $Subscribeinfo['id'];
                         $sparam['status'] = 1;
                         
                         $SubscribeModel->updateSubscribe($sparam);
                            
                            
                    } 
                
                
        
                */
                
                
                $rdata = json_encode(array('status'=>0,'msg'=>'操作完成'));

                
            }else{
                
                $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
            }


            }else{
                
                $rdata = json_encode(array('status'=>1,'msg'=>'Token数据异常'));

            }
            return $rdata;
        }
        
        
    }
    
    
    
    //面试通过|失败
    
         public function doPass(){
        
        if(request()->isPost()){
            $param = input('post.');
            
            
            $msg = $this->isLogin();
                
                if($msg['error']  == 0 ) { 
    
            
                        $JobrecordModel = new JobrecordModel();
                        $companyid = $param['companyid'] = $msg['companyid'];
                        
                        $jobrecordinfo = $JobrecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));
            
                         $JobrecordModel->updateJobrecord($param);
                        
                        if($param['status'] == 2 || $param['status'] == -2 )
                        {
                            
                            $id = $param['id'];
                            
                            
                            
                            $ProjobrecordModel = new ProjobrecordModel();
                            
                            $param_pro['gid'] = $id;
                            
                            if($param['status'] == 2)
                            {
                            $param_pro['title'] = '面试通过';
                            
                            $param_pro['content'] = $param['content'].'【已通过面试】.';
                            
                            
                                $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》已通过,即将录用。';
                            }else{
                                $param_pro['title'] = '面试失败';
                            
                                $param_pro['content'] = $param['content'].'【面试失败】.';
                                
                                   $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未通过,再接再厉,海量职位等您来。。';
                                
                            
                                
                            }
                            $param_pro['createtime'] = time();


                            $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);



                               //发送给求职者
                            $msg['uid'] =  $jobrecordinfo['uid'];

                            $msg['createtime'] = time();

                            $sysmsgModel= new SysmsgModel();

                            $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                            $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                            $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                            $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                            $sysmsgModel->insertSysmsg($msg);






                        }else{
                            
                            $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
                        }
            
            
                }else{
                    
                        
                        $rdata = json_encode(array('status'=>1,'msg'=>'Token数据异常'));

                    
                }

            return $rdata;
        }
        
        
    }
    
    
    
      //录用通过|失败
    
         public function doTypein(){
        
        if(request()->isPost()){
            $param = input('post.');

            $msg = $this->isLogin();
                
            if($msg['error']  == 0 ) { 
            
            $JobrecordModel = new JobrecordModel();
            $companyid = $param['companyid'] = $msg['companyid'];
                        
            $jobrecordinfo = $JobrecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));

             $JobrecordModel->updateJobrecord($param);
            
            if($param['status'] == 3 || $param['status'] == -3 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 3)
                {
                $param_pro['title'] = '录用成功';
                
                $param_pro['content'] = $param['content'].'【录用成功】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》录用成功,即将上班试用。';
                }else{
                    $param_pro['title'] = '录用失败';
                
                    $param_pro['content'] = $param['content'].'【录用失败】.';
                    
                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未录用成功,再接再厉,海量职位等您来。。';
                    
                
                    
                }
                $param_pro['createtime'] = time();


                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);



                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];

                $msg['createtime'] = time();

                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);




            }else{

                $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
            }
            
        }else{
                    
                                    $rdata = json_encode(array('status'=>1,'msg'=>'Token数据异常'));

        }

            return $rdata;
        }
        
        
    }
    
    
    
    
    //试用通过|失败
    
         public function doTry(){
        
        if(request()->isPost()){

            $param = input('post.');

            $msg = $this->isLogin();
                
            if($msg['error']  == 0 ) { 
            
            
            $JobrecordModel = new JobrecordModel();
            $companyid = $param['companyid'] = $msg['companyid'];
            $jobrecordinfo = $JobrecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));
            $JobrecordModel->updateJobrecord($param);
            
            if($param['status'] == 4 || $param['status'] == -4 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 4)
                {
                $param_pro['title'] = '试用成功';
                
                $param_pro['content'] = $param['content'].'【试用成功】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》试用成功,即将正式入职。';
                }else{
                    $param_pro['title'] = '试用失败';
                
                    $param_pro['content'] = $param['content'].'【试用失败】.';
                    
                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未试用成功,再接再厉,海量职位等您来。。';
                    
                
                    
                }
                $param_pro['createtime'] = time();
                
                
                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);
                
                
                
                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];
            
                $msg['createtime'] = time();
                
                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);






            }else{

                $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
            }

            }else{
                
                    $rdata = json_encode(array('status'=>1,'msg'=>'Token数据异常'));

            }

            return $rdata;
        }
        
        
    }
    
    
    
     //全部通过|失败
    
         public function doDone(){
        
        if(request()->isPost()){
            extract(input());
            $param = input('post.');


            $msg = $this->isLogin();
                
            if($msg['error']  == 0 ) { 
            
            
            $JobrecordModel = new JobrecordModel();
            $companyid = $param['companyid'] = $msg['companyid'];
            $jobrecordinfo = $JobrecordModel->getOne(array('r.id'=>$param['id'],'r.companyid'=>$companyid));
            $JobrecordModel->updateJobrecord($param);
            
            if($param['status'] == 5 || $param['status'] == -5 )
            {
                
                $id = $param['id'];
                
                
                
                $ProjobrecordModel = new ProjobrecordModel();
                
                $param_pro['gid'] = $id;
                
                if($param['status'] == 5)
                {
                $param_pro['title'] = '完成正式入职';
                
                $param_pro['content'] = $param['content'].'【完成正式入职】.';
                
                
                    $msg['content'] = '恭喜您，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》完成正式入职。';
                }else{
                    $param_pro['title'] = '试用失败';
                
                    $param_pro['content'] = $param['content'].'【试用失败】.';
                    
                       $msg['content'] = '很遗憾，您面试的'.$jobrecordinfo['companyname'].'《'.$jobrecordinfo['jobtitle'].'》未能正式入职,再接再厉,海量职位等您来。。';
                    
                
                    
                }
                $param_pro['createtime'] = time();
                
                
                $rdata = $ProjobrecordModel->insertProjobrecord($param_pro);
                
                
                
                   //发送给求职者
                $msg['uid'] =  $jobrecordinfo['uid'];
            
                $msg['createtime'] = time();
                
                $sysmsgModel= new SysmsgModel();

                $msg['jobtitle'] = isset($jobrecordinfo['jobtitle']) ? $jobrecordinfo['jobtitle'] : '';
                $msg['money'] = isset($jobrecordinfo['money']) ? $jobrecordinfo['money'] : '';
                $msg['companyname'] = isset($jobrecordinfo['companyname']) ? $jobrecordinfo['companyname'] : '';
                $msg['jobcatename'] = isset($jobrecordinfo['jobcatename']) ? $jobrecordinfo['jobcatename'] : '';
                $sysmsgModel->insertSysmsg($msg);





                }else{
                    
                    $rdata = json_encode(array('status'=>1,'msg'=>'数据异常'));
                }
            
            }else{
                
                    $rdata = json_encode(array('status'=>1,'msg'=>'Token数据异常'));

                
            }

            return $rdata;
        }
        
        
    }
    
        
        
        /*
         * 获取某个经纪人推荐的企业列表
         *
         */
        
        public function getAgentCompanylist()
        {
            
            $cityid = input('post.cityid');
            
            $od = "g.sort desc ,g.createtime desc";
            //  $map['g.cityid']  = $cityid;
            $uid = Token::getCurrentUid();
            $map = [];
            $map['g.tid'] = $uid;
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
    
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $companyModel = new CompanyModel();
            
            $list = $companyModel->getCompanyByWhere($map, $Nowpage, $limits, $od);
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            $data = array('companylist' => $list, 'arealist' => $arealist);
            
            return json_encode($data);
        }
        
        
        /*
         *
         * 按企业名称模糊查询企业列表
         * @keyworkd传参post
         */
        
        public function getSearchCompany()
        {
            
            $keyword = input('post.keyword');
            
            
            $od = "g.sort desc, g.createtime desc";
            //  $map['g.cityid']  = $cityid;
            
            $map = [];
            $map['g.status'] = 1;
            $map['g.companyname'] = array('like', '%' . $keyword . '%');
            $limits = 100;
            $Nowpage = 1;
            
            
            $companyModel = new CompanyModel();
            
            $list = $companyModel->getCompanyByWhere($map, $Nowpage, $limits, $od);
            
            // $arealist =AreaModel::getAreaByCityid($cityid);
            $data = array('companylist' => $list);
            
            return json_encode($data);
        }
        
        
        /*
         * 获取某企业详情信息
         * return array  companyinfo
         * joblist:关联职位信息
         */
        
        public function getCompanydetail()
        {
    
    
            $msg = $this->isLogin();
            
            if($msg['error']  == 0) {
    
                $id = $msg['companyid'];
    
    
    
                $city = input('post.city');
    
                $cityinfo = CityModel::getCityByName($city);
    
                $cityid = $cityinfo['id'];
    
                $arealist = AreaModel::getAreaByCityid($cityid);
    
                $companyinfo = CompanyModel::getCompany($id);
    
                $mapgz = array('companyid' => $companyinfo['id']);
                $mygz = MygzModel::getMygzWhere($mapgz);
                if ($mygz) {
        
                    $companyinfo['isgz'] = 1;
        
                } else {
        
                    $companyinfo['isgz'] = 0;
                }
    
                $odjob = "j.sort desc";
                //  $mapjob['j.isrecommand']  = 1;
                $mapjob['j.companyid'] = $id;
                $mapjob['j.status'] = 1;
                $mapjob['j.ischeck'] = 1;
                $limits = 120;
                $Nowpage = 1;
    
    
                $JobModel = new JobModel();
    
                $joblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
                
                $CommentModel = new CommentModel();
                $map=[];
                $map['c.companyid'] = $id;
                $od = 'c.create_time DESC';
                
                $commentlist = $CommentModel->getCommentList($map,$od);
                
                
    
                if (!$companyinfo) {
                    throw new MissException(['msg' => '请求数据不存在', 'errorCode' => 40000]);
                }
    
                $data = json_encode(array('joblist' => $joblist, 'companyinfo' => $companyinfo, 'arealist' => $arealist,'commentlist'=>$commentlist));
    
                return $data;
    
            }else{
                
                return json_encode($msg);
            }
        }
        
        
        
        /*
         * 获取普通用户企业详情
         */
    
        public function getCompanyuserdetail()
        {
            
            
                $id = input('post.id');
                
                $city = input('post.city');
            
                $cityinfo = CityModel::getCityByName($city);
            
                $cityid = $cityinfo['id'];
            
                $arealist = AreaModel::getAreaByCityid($cityid);
            
                $companyinfo = CompanyModel::getCompany($id);
                
                  if (!$companyinfo) {
                        return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                }
            
                $mapgz = array('companyid' => $companyinfo['id']);
                $mygz = MygzModel::getMygzWhere($mapgz);
                if ($mygz) {
                
                    $companyinfo['isgz'] = 1;
                
                } else {
                
                    $companyinfo['isgz'] = 0;
                }
            
                $odjob = "j.sort desc";
                //  $mapjob['j.isrecommand']  = 1;
                $mapjob['j.companyid'] = $id;
                $mapjob['j.status'] = 1;
                $limits = 120;
                $Nowpage = 1;
            
            
                $JobModel = new JobModel();
            
                $joblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
            
              
                $CommentModel = new CommentModel();
                $map=[];
                $map['c.companyid'] = $id;
                $od = 'c.create_time DESC';
                
        
                $commentlist = $CommentModel->getCommentList($map,$od);
                $data = json_encode(array('joblist' => $joblist, 'companyinfo' => $companyinfo, 'arealist' => $arealist,'commentlist'=>$commentlist,'status'=>0,'msg'=>'请求数据正常'));
            
                return $data;
            
          
        }
        
        /*
         * 企业入驻,页面初始化信息包含行业、区域、当前城市
         *
         */
        public function getCompanyregisterinit()
        {
            
            
            $city = input('post.city');
            
            
            $cityinfo = CityModel::getCityByName($city);
            
            $cityid = $cityinfo['id'];
            
            
            $arealist = AreaModel::getAreaByCityid($cityid);
            
            $jobcatelist = JobcateModel::getJobcatelist();
            
            
            $data = array('jobcatelist' => $jobcatelist, 'arealist' => $arealist, 'cityinfo' => $cityinfo);
            
            
            return json_encode($data);
            
        }
        
        /*
         *
         * 保存企业从前端提交的信息
         */
        
        public function saveCompany()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                
                $account = array();
                
             //   $account['uid'] = $param['uid'];
                $account['name'] = $param['account'];
                


                $companyaccount = CompanyaccountModel::getCompanyLogin($account);
                
                if($companyaccount)
                {
                    
                     return json_encode(array('status' => 1, 'msg' => '企业登录账号重复,请换一个'));
                }
                
                

                
                $account['password'] = md5($param['password']);
                
                $tid = $param['tid'];
                
                
                $company = new CompanyModel();
                
               
                    
                    $maprole['isinit'] = 1;
                    
                    $companyrole = CompanyroleModel::getCompanyrole($maprole);//获取企业初始化套餐
                    
                    $param['roleid'] = $companyrole['id'];
                    $param['status'] = 0 ;
                    
                    $param['createtime'] = time();
                    $param['create_time'] = time();
                    $data = $company->insertCompany($param);
                    
                    
                  //  $companyinfo = CompanyModel::getCompanyByuid($param['uid']);
                    
                    $companyid = $account['companyid'] = $data['companyid'];
                    $account['createtime'] = time();
                    $account['status'] = 0;
                    
                    $companyaccountinfo = CompanyaccountModel::getCompanyaccountByuid($account['companyid']);
                    
                
                    
                    
                    if (!$companyaccountinfo) {
                        
                        //初始化赠送套餐
                        
                        if ($companyrole) {
                            
                            $CompanyRecordService = new CompanyRecordService($companyid);
                            
                            $CompanyRecordService->topnum = $companyrole['topnum'];
                            
                            $CompanyRecordService->notenum = $companyrole['notenum'];
                            
                            $CompanyRecordService->jobnum = $companyrole['jobnum'];
                            
                            $CompanyRecordService->mark = '赠送套餐';
                            
                            $CompanyRecordService->setFreeRecord();
                            
                        }
                        
                        
                        $companyaccount = new CompanyaccountModel();

                        $companyaccount->insertCompanyaccount($account);


                        $map['companyid'] = $account['companyid'];

                        // 设置当前用户为发布者
                        $uid = Token::getCurrentUid();
                        if ($uid) {
                            UserModel::ensureUserRoleField();
                            $userModel = new UserModel();
                            $userModel->updateUser(['id' => $uid, 'user_role' => 2]);
                        }

                        $data = array('status' => 0, 'msg' => '提交成功');
                        
                    } else {
                        
                        $data = array('status' => 1, 'msg' => '您已提交过企业信息');
                    }
                    
                    
            
                
                return json_encode($data);
            }
            
            
        }
        
        /*
         *
         * 前端企业编辑自己的企业信息接口
         *
         */
        
        public function updateCompany()
        {
            if (request()->isPost()) {
                
                $msg = $this->isLogin();
                if ($msg['error'] == 0) {
                    $param = input('post.');
                    $ctoken = $param['ctoken'];
                
                     $companyid = Token::getCurrentCid($ctoken);
                    
                    $param['id'] = $companyid;
        
                    $companyinfo = CompanyModel::getCompany($companyid);
        
                    if ($companyinfo) {
            
            
                        $company = new CompanyModel();
                        $company->updateCompany($param);
            
            
                        $data = array('status' => 0, 'msg' => '编辑成功');
                        //账号不更新
                    } else {
            
            
                        $data = array('status' => 1, 'msg' => '编辑失败');
                    }
        
        
                    return json_encode($data);
                    
                }else{
    
                    return json_encode($msg);
                }
            }
    
          
            
        }
        
        public function checkLogin()
        {
            if (request()->isPost()) {
                $ctoken = input('post.ctoken');
    

                $valid = TokenService::verifyToken($ctoken);
                if($valid)
                {
                    $companyid = Token::getCurrentCid($ctoken);
                    
                    if($companyid)
                        $data = array('error' => 0, 'msg' => '正常');
                    else
                        $data = array('error' => 1, 'msg' => '数据异常');
                    
                }else{
    
                    $data = array('error' => 1, 'msg' => 'Token异常');
                }
    
    
                return json_encode($data);
            
                }
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
        
        /*
         * 企业登录接口
         *
         */
        public function doLogin()
        {
            
            if (request()->isPost()) {
                
                $name = input('post.name');
                $password = input('post.password');
                
                $param['name'] = $name;
                $param['password'] = md5($password);
                
                $companyaccount = CompanyaccountModel::getCompanyLogin($param);
                
                if ($companyaccount) {
                    
                    if ($companyaccount['status'] == 1) {

                        $CompanyToken = new CompanyToken();
                        $ctoken = $CompanyToken->get($companyaccount);

                        // 设置当前用户为发布者
                        $uid = Token::getCurrentUid();
                        if ($uid) {
                            UserModel::ensureUserRoleField();
                            $userModel = new UserModel();
                            $userModel->updateUser(['id' => $uid, 'user_role' => 2]);
                        }

                        $data = array('error' => 0, 'companyid' => $companyaccount['companyid'],'ctoken'=>$ctoken);
                        
                    } else {
                        
                        $data = array('error' => 1, 'msg' => '账号正在审核中...');
                    }
                    
                } else {
                    
                    $data = array('error' => 1, 'msg' => '账号不存在，请先入驻...');
                    
                }
                
                return json_encode($data);
                
                
            }
            
        }
        
        /*
         * 根据uid自动登录企业账号
         */
        public function autoCompanyLogin()
        {
            if (request()->isPost()) {
                try {
                    $uid = Token::getCurrentUid();
                } catch (\Exception $e) {
                    return json_encode(['error' => 1, 'msg' => '用户未登录']);
                }

                if (!$uid) {
                    return json_encode(['error' => 1, 'msg' => '用户未登录']);
                }

                // 通过 companyaccount.uid 查找
                $companyaccount = CompanyaccountModel::where('uid', $uid)->where('status', 1)->find();

                if (!$companyaccount) {
                    // 通过 company.uid 查找对应的 companyaccount
                    $company = CompanyModel::getCompanyByuid($uid);
                    if ($company) {
                        $companyaccount = CompanyaccountModel::getCompanyaccountByuid($company['id']);
                    }
                }

                if ($companyaccount && $companyaccount['status'] == 1) {
                    $CompanyToken = new CompanyToken();
                    $ctoken = $CompanyToken->get($companyaccount);
                    return json_encode(['error' => 0, 'companyid' => $companyaccount['companyid'], 'ctoken' => $ctoken]);
                }

                return json_encode(['error' => 1, 'msg' => '未关联企业账号']);
            }
        }

        /*
         * 企业下架职位接口
         */
        
        public function cancleJob()
        {
            
            
            if (request()->isPost()) {
                
                $param = input('post.');

                $msg = $this->isLogin();
                
                if($msg['error']  == 0 ) { 
    
                    $companyid = $msg['companyid'];
                    $map =[];
                    $map['id'] = $param['id'];
                    $map['companyid'] = $companyid;
                    $jobinfo = JobModel::getOne($map);
                    if(!$jobinfo)
                        {
                            
                            $data = array('status' => 1, 'msg' => '请求数据不存在');
                            
                            return json_encode($data);
                            
                        }
                        
                    $params['status'] = 0;
                    $params['companyid'] = $companyid;
                    $params['id'] = $param['id'];
                        
                    $JobModel = new JobModel();
                    $JobModel->updateJob($params);
                    
                    $data = array('status' => 0, 'msg' => '下架成功');
                
                }else{
                    
                     $data = array('status' => 1, 'msg' => 'Token异常');
                }
                
                
                return json_encode($data);
            }
            
            
        }
        
        
        /*
        * 企业职位置顶接口
        */
        
        public function topJob()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                $msg = $this->isLogin();
                
                // var_dump($msg);
                
                if($msg['error'] ==0){
                    
                    $companyid = $map['companyid'] = $msg['companyid'];
                    $jmap = [];
                    
                    $jmap['id'] = $param['id'];
                    $jmap['companyid'] = $companyid;
                    
                    $jobinfo = JobModel::getOne($jmap);
                    
                    if(!$jobinfo){
                            
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
                        return json_encode($data);
                    }
                
                
                    if ($jobinfo->toptime < time()) {
                        
                        
                        $CompanyRecordService = new CompanyRecordService($map['companyid']);
                        $list = $CompanyRecordService->GetFirstRecord();
                        
                        if ($list) {
                            
                            $topnum = $list[0]['totaltopnum'];
                            
                        } else {
                            
                            $topnum = 0;
                        }
                        
                        
                        if ($topnum <= 0) {
                            $data = array('status' => 1, 'msg' => '置顶次数余额不足');
                            
                        } else {
                            
                            $params['toptime'] = time() + 24 * 60 * 60;
                            
                            $params['id'] = $param['id'];
                            $params['companyid'] = $companyid;
                            
                            $JobModel = new JobModel();
                            
                            $JobModel->updateJob($params);
                            
                            $CompanyRecordService->topnum = -1;
                            
                            $CompanyRecordService->SetTopNumRecord();
                            
                            $data = array('status' => 0, 'msg' => '置顶成功');
                        }
                        
                        
                    } else {
                        $data = array('status' => 1, 'msg' => '当前置顶未结束');
                        
                    }
                }else{

                     $data = array('status' => 1, 'msg' => 'Token异常');
                }
                
                return json_encode($data);
            }


        }

        public function spreadJob()
        {
            if (request()->isPost()) {

                $param = input('post.');
                $msg = $this->isLogin();

                if ($msg['error'] == 0) {

                    $companyid = $msg['companyid'];
                    $jmap = [];
                    $jmap['id'] = $param['id'];
                    $jmap['companyid'] = $companyid;

                    $jobinfo = JobModel::getOne($jmap);

                    if (!$jobinfo) {
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
                        return json_encode($data);
                    }

                    $map['companyid'] = $companyid;
                    $CompanyRecordService = new CompanyRecordService($companyid);
                    $list = $CompanyRecordService->GetFirstRecord();

                    $spreadnum = 0;
                    if ($list) {
                        $spreadnum = isset($list[0]['totalspreadnum']) ? $list[0]['totalspreadnum'] : 0;
                    }

                    if ($spreadnum <= 0) {
                        $data = array('status' => 1, 'msg' => '加急扩散次数余额不足');
                    } else {
                        $params = [];
                        $params['spreadtime'] = time() + 24 * 60 * 60;
                        $params['id'] = $param['id'];
                        $params['companyid'] = $companyid;

                        $JobModel = new JobModel();
                        $JobModel->updateJob($params);

                        $CompanyRecordService->spreadnum = -1;
                        $CompanyRecordService->SetSpreadNumRecord();

                        $data = array('status' => 0, 'msg' => '加急扩散成功');
                    }

                } else {
                    $data = array('status' => 1, 'msg' => 'Token异常');
                }

                return json_encode($data);
            }
        }

        public function doCompanyendtime()
        {
            if (request()->isPost()) {

                $param = input('post.');
                $msg = $this->isLogin();

                if ($msg['error'] == 0) {

                    $companyid = $msg['companyid'];
                    $jmap = [];
                    $jmap['id'] = $param['id'];
                    $jmap['companyid'] = $companyid;

                    $jobinfo = JobModel::getOne($jmap);

                    if (!$jobinfo) {
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
                        return json_encode($data);
                    }

                    $params = [];
                    $params['id'] = $param['id'];
                    $params['companyid'] = $companyid;
                    $params['endtime'] = time() + 24 * 60 * 60;

                    $JobModel = new JobModel();
                    $JobModel->updateJob($params);

                    $data = array('status' => 0, 'msg' => '刷新成功');

                } else {
                    $data = array('status' => 1, 'msg' => 'Token异常');
                }

                return json_encode($data);
            }
        }

        /*
         *
         * 企业上架职位接口
         *
         */


    public function upJob()
            {
                
                
                if (request()->isPost()) {
                    
                    $param = input('post.');
    
                    $msg = $this->isLogin();
                    
                    if($msg['error']  == 0 ) { 
        
                        $companyid = $msg['companyid'];
                        $map =[];
                        $map['id'] = $param['id'];
                        $map['companyid'] = $companyid;
                        $jobinfo = JobModel::getOne($map);
                        if(!$jobinfo)
                            {
                                
                                $data = array('status' => 1, 'msg' => '请求数据不存在');
                                
                                return json_encode($data);
                                
                            }
                            
                        $params['status'] = 1;
                        $params['companyid'] = $companyid;
                        $params['id'] = $param['id'];
                            
                        $JobModel = new JobModel();
                        $JobModel->updateJob($params);
                        
                        $data = array('status' => 0, 'msg' => '上架成功');
                    
                    }else{
                        
                         $data = array('status' => 1, 'msg' => 'Token异常');
                    }
                    
                    
                    return json_encode($data);
                }
                
                
            }
        
        /*
         *
         * 用户关注企业接口
         */
        
        public function gzCompany()
        {
            if (request()->isPost()) {
                
                $param = input('post.');
                $param['uid'] = Token::getCurrentUid();
                $map['companyid'] = $param['companyid'];
                $map['uid'] = $param['uid'];
                
                
                $mygz = MygzModel::getMygzWhere($map);
                
                if ($mygz) {
                    
                    $mygzmodel = new MygzModel();
                    $mygzmodel->delMygz($map);
                    
                    $data = json_encode(array('status' => 2, 'msg' => '取消关注'));
                    
                } else {
                    
                    $param['createtime'] = time();
                    
                    $mygz = new MygzModel();
                    
                    $data = $mygz->myGzSave($param);
                }
                
                
                return $data;
            }
            
            
        }
        
        /*
         * 获取用户关注企业列表
         */
        
        public function myGzCompany()
        {
            if (request()->isPost()) {
    
                $Nowpage = input('post.page');
                if ($Nowpage) $Nowpage = $Nowpage; else
                    $Nowpage = 1;
    
                $limits = $Nowpage * 10;
                $Nowpage = 1;
                
                $uid = Token::getCurrentUid();
                $od = "r.createtime desc";
                $map['r.uid'] = $uid;
  
                $mygz = new MygzModel();
                $mygzlist = $mygz->getGzCompanyList($map, $Nowpage, $limits, $od);
                
                $data = array('mygzlist' => $mygzlist);
                return json_encode($data);
            }
            
            
        }
        
        /*
         * 企业中心初始化数据
         */
        public function companyCenter()
        {
            
            
            if (request()->isPost()) {
                
                $ctoken = input('post.ctoken');
                
                $id = Token::getCurrentCid($ctoken);
                
            
                $uid = Token::getCurrentUid();
                $companyinfo = CompanyModel::getCompany($id);
                
                if(!$companyinfo)
                    {
                        
                        $data = array('status' => 1, 'msg' => '请求数据不存在');
                        
                        return json_encode($data);
                        
                    }
                // 
                $maprole['id'] = $companyinfo['roleid'];
                
                $companyrole = CompanyroleModel::getCompanyrole($maprole);
                
                $map['companyid'] = $id;
                $companyrecordlist = CompanyrecordModel::getCompanyrecordPoP($map);//获取最近一条企业发布余额记录
                if (!$companyrecordlist) {

                    $totaljobnum = 0;
                    $totalnotenum = 0;
                    $totaltopnum = 0;
                    $totalspreadnum = 0;
                } else {

                    $totaljobnum = $companyrecordlist[0]['totaljobnum'];

                    $totalnotenum = $companyrecordlist[0]['totalnotenum'];
                    $totaltopnum = $companyrecordlist[0]['totaltopnum'];
                    $totalspreadnum = isset($companyrecordlist[0]['totalspreadnum']) ? $companyrecordlist[0]['totalspreadnum'] : 0;

                }
                // var_dump($companyinfo);exit();

                $data = array('companyinfo' => $companyinfo, 'totaljobnum' => $totaljobnum, 'totalnotenum' => $totalnotenum, 'totaltopnum' => $totaltopnum, 'totalspreadnum' => $totalspreadnum, 'companyrole' => $companyrole, 'coin_balance' => CoinrecordModel::getBalance($uid, 1), 'status' => 0, 'msg' => '请求数据正常');
                return json_encode($data);
            }
            
            
        }
        
        
        /*
         *
         * 企业中管理管理下的职位列表
         *
         */
        
        public function companyJob()
        {
            
            
            if (request()->isPost()) {
                
                
                $msg = $this->isLogin();

                if($msg['error']  == 0 ) {

                    $companyid = $msg['companyid'];

                    $Nowpage = input('post.page');
                    if ($Nowpage) $Nowpage = $Nowpage; else
                        $Nowpage = 1;

                    $limits = $Nowpage * 10;
                    $Nowpage = 1;

                    $odjob = "j.createtime desc";
                    $mapjob['j.companyid'] = $companyid;

                    $filterStatus = input('post.filterStatus');
                    if ($filterStatus !== null && $filterStatus >= 0) {
                        if ($filterStatus == 0) {
                            $mapjob['j.ischeck'] = 0;
                        } elseif ($filterStatus == 1) {
                            $mapjob['j.ischeck'] = 1;
                            $mapjob['j.status'] = 1;
                        } elseif ($filterStatus == 2) {
                            $mapjob['j.status'] = 0;
                        }
                    }

                    $JobModel = new JobModel();
                    $joblist = $JobModel->getJobByWhere($mapjob, $Nowpage, $limits, $odjob);
    
    
                    $data = array('joblist' => $joblist);
                    return json_encode($data);
    
                }else{
                    return json_encode($msg);
                    
                }
            }
            
            
        }
        
        
        /*
         *
         * 企业邀请求职者面试接口
         */
        
        public function inviteNote()
        {
            
            
            if (request()->isPost()) {
                
    
                $msg = $this->isLogin();
                
                if($msg['error'] == 0 )
                {
                
                
                $id = input('post.id');
                
                $companyid = $msg['companyid'];
                $map['id'] = $id;
                $map['companyid'] = $companyid;
                $jobrecord = JobrecordModel::getJobrecordWhere($map);
                
                if($jobrecord)
                {
                
                
                    if ($jobrecord['status'] == 0) {
                        
                        $jobrecordModel = new JobrecordModel();
                        
                        $param['id'] = $id;
                        
                        $param['status'] = 1;
                        
                        
                        $flag = $jobrecordModel->saveJobrecord($param);
                        
                        if ($flag) {
                            $data = array('status' => 0, 'msg' => '邀请成功');
                        } else {
                            
                            
                            $data = array('status' => 1, 'msg' => '邀请失败');
                        }
                    } else {
                        
                        
                        $data = array('status' => 1, 'msg' => '已邀请过');
                    }
                }else{
                    
                    $data = array('status' => 1, 'msg' => '请求数据不存在');
                }
                
                
                
                return json_encode($data);
            }else{
                    $data = array('status' => 2, 'msg' => '企业未登录');
                    return json_encode($data);
                }
            
            }
        }
        
        
        /*
        *
        * 获取企业邀请求职者列表
        */
        
        public function companyNote()
        {
            
            
            if (request()->isPost()) {
                
                $msg = $this->isLogin();
    
                if($msg['error']  == 0 ) {
    
                    $companyid = $msg['companyid'];
                    $uid = Token::getCurrentUid();
    
                    $Nowpage = input('post.page');
                    if ($Nowpage) $Nowpage = $Nowpage; else
                        $Nowpage = 1;
    
                    $limits = $Nowpage * 10;
                    $Nowpage = 1;
                    
                    
                    $od = "r.createtime desc";
                    $map['r.companyid'] = $companyid;

                    // 支持按 jobid 筛选
                    $filterJobid = input('post.jobid');
                    if ($filterJobid) {
                        $map['r.jobid'] = $filterJobid;
                    }

                    // 支持按状态筛选
                    $filterStatus = input('post.filterStatus');
                    if ($filterStatus !== null && $filterStatus !== '' && $filterStatus != -1) {
                        $map['r.status'] = intval($filterStatus);
                    }

                    $Jobrecord = new JobrecordModel();
                    $notelist = $Jobrecord->getMyFindList($map, $Nowpage, $limits,$od);
                    
                      $status_arr = array(0=>'新提交',1=>'同意面试',-1=>'拒绝面试',2=>'面试成功',-2=>'面试失败',3=>'录用成功',-3=>'录用失败',4=>'试用成功',-4=>'试用失败',5=>'已完成',-5=>'订单失败',6=>'已完成/已分佣',-6=>'分佣失败');
                      
                      
        
                    if($notelist)
                        {

                            foreach ($notelist as $k=>$v)
                            {


                                $notelist[$k]['status_str'] =$status_arr[$v['status']];

                                // 获取报名者评分
                                $avgScore = \think\Db::name('zpwxsys_comment')
                                    ->where('uid', $v['uid'])
                                    ->avg('score');
                                $notelist[$k]['rating'] = $avgScore ? round($avgScore, 1) : 0;


                            }

                        }
    
    
                    $jobinfo = null;
                    if ($filterJobid) {
                        $jobinfo = \think\Db::name('zpwxsys_job')
                            ->where('id', $filterJobid)
                            ->field('id,jobtitle,num,work_start_date,work_end_date,work_start_time,work_end_time')
                            ->find();
                    }

                    $data = array('notelist' => $notelist, 'jobinfo' => $jobinfo);
                    return json_encode($data);
    
                }else{
    
                    return json_encode($msg);
                }
            }
            
            
        }
        
        public function delComment()
        {
            if (request()->isPost()) {
                 $uid = Token::getCurrentUid();
                $id = input('post.id');
                $map = [];
                $map['id'] = $id;
                $map['uid']=$uid;
                
                $commentinfo = CommentModel::getOne($map);
                
                if(!$commentinfo)
                {
                        
                        $data = ['error'=>1,'请求数据不存在'];
                        
                        return json_encode($data);
                }
                
                $CommentModel = new CommentModel();
               $res =  $CommentModel->delComment($map);
               
               if($res)
               {
                   $data = ['error'=>0,'删除成功'];
               }else{
    
                   $data = ['error'=>1,'删除失败'];
               }
               
               return json_encode($data);

                
            }
            
        }
        
        public function getMycomment()
        {
            $uid = Token::getCurrentUid();
            $commentType = input('post.commentType');

            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;

            $limits = $Nowpage * 10;
            $Nowpage = 1;

            $commentlist = [];
            $pendinglist = [];

            // 已评价列表 (commentType == -1 全部 或 commentType == 1 已评价)
            if ($commentType == -1 || $commentType == 1 || $commentType === null) {
                $map = [];
                $map['c.uid'] = $uid;
                $od = 'c.create_time DESC';
                $CommentModel = new CommentModel();
                $commentlist = $CommentModel->getCommentByWhere($map, $Nowpage, $limits, $od);
            }

            // 待评价列表 (commentType == -1 全部 或 commentType == 0 待评价)
            if ($commentType == -1 || $commentType == 0) {
                $mapPending = [];
                $mapPending['r.uid'] = $uid;
                $mapPending['r.status'] = 5;
                $Jobrecord = new JobrecordModel();
                $pendinglist = $Jobrecord->getMyFindList($mapPending, $Nowpage, $limits, 'r.createtime desc');
            }

            $data = array('commentlist' => $commentlist, 'pendinglist' => $pendinglist);
            return json_encode($data);
        }
        
        
        public function batchApplicant()
        {
            if (request()->isPost()) {
                $msg = $this->isLogin();

                if ($msg['error'] == 0) {
                    $ids = input('post.ids');
                    $action = input('post.action');

                    if (!$ids || !$action) {
                        return json_encode(array('status' => 1, 'msg' => '参数不完整'));
                    }

                    $idArr = explode(',', $ids);
                    $Jobrecord = new JobrecordModel();

                    if ($action == 'accept') {
                        $newStatus = 1;
                        $msgText = '批量同意成功';
                    } elseif ($action == 'reject') {
                        $newStatus = -1;
                        $msgText = '批量拒绝成功';
                    } else {
                        return json_encode(array('status' => 1, 'msg' => '无效操作'));
                    }

                    foreach ($idArr as $id) {
                        $id = intval($id);
                        if ($id > 0) {
                            // 跳过已处理的记录，防止重复提交
                            $currentStatus = $Jobrecord->where(['id' => $id])->value('status');
                            if ($currentStatus !== null && $currentStatus >= 1) {
                                continue;
                            }
                            $Jobrecord->where(['id' => $id])->update(['status' => $newStatus]);

                            // 录用后自动加入群组
                            if ($action == 'accept') {
                                $record = JobrecordModel::getJobrecordWhere(['id' => $id]);
                                if ($record) {
                                    $groupInfo = ChatgroupModel::where('jobid', $record['jobid'])->where('status', 1)->find();
                                    if ($groupInfo) {
                                        // 检查是否已在群内
                                        $existMember = ChatmemberModel::getOne(['groupid' => $groupInfo['id'], 'uid' => $record['uid']]);
                                        if (!$existMember) {
                                            $chatmember = new ChatmemberModel();
                                            $chatmember->addMember([
                                                'groupid' => $groupInfo['id'],
                                                'uid' => $record['uid'],
                                                'role' => 0,
                                                'agreed_rule' => 0,
                                                'jointime' => time()
                                            ]);

                                            // 群内系统通知
                                            $userInfo = UserModel::get($record['uid']);
                                            $userName = $userInfo ? ($userInfo['nickname'] ?: $userInfo['wechaname'] ?: '新成员') : '新成员';
                                            $chatmsg = new ChatmessageModel();
                                            $chatmsg->sendMessage([
                                                'groupid' => $groupInfo['id'],
                                                'uid' => 0,
                                                'content' => $userName . ' 已被录用，加入了群聊',
                                                'msg_type' => 1,
                                                'createtime' => time()
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    return json_encode(array('status' => 1, 'msg' => $msgText));
                } else {
                    return json_encode(array('status' => 0, 'msg' => 'Token异常'));
                }
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

        public function getSigninList()
        {
            if (request()->isPost()) {
                $msg = $this->isLogin();

                if ($msg['error'] == 0) {
                    $companyid = $msg['companyid'];
                    $filterType = input('post.filterType');

                    $Nowpage = input('post.page');
                    if (!$Nowpage) $Nowpage = 1;
                    $limits = $Nowpage * 10;
                    $Nowpage = 1;

                    $map = [];
                    $map['j.companyid'] = $companyid;
                    $map['j.ischeck'] = 1;

                    if ($filterType !== null && $filterType >= 0) {
                        $map['j.status'] = $filterType;
                    }

                    $JobModel = new JobModel();
                    $joblist = $JobModel->alias('j')
                        ->field('j.id, j.jobtitle, j.status, j.createtime, j.num')
                        ->where($map)
                        ->order('j.createtime desc')
                        ->limit(0, $limits)
                        ->select();

                    if ($joblist) {
                        $Jobrecord = new JobrecordModel();
                        foreach ($joblist as $k => $v) {
                            $joblist[$k]['createtime'] = date('Y-m-d', $v['createtime']);

                            // 录用人数（status >= 1）
                            $notecount = $Jobrecord->where('jobid', $v['id'])->where('status', '>=', 1)->count();
                            $joblist[$k]['notecount'] = $notecount;

                            // 已签到人数
                            $signin_count = $Jobrecord->where(['jobid' => $v['id'], 'signed_in' => 1])->count();
                            $joblist[$k]['signin_count'] = $signin_count;

                            // 已签退人数 (status=5 且有签退时间)
                            $signout_count = $Jobrecord->where(['jobid' => $v['id'], 'status' => 5])
                                ->where('signout_time', '<>', '')
                                ->count();
                            $joblist[$k]['signout_count'] = $signout_count;
                        }
                    }

                    return json_encode(array('joblist' => $joblist ?: []));
                } else {
                    return json_encode(array('status' => 0, 'msg' => 'Token异常', 'joblist' => []));
                }
            }
        }

        public function getSigninDetail()
        {
            if (request()->isPost()) {
                $msg = $this->isLogin();

                if ($msg['error'] == 0) {
                    $companyid = $msg['companyid'];
                    $jobid = input('post.jobid');

                    $Nowpage = input('post.page');
                    if (!$Nowpage) $Nowpage = 1;
                    $limits = $Nowpage * 10;
                    $Nowpage = 1;

                    // 获取职位信息
                    $jmap = [];
                    $jmap['id'] = $jobid;
                    $jmap['companyid'] = $companyid;
                    $jobinfo = JobModel::getOne($jmap);

                    if (!$jobinfo) {
                        return json_encode(array('status' => 0, 'msg' => '职位不存在', 'recordlist' => [], 'jobinfo' => []));
                    }

                    $jobinfoArr = [];
                    $jobinfoArr['jobtitle'] = $jobinfo['jobtitle'];
                    $jobinfoArr['id'] = $jobinfo['id'];

                    // 获取签到记录
                    $rmap = [];
                    $rmap['r.jobid'] = $jobid;

                    $Jobrecord = new JobrecordModel();
                    $rmap['r.status'] = ['>=', 1];
                    $recordlist = $Jobrecord->alias('r')
                        ->join('zpwxsys_user u', 'r.uid = u.id', 'LEFT')
                        ->join('zpwxsys_comment c', 'c.pid = r.id', 'LEFT')
                        ->field('r.id, r.uid, r.status, r.signin_time, r.signout_time, r.signed_in, r.createtime, u.nickname, u.avatarUrl, c.score AS comment_score')
                        ->where($rmap)
                        ->order('r.createtime desc')
                        ->limit(0, $limits)
                        ->select();

                    if ($recordlist) {
                        foreach ($recordlist as $k => $v) {
                            $recordlist[$k]['createtime'] = date('Y-m-d H:i', $v['createtime']);

                            // 计算工时
                            if (!empty($v['signin_time']) && !empty($v['signout_time'])) {
                                $inTime = strtotime($v['signin_time']);
                                $outTime = strtotime($v['signout_time']);
                                $diff = $outTime - $inTime;
                                $hours = round($diff / 3600, 1);
                                $recordlist[$k]['work_hours'] = $hours . '小时';
                            } else {
                                $recordlist[$k]['work_hours'] = '';
                            }

                            // 评价结果
                            if ($v['comment_score'] !== null) {
                                $recordlist[$k]['eval_str'] = intval($v['comment_score']) >= 3 ? '好' : '差';
                            } else {
                                $recordlist[$k]['eval_str'] = '-';
                            }
                        }
                    }

                    // 统计（只统计已录用的记录 status >= 1）
                    $notecount = $Jobrecord->where('jobid', $jobid)->where('status', '>=', 1)->count();
                    $signin_count = $Jobrecord->where(['jobid' => $jobid, 'signed_in' => 1])->count();
                    $signout_count = $Jobrecord->where(['jobid' => $jobid, 'status' => 5])
                        ->where('signout_time', '<>', '')
                        ->count();

                    // 计算总工时
                    $total_work_hours = 0;
                    $completedRecords = $Jobrecord->where(['jobid' => $jobid])
                        ->where('signin_time', '<>', '')
                        ->where('signout_time', '<>', '')
                        ->field('signin_time, signout_time')
                        ->select();
                    if ($completedRecords) {
                        foreach ($completedRecords as $cr) {
                            $diff = strtotime($cr['signout_time']) - strtotime($cr['signin_time']);
                            if ($diff > 0) {
                                $total_work_hours += $diff;
                            }
                        }
                    }
                    $total_work_hours = round($total_work_hours / 3600, 1);

                    $jobinfoArr['notecount'] = $notecount;
                    $jobinfoArr['signin_count'] = $signin_count;
                    $jobinfoArr['signout_count'] = $signout_count;
                    $jobinfoArr['total_work_hours'] = $total_work_hours . '小时';

                    return json_encode(array('jobinfo' => $jobinfoArr, 'recordlist' => $recordlist ?: []));
                } else {
                    return json_encode(array('status' => 0, 'msg' => 'Token异常', 'recordlist' => [], 'jobinfo' => []));
                }
            }
        }

        public function batchSigninAction()
        {
            if (request()->isPost()) {
                $msg = $this->isLogin();
                if ($msg['error'] == 0) {
                    $companyid = $msg['companyid'];
                    $jobid = input('post.jobid');
                    $action = input('post.action'); // confirmWork / signIn / signOut

                    // 验证岗位归属
                    $job = JobModel::getOne(['id' => $jobid, 'companyid' => $companyid]);
                    if (!$job) {
                        return json_encode(['status' => 0, 'msg' => '职位不存在']);
                    }

                    $affectedUids = [];
                    $recordTable = config('database.prefix') . 'zpwxsys_jobrecord';

                    // 确定 signin_phase 值和通知内容
                    $phaseMap = [
                        'confirmWork' => 1,
                        'signIn'      => 2,
                        'signOut'     => 3
                    ];
                    $msgMap = [
                        'confirmWork' => '发布者已发起工作确认，请前往「我的报名」完成确认',
                        'signIn'      => '发布者已发起签到，请前往「我的报名」完成签到',
                        'signOut'     => '发布者已发起签退，请前往「我的报名」完成签退'
                    ];

                    if (!isset($phaseMap[$action])) {
                        return json_encode(['status' => 0, 'msg' => '无效操作']);
                    }

                    // 查询需要通知的报名者（不修改 jobrecord 状态，等报名者自己操作）
                    if ($action == 'confirmWork') {
                        $affectedUids = Db::table($recordTable)->where(['jobid' => $jobid, 'status' => 1])
                            ->column('uid');
                    } elseif ($action == 'signIn') {
                        $affectedUids = Db::table($recordTable)->where(['jobid' => $jobid, 'status' => 4, 'signed_in' => 0])
                            ->column('uid');
                    } elseif ($action == 'signOut') {
                        $affectedUids = Db::table($recordTable)->where(['jobid' => $jobid, 'status' => 4, 'signed_in' => 1])
                            ->column('uid');
                    }

                    // 仅更新 job 的 signin_phase（先确保字段存在）
                    JobModel::ensureZwFields();
                    Db::table(config('database.prefix') . 'zpwxsys_job')
                        ->where(['id' => $jobid])
                        ->update(['signin_phase' => $phaseMap[$action]]);

                    // 批量写入系统消息通知受影响用户
                    if (!empty($affectedUids) && isset($msgMap[$action])) {
                        SysmsgModel::ensureNewFields();
                        foreach ($affectedUids as $uid) {
                            $sysmsg = new SysmsgModel();
                            $sysmsg->allowField(true)->save([
                                'uid'        => $uid,
                                'content'    => $msgMap[$action],
                                'createtime' => time(),
                                'status'     => 0,
                                'jobtitle'   => $job['jobtitle'],
                                'link'       => '/pages/myfind/index'
                            ]);
                        }
                    }

                    return json_encode(['status' => 1, 'msg' => '操作成功', 'notified' => count($affectedUids)]);
                } else {
                    return json_encode(['status' => 0, 'msg' => 'Token异常']);
                }
            }
        }


    }