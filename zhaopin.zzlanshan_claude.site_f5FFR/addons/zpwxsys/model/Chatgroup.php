<?php

namespace addons\zpwxsys\model;

use think\Model;
use think\Db;

class Chatgroup extends Model
{
    protected $name = 'zpwxsys_chat_group';

    public static function getOne($map)
    {
        return self::where($map)->find();
    }

    public function getGroupList($map, $page = 1, $limit = 10)
    {
        return $this->where($map)
            ->order('createtime desc')
            ->page($page, $limit)
            ->select();
    }

    public function getGroupListByUid($uid, $page = 1, $limit = 10)
    {
        return $this->alias('g')
            ->join('zpwxsys_chat_member m', 'm.groupid = g.id')
            ->join('zpwxsys_job j', 'j.id = g.jobid', 'LEFT')
            ->join('zpwxsys_company c', 'c.id = g.companyid', 'LEFT')
            ->field('g.id, g.group_name, g.notice, g.member_count, g.max_member, g.status, g.createtime, g.jobid, m.role, j.jobtitle, c.companyname, c.thumb AS logo')
            ->where('m.uid', $uid)
            ->where('g.status', 1)
            ->order('g.updatetime desc')
            ->page($page, $limit)
            ->select();
    }

    public function createGroup($data)
    {
        Db::startTrans();
        try {
            $this->allowField(true)->save($data);
            $groupid = $this->id;
            Db::commit();
            return $groupid;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    /**
     * 为岗位创建群组（含群主自动加入）
     */
    public function createGroupForJob($groupData, $ownerUid)
    {
        Db::startTrans();
        try {
            $this->allowField(true)->save($groupData);
            $groupid = $this->id;

            // 群主自动加入
            $member = new Chatmember();
            $member->allowField(true)->save([
                'groupid' => $groupid,
                'uid' => $ownerUid,
                'role' => 2,
                'agreed_rule' => 1,
                'jointime' => time()
            ]);

            // 发送系统欢迎消息
            $msg = new Chatmessage();
            $msg->allowField(true)->save([
                'groupid' => $groupid,
                'uid' => 0,
                'content' => '群聊已创建，欢迎大家加入',
                'msg_type' => 1,
                'createtime' => time()
            ]);

            Db::commit();
            return $groupid;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }

    /**
     * 根据 jobid 获取群组
     */
    public static function getGroupByJobId($jobid)
    {
        return self::where(['jobid' => $jobid, 'status' => 1])->find();
    }
}
