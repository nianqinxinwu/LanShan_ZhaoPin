<?php

namespace addons\zpwxsys\model;

use think\Model;

class Chatmessage extends Model
{
    protected $name = 'zpwxsys_chat_message';

    public function getMessages($groupid, $lastId = 0, $limit = 30)
    {
        $map = [];
        $map['m.groupid'] = $groupid;
        if ($lastId > 0) {
            $map['m.id'] = ['>', $lastId];
        }

        return $this->alias('m')
            ->join('zpwxsys_user u', 'u.id = m.uid', 'LEFT')
            ->join('zpwxsys_chat_member cm', 'cm.groupid = m.groupid AND cm.uid = m.uid', 'LEFT')
            ->field('m.id, m.uid, m.content, m.msg_type, m.img_url, m.createtime, u.avatarUrl, u.nickname, cm.role')
            ->where($map)
            ->order('m.id asc')
            ->limit($limit)
            ->select();
    }

    public function getHistoryMessages($groupid, $beforeId = 0, $limit = 30)
    {
        $map = [];
        $map['m.groupid'] = $groupid;
        if ($beforeId > 0) {
            $map['m.id'] = ['<', $beforeId];
        }

        return $this->alias('m')
            ->join('zpwxsys_user u', 'u.id = m.uid', 'LEFT')
            ->join('zpwxsys_chat_member cm', 'cm.groupid = m.groupid AND cm.uid = m.uid', 'LEFT')
            ->field('m.id, m.uid, m.content, m.msg_type, m.img_url, m.createtime, u.avatarUrl, u.nickname, cm.role')
            ->where($map)
            ->order('m.id desc')
            ->limit($limit)
            ->select();
    }

    public function sendMessage($data)
    {
        $this->allowField(true)->save($data);
        return $this->id;
    }
}
