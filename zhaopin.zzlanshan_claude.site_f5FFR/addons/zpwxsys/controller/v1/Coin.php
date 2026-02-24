<?php

namespace addons\zpwxsys\controller\v1;

use addons\zpwxsys\controller\BaseController;
use addons\zpwxsys\model\Coinrecord as CoinrecordModel;
use addons\zpwxsys\model\User as UserModel;
use addons\zpwxsys\model\Chatgroup as ChatgroupModel;
use addons\zpwxsys\model\Chatmember as ChatmemberModel;
use addons\zpwxsys\model\Chatmessage as ChatmessageModel;
use addons\zpwxsys\model\Job as JobModel;
use addons\zpwxsys\model\Jobrecord as JobrecordModel;
use addons\zpwxsys\service\Token;

class Coin extends BaseController
{

    /**
     * 获取章鱼币余额
     */
    public function getBalance()
    {
        $uid = Token::getCurrentUid();

        $coinBalance = CoinrecordModel::getBalance($uid, 1);
        $pointBalance = CoinrecordModel::getBalance($uid, 2);

        return json_encode([
            'status' => 0,
            'msg' => '请求数据正常',
            'coin_balance' => $coinBalance,
            'point_balance' => $pointBalance
        ]);
    }

    /**
     * 章鱼币充值
     * 当前版本：模拟充值（直接增加余额）
     * 生产环境需对接微信支付回调
     */
    public function recharge()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $amount = floatval(input('post.amount'));

            if ($amount <= 0) {
                return json_encode(['status' => 1, 'msg' => '充值金额无效']);
            }

            // 充值档位校验
            $validAmounts = [10, 30, 50, 100, 200, 500];
            if (!in_array($amount, $validAmounts)) {
                return json_encode(['status' => 1, 'msg' => '请选择有效的充值档位']);
            }

            $result = CoinrecordModel::recharge($uid, $amount, null, '充值 ' . $amount . ' 章鱼币');

