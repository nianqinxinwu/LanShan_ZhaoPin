<?php

namespace addons\zpwxsys\model;

use think\Db;

class Coinrecord extends BaseModel
{
    protected $name = 'zpwxsys_coin_record';

    /**
     * 获取用户的章鱼币/积分余额
     * @param int $uid 用户ID
     * @param int $type 1=章鱼币 2=积分
     * @return float
     */
    public static function getBalance($uid, $type = 1)
    {
        $field = $type == 1 ? 'coin_balance' : 'point_balance';
        $user = Db::name('zpwxsys_user')->where('id', $uid)->field($field)->find();
        return $user ? floatval($user[$field]) : 0;
    }

    /**
     * 获取消费/收入记录列表
     */
    public function getRecordList($map, $Nowpage, $limits, $od)
    {
        $list = $this->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();

        foreach ($list as $k => $v) {
            $list[$k]['createtime'] = $v['createtime'] ? date('Y-m-d H:i', $v['createtime']) : '';
            $list[$k]['action_text'] = self::getActionText($v['action']);
        }

        return $list;
    }

    /**
     * 充值（增加章鱼币）
     */
    public static function recharge($uid, $amount, $relatedId = null, $remark = '充值')
    {
        Db::startTrans();
        try {
            // 更新用户余额
            Db::name('zpwxsys_user')->where('id', $uid)->setInc('coin_balance', $amount);

            // 获取更新后的余额
            $newBalance = self::getBalance($uid, 1);

            // 写入记录
            $record = [
                'uid' => $uid,
                'type' => 1,
                'amount' => $amount,
                'balance' => $newBalance,
                'action' => 'recharge',
                'remark' => $remark,
                'related_id' => $relatedId,
                'createtime' => time()
            ];
            Db::name('zpwxsys_coin_record')->insert($record);

            Db::commit();
            return ['status' => 0, 'msg' => '充值成功', 'balance' => $newBalance];
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 1, 'msg' => '充值失败'];
        }
    }

    /**
     * 消费章鱼币
     */
    public static function consume($uid, $amount, $action = 'consume', $relatedId = null, $remark = '消费')
    {
        Db::startTrans();
        try {
            // 检查余额
            $currentBalance = self::getBalance($uid, 1);
            if ($currentBalance < $amount) {
                Db::rollback();
                return ['status' => 2, 'msg' => '章鱼币余额不足', 'balance' => $currentBalance];
            }

            // 扣减余额
            Db::name('zpwxsys_user')->where('id', $uid)->setDec('coin_balance', $amount);

            $newBalance = $currentBalance - $amount;

            // 写入记录
            $record = [
                'uid' => $uid,
                'type' => 1,
                'amount' => -$amount,
                'balance' => $newBalance,
                'action' => $action,
                'remark' => $remark,
                'related_id' => $relatedId,
                'createtime' => time()
            ];
            Db::name('zpwxsys_coin_record')->insert($record);

            Db::commit();
            return ['status' => 0, 'msg' => '支付成功', 'balance' => $newBalance];
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 1, 'msg' => '支付失败'];
        }
    }

    /**
     * 增加积分
     */
    public static function addPoints($uid, $amount, $action = 'signin', $relatedId = null, $remark = '签到奖励')
    {
        Db::startTrans();
        try {
            Db::name('zpwxsys_user')->where('id', $uid)->setInc('point_balance', $amount);

            $newBalance = self::getBalance($uid, 2);

            $record = [
                'uid' => $uid,
                'type' => 2,
                'amount' => $amount,
                'balance' => $newBalance,
                'action' => $action,
                'remark' => $remark,
                'related_id' => $relatedId,
                'createtime' => time()
            ];
            Db::name('zpwxsys_coin_record')->insert($record);

            Db::commit();
            return ['status' => 0, 'msg' => '积分增加成功', 'balance' => $newBalance];
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 1, 'msg' => '操作失败'];
        }
    }

    /**
     * 积分兑换（扣除积分，增加章鱼币）
     */
    public static function exchangePoints($uid, $points, $coinAmount)
    {
        Db::startTrans();
        try {
            $pointBalance = self::getBalance($uid, 2);
            if ($pointBalance < $points) {
                Db::rollback();
                return ['status' => 2, 'msg' => '积分不足'];
            }

            // 扣积分
            Db::name('zpwxsys_user')->where('id', $uid)->setDec('point_balance', $points);
            $newPointBalance = $pointBalance - $points;

            Db::name('zpwxsys_coin_record')->insert([
                'uid' => $uid,
                'type' => 2,
                'amount' => -$points,
                'balance' => $newPointBalance,
                'action' => 'exchange',
                'remark' => '积分兑换章鱼币',
                'createtime' => time()
            ]);

            // 加章鱼币
            Db::name('zpwxsys_user')->where('id', $uid)->setInc('coin_balance', $coinAmount);
            $newCoinBalance = self::getBalance($uid, 1);

            Db::name('zpwxsys_coin_record')->insert([
                'uid' => $uid,
                'type' => 1,
                'amount' => $coinAmount,
                'balance' => $newCoinBalance,
                'action' => 'exchange',
                'remark' => '积分兑换所得',
                'createtime' => time()
            ]);

            Db::commit();
            return [
                'status' => 0,
                'msg' => '兑换成功',
                'coin_balance' => $newCoinBalance,
                'point_balance' => $newPointBalance
            ];
        } catch (\Exception $e) {
            Db::rollback();
            return ['status' => 1, 'msg' => '兑换失败'];
        }
    }

    /**
     * 获取动作文本
     */
    public static function getActionText($action)
    {
        $map = [
            'recharge' => '充值',
            'consume' => '消费',
            'signin' => '签到奖励',
            'referral' => '推荐奖励',
            'exchange' => '积分兑换',
            'topjob' => '置顶职位',
            'spreadjob' => '加急扩散',
            'creategroup' => '创建群聊',
            'tempcall' => '临时通话',
            'signinservice' => '签到服务'
        ];
        return isset($map[$action]) ? $map[$action] : $action;
    }
}
