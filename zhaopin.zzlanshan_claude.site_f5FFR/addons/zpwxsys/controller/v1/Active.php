<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Active as ActiveModel;
    use addons\zpwxsys\model\Activerecord as ActiverecordModel;
    use addons\zpwxsys\service\Token;
    use addons\zpwxsys\service\Token as TokenService;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;
    
    /*
     *
     * 招聘会相关接口
     */
    
    class Active extends BaseController
    {
    
    
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
        /**
         * 获取招聘会列表
         * @url /v1.Acitve/getActivelist
         * @return array of activelist
         *
         */
        public function getActivelist()
        {
            
            $od = "sort desc,createtime desc";
            $map['status'] = 1;
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
    
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            $ActiveModel = new ActiveModel();
            
            
            $activelist = $ActiveModel->getActiveByWhere($map, $Nowpage, $limits, $od);
            
            $data = array('activelist' => $activelist,
            
            );
            
            return json_encode($data);
            
        }
        
        
        /**
         * 获取招聘会详情方法
         * @url /v1.Acitve/getActivedetail
         * @return array of activeinfo
         * @throws MissException
         */
        
        public function getActivedetail()
        {
            
            $id = input('post.id');
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            $map = array('id' => $id);
            
            $ActiveModel = new ActiveModel();
            
            
            $activeinfo = $ActiveModel->getActiveDetail($map);
            
            if (!$activeinfo) {
                return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            $rmap['a.aid'] = $activeinfo['id'];
            $rmap['g.status'] = 1;
            
            $od = "a.createtime desc";
            $limits = 100;
            $Nowpage = 1;
            
            $ActiverecordModel = new ActiverecordModel();
            
            $activerecordlist = $ActiverecordModel->getActiverecordByWhere($rmap, $Nowpage, $limits, $od);
            
            $data = array( 'activeinfo' => $activeinfo, 'activerecordlist' => $activerecordlist,'status'=>0,'msg'=>'请求数据正常');
            
            return json_encode($data);
        }
        
        /*
         * 检测企业是否报名招聘会
         * retrun  int status  0:是   1:已报名
         */
        public function checkActiveRecord()
        {
            
            $msg = $this->isLogin();
            
            if($msg['error'] == 0 ) {
    
    
                $companyid = $msg['companyid'];
                
                $aid = input('post.aid');
    
                $map['companyid'] = $companyid;
                $map['aid'] = $aid;
    
                $activeinfo = ActiveModel::getOne(array('id' => $aid));
                
                if(!$activeinfo)
                {
                    
                     return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                    
                }
                     
                $activerecord = ActiverecordModel::getOne($map);
        
                $ActiverecordModel = new ActiverecordModel();
                
                $endtime = strtotime($activeinfo['endtime']);
                
                if($endtime<time())
                {
                    $data = array('status' => 1, 'msg' => '活动已结束');
                    return json_encode($data);
                }
    
                if ($activerecord) {
        
        
                    $data = array('status' => 1, 'msg' => '已报过名');
        
        
                } else {
        
                    $map['createtime'] = $map['create_time'] = $map['update_time'] = time();
        
                    $ActiverecordModel->insertActiverecord($map);
        
                  
        
                    $companyid_arr = explode(',', $activeinfo['companyid']);
        
                    array_push($companyid_arr, $companyid);
        
        
                    $companyids = implode(',', $companyid_arr);
        
                    $ActiveModel = new ActiveModel();
        
                    $param['id'] = $aid;
                    $param['companyid'] = $companyids;
        
                    $ActiveModel->updateActive($param);
        
        
                    $data = array('status' => 0, 'msg' => '报名成功');
                }
    
                return json_encode($data);
    
    
            }else{
                $data = array('status' => 2, 'msg' => '企业未登录');
                return json_encode($data);
                
            }
            
        }
        
        
        
        
    }