            return json_encode($result);
        }
    }

    /**
     * 获取章鱼币消费记录
     */
    public function getCoinRecords()
    {
        $uid = Token::getCurrentUid();
        $page = input('post.page') ?: 1;
        $limits = 20;

        $map = ['uid' => $uid, 'type' => 1];
        $od = 'createtime desc';

        $CoinrecordModel = new CoinrecordModel();
        $list = $CoinrecordModel->getRecordList($map, $page, $limits, $od);

        $balance = CoinrecordModel::getBalance($uid, 1);

        return json_encode([
            'status' => 0,
            'msg' => '请求数据正常',
            'recordlist' => $list,
            'balance' => $balance
        ]);
    }

    /**
     * 获取积分记录
     */
    public function getPointRecords()
    {
        $uid = Token::getCurrentUid();
        $page = input('post.page') ?: 1;
        $limits = 20;

        $map = ['uid' => $uid, 'type' => 2];
        $od = 'createtime desc';

        $CoinrecordModel = new CoinrecordModel();
        $list = $CoinrecordModel->getRecordList($map, $page, $limits, $od);

        $balance = CoinrecordModel::getBalance($uid, 2);

        return json_encode([
            'status' => 0,
            'msg' => '请求数据正常',
            'recordlist' => $list,
            'balance' => $balance
        ]);
    }

    /**
     * 章鱼币消费（通用扣费接口）
     * 其他模块调用此方法进行扣费
     */
    public function consume()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $amount = floatval(input('post.amount'));
            $action = input('post.action') ?: 'consume';
            $relatedId = input('post.related_id');
            $remark = input('post.remark') ?: '服务消费';

            if ($amount <= 0) {
                return json_encode(['status' => 1, 'msg' => '金额无效']);
            }

            // 创建群聊：先检查是否已有群，避免重复扣费
            if ($action == 'creategroup' && $relatedId) {
                $existGroup = ChatgroupModel::getOne(['jobid' => $relatedId, 'status' => 1]);
                if ($existGroup) {
                    return json_encode(['status' => 0, 'msg' => '群聊已存在', 'groupid' => $existGroup['id']]);
                }
            }

            // 签到服务：检查是否已购买过，避免重复扣费
            if ($action == 'signinservice' && $relatedId) {
                $existRecord = CoinrecordModel::where('related_id', $relatedId)
                    ->where('action', 'signinservice')
                    ->where('amount', '<', 0)
                    ->find();
                if ($existRecord) {
                    return json_encode(['status' => 0, 'msg' => '签到服务已开通']);
                }
            }

            $result = CoinrecordModel::consume($uid, $amount, $action, $relatedId, $remark);

            // 创建群聊服务：扣费成功后自动创建群组
            if ($result['status'] == 0 && $action == 'creategroup' && $relatedId) {
                $jobinfo = JobModel::getOne(['id' => $relatedId]);
                if ($jobinfo) {
                    // 检查是否已有群
                    $existGroup = ChatgroupModel::getOne(['jobid' => $relatedId, 'status' => 1]);
                    if (!$existGroup) {
                        $groupData = [
                            'jobid' => $relatedId,
                            'group_name' => $jobinfo['jobtitle'] . '通知群',
                            'companyid' => $jobinfo['companyid'],
                            'owner_uid' => $uid,
                            'max_member' => 200,
                            'member_count' => 1,
                            'status' => 1,
                            'createtime' => time(),
                            'updatetime' => time(),
                            'expire_time' => $jobinfo['endtime'] ? $jobinfo['endtime'] + 86400 : time() + 30 * 86400
                        ];
                        $chatgroup = new ChatgroupModel();
                        $groupid = $chatgroup->createGroup($groupData);
                        if ($groupid) {
                            $chatmember = new ChatmemberModel();
                            $chatmember->addMember([
                                'groupid' => $groupid,
                                'uid' => $uid,
                                'role' => 2,
                                'agreed_rule' => 1,
                                'jointime' => time()
                            ]);
                            $chatmsg = new ChatmessageModel();
                            $chatmsg->sendMessage([
                                'groupid' => $groupid,
                                'uid' => 0,
                                'content' => '群聊已创建，欢迎大家加入',
                                'msg_type' => 1,
                                'createtime' => time()
                            ]);

                            // 将已录用的报名者自动加入群
                            $acceptedRecords = JobrecordModel::where('jobid', $relatedId)
                                ->where('status', 1)
                                ->select();
                            foreach ($acceptedRecords as $record) {
                                $memberUid = $record['uid'];
                                if ($memberUid == $uid) {
                                    continue; // 群主已添加
                                }
                                $existMember = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $memberUid]);
                                if (!$existMember) {
                                    $cm = new ChatmemberModel();
                                    $cm->addMember([
                                        'groupid' => $groupid,
                                        'uid' => $memberUid,
                                        'role' => 0,
                                        'agreed_rule' => 0,
                                        'jointime' => time()
                                    ]);
                                }
                            }

                            $result['groupid'] = $groupid;
                        }
                    } else {
                        $result['groupid'] = $existGroup['id'];
                    }
                }
            }

            return json_encode($result);
        }
    }

    /**
     * 积分兑换章鱼币
     * 当前规则：10积分 = 1章鱼币
     */
    public function exchangePoints()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $points = floatval(input('post.points'));

            if ($points < 10) {
                return json_encode(['status' => 1, 'msg' => '最少需要10积分才能兑换']);
            }

            // 兑换比例：10积分 = 1章鱼币
            $coinAmount = floor($points / 10);
            $actualPoints = $coinAmount * 10;

            $result = CoinrecordModel::exchangePoints($uid, $actualPoints, $coinAmount);

            return json_encode($result);
        }
    }

    /**
     * 获取充值档位列表
     */
    public function getRechargeOptions()
    {
        $options = [
            ['amount' => 10, 'label' => '10章鱼币', 'price' => '10.00'],
            ['amount' => 30, 'label' => '30章鱼币', 'price' => '30.00'],
            ['amount' => 50, 'label' => '50章鱼币', 'price' => '50.00'],
            ['amount' => 100, 'label' => '100章鱼币', 'price' => '98.00'],
            ['amount' => 200, 'label' => '200章鱼币', 'price' => '188.00'],
            ['amount' => 500, 'label' => '500章鱼币', 'price' => '468.00']
        ];

        return json_encode([
            'status' => 0,
            'msg' => '请求数据正常',
            'options' => $options
        ]);
    }

    /**
     * 查询岗位已开通的服务状态
     */
    public function checkServiceStatus()
    {
        if (request()->isPost()) {
            $relatedId = input('post.related_id');
            if (!$relatedId) {
                return json_encode(['status' => 1, 'msg' => '参数缺失']);
            }

            // 群聊是否已创建
            $groupCreated = false;
            $existGroup = ChatgroupModel::getOne(['jobid' => $relatedId, 'status' => 1]);
            if ($existGroup) {
                $groupCreated = true;
            }

            // 签到服务是否已购买
            $signinPurchased = false;
            $existSignin = CoinrecordModel::where('related_id', $relatedId)
                ->where('action', 'signinservice')
                ->where('amount', '<', 0)
                ->find();
            if ($existSignin) {
                $signinPurchased = true;
            }

            return json_encode([
                'status' => 0,
                'group_created' => $groupCreated,
                'signin_purchased' => $signinPurchased
            ]);
        }
    }
}
