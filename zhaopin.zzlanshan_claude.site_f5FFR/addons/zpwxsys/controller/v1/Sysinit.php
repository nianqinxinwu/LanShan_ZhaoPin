<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    
    use addons\zpwxsys\model\Adv as BannerModel;
    use addons\zpwxsys\model\Nav as NavModel;
    use addons\zpwxsys\model\City as CityModel;
    use addons\zpwxsys\model\Company as CompanyModel;
    
    use addons\zpwxsys\model\Job as JobModel;
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use addons\zpwxsys\model\Sysmsg as SysmsgModel;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\service\AccessToken as AccessToken;
    use addons\zpwxsys\model\Sysinit as SysinitModel;
    use addons\zpwxsys\model\Notice as NoticeModel;
    use addons\zpwxsys\model\Worktype as WorktypeModel;
    use addons\zpwxsys\model\Money as MoneyModel;
    use addons\zpwxsys\model\Oldhouse as OldhouseModel;
    use addons\zpwxsys\service\LookRoleRecord as LookRoleRecordService;
    use addons\zpwxsys\model\Coinrecord as CoinrecordModel;

    use addons\zpwxsys\service\WXBizDataCrypt;
    use addons\zpwxsys\service\Token;
    use think\Db;
 
    
    
    class Sysinit extends BaseController
    {
     
        public function getSysinit()
        {
            
            
            $city = input('post.city');
            
            
            $cityinfo = CityModel::getCityByName($city);
            
            
            $mapbanner['enabled'] = 1;
            $mapbanner['cityid'] = $cityinfo['id'];
            
            $bannerlist = BannerModel::getBanner($mapbanner);
            $navlist = NavModel::getNav();
            
            
            $JobModel = new JobModel();
            
            $od = "g.sort desc";
            $map['g.isrecommand'] = 1;
            $map['g.status'] = 1;
            $map['g.cityid'] = $cityinfo['id'];
            $limits = 10;
            $Nowpage = 1;
            $CompanyModel = new CompanyModel();
            $companylist = $CompanyModel->getCompanyByWhere($map, $Nowpage, $limits, $od);
            
            
            if ($companylist) {
                foreach ($companylist as $k => $v) {
                    $maps['companyid'] = $v['id'];
                    
                    $companylist[$k]['jobcount'] = $JobModel->getJobTotal($maps);
                    
                }
                
            }
            
            
            $sysinfo = SysinitModel::getSysinfo();
            $oldhouselist =[];
            if($sysinfo) {
    
                $param['id'] = $sysinfo['id'];
                $param['view'] = $sysinfo['view'] + 1;
                $sysinitModel = new SysinitModel();
                $sysinitModel->updateSysView($param);
    
                //用于过审
                if($sysinfo['ischeck'] == 1)
                {
        
                    $limits = 10;
                    $Nowpage = 1;
                    $od="h.sort desc";
                    $housemap['h.status'] = 0 ;
        
                    $OldhouseModel = new OldhouseModel();
                    $oldhouselist = $OldhouseModel->getHouseByWhere($housemap, $Nowpage, $limits,$od);
                    
                }
    
            }
            
            
            $mapnotice['status'] = 1;
            $odnotice = "createtime desc";
            $limits = 20;
            $Nowpage = 1;
            
            $NoticeModel = new NoticeModel();
            $noticelist = $NoticeModel->getNoticeByWhere($mapnotice, $Nowpage, $limits, $odnotice);
            
            $mapwork['enabled'] = 1;
            $od = 'sort desc';
            $worktyelist = WorktypeModel::getList($mapwork, $od);
            
            
            $data = array('bannerlist' => $bannerlist, 'navlist' => $navlist, 'cityinfo' => $cityinfo, 'companylist' => $companylist, 'sysinfo' => $sysinfo, 'noticelist' => $noticelist, 'worktyelist' => $worktyelist,'oldhouselist'=>$oldhouselist);
            
            
            return json_encode($data);
            
            
        }
        
        public function getUserinit()
        {
            $uid = Token::getCurrentUid();
            
            $map['uid'] = $uid;
            
            $umap['id'] = $uid;
            
            $userinfo =  UserModel::getByUserWhere($umap);
            
            if (!$userinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
    
            if($userinfo['avatarUrl']!='')
            {
                $isuser = true;
            }else{
        
                $isuser =false;
            }
            
            $sendnotecount = JobrecordModel::getJobrecordToal($map);
            $map['status'] = 1;
            $noticenotecount = JobrecordModel::getJobrecordToal($map);
            
            $lookrolerecodservice = new LookRoleRecordService($uid, 0);
            
            $rolerecord = $lookrolerecodservice->GetFirstRecord();
            
            if (!$rolerecord) {
                
                $totallooknum = 0;
                
            } else {
                
                $totallooknum = $rolerecord[0]['totallooknum'];
                
            }
            
            $mapsys['uid'] = $uid;
            $mapsys['status'] = 0;
            $sysmsgcount = SysmsgModel::getTotal($mapsys);
    
            $tmap['uid'] = $uid;
            $lastmoney =  MoneyModel::getLastOne($tmap);//最新的余额
            
            if($lastmoney)
            {
                $totalmoney = $lastmoney[0]['totalmoney'];
                $usermoney =$totalmoney;
        
            }else{
        
                $totalmoney = '0.00';
                $usermoney = 0 ;
        
            }
            
            
            $data = array('sendnotecount' => $sendnotecount, 'noticenotecount' => $noticenotecount, 'totallooknum' => $totallooknum, 'sysmsgcount' => $sysmsgcount,'isuser'=>$isuser,'userinfo'=>$userinfo,'totalmoney'=>$totalmoney,'usermoney'=>$usermoney,'status'=>0,'msg'=>'请求数据正常');

            // 章鱼外快新增字段（try/catch 防止字段或表不存在导致500）
            try {
                $data['coin_balance'] = CoinrecordModel::getBalance($uid, 1);
            } catch (\Exception $e) {
                $data['coin_balance'] = 0;
            }
            try {
                $data['point_balance'] = CoinrecordModel::getBalance($uid, 2);
            } catch (\Exception $e) {
                $data['point_balance'] = 0;
            }

            // 评价平均分
            try {
                $avgScore = Db::name('zpwxsys_comment')->where('uid', $uid)->avg('score');
                $data['rating'] = $avgScore ? round(floatval($avgScore), 1) : 0;
            } catch (\Exception $e) {
                $data['rating'] = 0;
            }

            // 连续登录天数
            try {
                $data['login_days'] = UserModel::refreshLoginDays($uid);
            } catch (\Exception $e) {
                $data['login_days'] = 0;
            }

            // 实名状态
            $data['is_verified'] = !empty($userinfo['tel']);

            // 用户角色
            try {
                UserModel::ensureUserRoleField();
                // ensureUserRoleField 可能刚添加字段，需重新查询
                $freshUser = UserModel::getByUserWhere($umap);
                $data['user_role'] = ($freshUser && isset($freshUser['user_role'])) ? intval($freshUser['user_role']) : 0;
            } catch (\Exception $e) {
                $data['user_role'] = 0;
            }

            return json_encode($data);
            
            
        }
    
    
    
        public function  getMoneyRecord()
        {
            $uid = Token::getCurrentUid();
            $map['m.uid'] = $uid;
            $od = 'm.createtime desc';
            $MoneyModel = new MoneyModel();
         
        
            $moneylist=$MoneyModel->getListOrderByWhere($map,$od);
        
            if($moneylist)
            {
            
                foreach ($moneylist as $k=>$v)
                {
                    
                    if($v['type'] == 0 )
                    {
                    
                    }elseif($v['type'] == -1)
                    {
                        $moneylist[$k]['orderinfo'] ='提现';
                    
                    }
                    
                
                    $moneylist[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                }
            }
        
        
            $data = array('error'=>0,'moneylist'=>$moneylist);
        
            return json_encode($data);
        }
    
    
        public function getUserMoney()
        {
            $uid = Token::getCurrentUid();
            $map['uid'] = $uid;
            $lastmoney =  MoneyModel::getLastOne($map);//最新的余额
        
            if($lastmoney)
            {
                $totalmoney = $lastmoney[0]['totalmoney'];
            
                $usermoney = $totalmoney ;
                
            }else{
                
                $usermoney =  0;
            
            }
        
            if($usermoney>0)
            {
                $totalmoney = $totalmoney - $usermoney;
            
                $param_money['money'] = -$usermoney;
            
                $param_money['totalmoney'] = $totalmoney;
            
                $param_money['uid'] = $uid;
            
                $param_money['type'] = -1;
            
                $param_money['orderid'] = -1;
            
                $param_money['content'] = '佣金提现';
            
                $param_money['createtime'] = time();
                
                $moneyModel = new MoneyModel();
            
                $moneyModel->insertMoney($param_money);
            
                $data = array('error'=>0,'msg'=>'提现成功');
                
            }else{
                $data = array('error'=>1,'msg'=>'提现失败,余额不足');
            }
        
        
            return json_encode($data);
        
        
        
        }
        
        
        
        
        public function getPhone()
        {
            // 优先从 post 读 code，兼容 JSON body 和 form-data
            $code = $this->request->post('code', '', 'trim');
            if (!$code) {
                // 兜底：手动解析 php://input
                $rawInput = file_get_contents('php://input');
                $jsonInput = json_decode($rawInput, true);
                if ($jsonInput && !empty($jsonInput['code'])) {
                    $code = trim($jsonInput['code']);
                }
            }
            if (!$code) {
                return json_encode(['status' => 1, 'msg' => '参数错误，缺少code']);
            }

            $accessToken = new AccessToken();
            $token = $accessToken->get();
            if (!$token) {
                return json_encode(['status' => 1, 'msg' => '获取access_token失败']);
            }

            $url = "https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=" . $token;
            $curlService = new \addons\zpwxsys\service\Curl();
            $result = $curlService->curl_post($url, ['code' => $code]);

            if ($result === false) {
                return json_encode(['status' => 1, 'msg' => '请求微信接口失败(curl错误)']);
            }

            $res = json_decode($result, true);

            if (!$res) {
                return json_encode(['status' => 1, 'msg' => '微信接口返回数据解析失败', 'raw' => mb_substr($result, 0, 200)]);
            }

            if (!isset($res['errcode']) || $res['errcode'] != 0) {
                $errmsg = isset($res['errmsg']) ? $res['errmsg'] : '未知错误';
                $errcode = isset($res['errcode']) ? $res['errcode'] : '-';
                return json_encode(['status' => 1, 'msg' => '获取手机号失败: [' . $errcode . '] ' . $errmsg]);
            }

            if (!isset($res['phone_info']) || !isset($res['phone_info']['phoneNumber'])) {
                return json_encode(['status' => 1, 'msg' => '微信返回数据缺少手机号信息']);
            }

            $tel = $res['phone_info']['phoneNumber'];

            $data = array('msg' => '获取成功', 'status' => 0, 'tel' => $tel);
            return json_encode($data);
        }
        
        
        public function updateUsertel()
        {
            
            $uid = Token::getCurrentUid();
            $tel = input('post.tel');
            
            
             $userinfo  = UserModel::getByUserWhere(['id'=>$uid]);
             if (!$userinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            $userModel = new UserModel();
            
            $param['id'] = $uid;
            $param['tel'] = $tel;
            
            $userModel->Updateuser($param);
            $data = array('msg' => '更新成功', 'status' => 0);
            
            return json_encode($data);
        }
    
        public function getWxUserInfo()
        {
            $uid = Token::getCurrentUid();
            
            $map['id'] = $uid;
            
            $userinfo  = UserModel::getByUserWhere($map);
            
            if (!$userinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            if($userinfo['qrcode']== '')
            {
           
                $UserModel = new UserModel();
               
                $AccessToken = new AccessToken();
                $qrcode = $AccessToken->getUserQrcode($uid);
                
                
                
                $param['id'] = $uid;
                $param['qrcode'] = $qrcode;
                
                $UserModel->updateUser($param);
                
                $userinfo['qrcode'] = cdnurl($qrcode, true);
                
                
                
            }else{
                $userinfo['qrcode'] = cdnurl($userinfo['qrcode'], true);
                
            }
            $sysinfo = SysinitModel::getSysinfo();
            $userinfo['fxbg'] = cdnurl($sysinfo['fxbg'], true);
            $userinfo['sharebg'] = cdnurl($sysinfo['sharebg'], true);
        
            $data = array('userinfo'=>$userinfo);
        
            return json_encode($data);
        
        }
        
        public function updateUser()
        {
            $nickname = input('post.nickname');
            
            $avatarUrl = input('post.avatarUrl');
            
            $tel = input('post.tel');
            
            $uid = Token::getCurrentUid();
            
             $map['id'] = $uid;
            
            $userinfo  = UserModel::getByUserWhere($map);
            
            if (!$userinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            $userModel = new UserModel();
            
            $param['id'] = $uid;
            $param['nickname'] = $nickname;
            $param['avatarUrl'] = $avatarUrl;
            $param['tel'] = $tel;
            
            $param['update_time'] = time();
            
            
            $userModel->Updateuser($param);
            
            
        //$map['id'] = $uid;
            
           // $userinfo = UserModel::getByUserWhere($map);
            
            
            if ($userinfo['tel'] != '') {
                
                $istel = true;
            } else {
                
                $istel = false;
            }
            
            
            $data = array('msg' => '更新成功', 'status' => 0, 'istel' => $istel);
            
            return json_encode($data);
            
            
        }
        
        
    }