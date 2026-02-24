<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Baoming as BaomingModel;
    use addons\zpwxsys\model\Job as JobModel;
    use addons\zpwxsys\model\Company as CompanyModel;
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use addons\zpwxsys\model\Chatgroup as ChatgroupModel;
    use addons\zpwxsys\model\Chatmessage as ChatmessageModel;
    use addons\zpwxsys\model\Sysmsg as SysmsgModel;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\service\Token;
    
    /*
     * 求职者报名接口
     */
    
    class Baoming extends BaseController
    {
        
        
        /*
         * 保存报名信息
         */
        
        
        public function saveBaoming()
        {
            if (request()->isPost()) {

                $param = input('post.');

                $param['uid'] = Token::getCurrentUid();

                $map['uid'] = $param['uid'];

                $map['jobid'] = $param['jobid'];
                
                
                $agentinfo = BaomingModel::getAgentByuid($map);
                
                $baoming = new BaomingModel();
                
                if (!$agentinfo) {


                    $data = $baoming->insertBaoming($param);

                    // 同步写入 jobrecord 表，使发布者"筛选报名者"可查到该报名记录
                    $jobid = $param['jobid'];
                    $jobInfo = JobModel::getOne(['id' => $jobid]);
                    if ($jobInfo) {
                        $existRecord = JobrecordModel::getJobrecordWhere([
                            'uid' => $param['uid'],
                            'jobid' => $jobid
                        ]);
                        if (!$existRecord) {
                            $jobrecord = new JobrecordModel();
                            $jobrecord->allowField(true)->save([
                                'uid' => $param['uid'],
                                'jobid' => $jobid,
                                'companyid' => $jobInfo['companyid'],
                                'createtime' => time(),
                                'status' => 0,
                                'agentuid' => 0,
                                'taskid' => 0
                            ]);
                        }

                        $userInfo = UserModel::get($param['uid']);
                        $name = $userInfo ? ($userInfo['nickname'] ?: $userInfo['wechaname'] ?: '求职者') : '求职者';

                        // 向关联群组发送系统通知
                        $groupInfo = ChatgroupModel::where('jobid', $jobid)->where('status', 1)->find();
                        if ($groupInfo) {
                            $msgContent = $name . ' 报名了岗位「' . $jobInfo['jobtitle'] . '」';
                            $chatmsg = new ChatmessageModel();
                            $chatmsg->sendMessage([
                                'groupid' => $groupInfo['id'],
                                'uid' => 0,
                                'content' => $msgContent,
                                'msg_type' => 1,
                                'createtime' => time()
                            ]);
                        }

                        // 给发布者发系统消息
                        $companyInfo = CompanyModel::where('id', $jobInfo['companyid'])->find();
                        if ($companyInfo) {
                            SysmsgModel::ensureNewFields();
                            $sysmsg = new SysmsgModel();
                            $sysmsg->allowField(true)->save([
                                'uid' => $companyInfo['uid'],
                                'content' => $name . ' 报名了您发布的岗位「' . $jobInfo['jobtitle'] . '」',
                                'createtime' => time(),
                                'status' => 0,
                                'jobtitle' => $jobInfo['jobtitle'],
                                'link' => '/pages/selectnote/index?jobid=' . $jobid
                            ]);
                        }
                    }

                } else {
                    
                    $data = json_encode(array('status' => 1, 'msg' => '您的信息已提交过'));
                    
                }
                
                return $data;
            }
            
            
        }
        
        
    }