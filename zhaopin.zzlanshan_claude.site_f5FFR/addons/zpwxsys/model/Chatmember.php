<?php

namespace addons\zpwxsys\model;

use think\Model;
use think\Db;

class Chatmember extends Model
{
    protected $name = 'zpwxsys_chat_member';

    public static function getOne($map)
    {
        return self::where($map)->find();
    }

    public function getMemberList($groupid)
    {
        return $this->alias('m')
            ->join('zpwxsys_user u', 'u.id = m.uid', 'LEFT')
            ->field('m.id, m.uid, m.role, m.is_muted, m.agreed_rule, m.jointime, u.avatarUrl, u.nickname')
            ->where('m.groupid', $groupid)
            ->order('m.role desc, m.jointime asc')
            ->select();
    }

    public function addMember($data)
    {
        Db::startTrans();
        try {
            $this->allowField(true)->save($data);
            // 更新群成员数
            Db::name('zpwxsys_chat_group')->where('id', $data['groupid'])->setInc('member_count');
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    public function removeMember($groupid, $uid)
    {
        Db::startTrans();
        try {
            $this->where(['groupid' => $groupid, 'uid' => $uid])->delete();
            Db::name('zpwxsys_chat_group')->where('id', $groupid)->setDec('member_count');
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }
}
