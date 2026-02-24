<?php

namespace addons\zpwxsys\controller\v1;

use addons\zpwxsys\controller\BaseController;
use addons\zpwxsys\model\Chatgroup as ChatgroupModel;
use addons\zpwxsys\model\Chatmember as ChatmemberModel;
use addons\zpwxsys\model\Chatmessage as ChatmessageModel;
use addons\zpwxsys\model\Chatrule as ChatruleModel;
use addons\zpwxsys\model\Sensitiveword as SensitivewordModel;
use addons\zpwxsys\model\Job as JobModel;
use addons\zpwxsys\service\Token;
use addons\zpwxsys\service\Token as TokenService;
use app\common\library\Upload;

class Chat extends BaseController
{

    /**
     * 获取群聊公约
     */
    public function getRule()
    {
        $rule = ChatruleModel::getActiveRule();
        if ($rule) {
            $data = array('status' => 1, 'msg' => '请求数据正常', 'rule' => [
                'title' => $rule['title'],
                'content' => $rule['content']
            ]);
        } else {
            $data = array('status' => 1, 'msg' => '请求数据正常', 'rule' => [
                'title' => '群聊公约',
                'content' => '暂无公约内容'
            ]);
        }
        return json_encode($data);
    }

    /**
     * 同意群聊公约
     */
    public function agreeRule()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member) {
                return json_encode(array('status' => 0, 'msg' => '您不是群成员'));
            }

            $ChatmemberModel = new ChatmemberModel();
            $ChatmemberModel->where(['groupid' => $groupid, 'uid' => $uid])->update(['agreed_rule' => 1]);

            return json_encode(array('status' => 1, 'msg' => '已同意公约'));
        }
    }

    /**
     * 创建群聊（发布者调用）
     */
    public function createGroup()
    {
        if (request()->isPost()) {
            $msg = $this->isLogin();

            if ($msg['error'] == 0) {
                $companyid = $msg['companyid'];
                $jobid = input('post.jobid');

                // 检查岗位是否存在
                $jobinfo = JobModel::getOne(['id' => $jobid, 'companyid' => $companyid]);
                if (!$jobinfo) {
                    return json_encode(array('status' => 0, 'msg' => '岗位不存在'));
                }

                // 检查是否已有群
                $existGroup = ChatgroupModel::getOne(['jobid' => $jobid, 'status' => 1]);
                if ($existGroup) {
                    return json_encode(array('status' => 0, 'msg' => '该岗位已有群聊', 'groupid' => $existGroup['id']));
                }

                $uid = Token::getCurrentUid();
                $groupData = [
                    'jobid' => $jobid,
                    'group_name' => $jobinfo['jobtitle'] . '通知群',
                    'companyid' => $companyid,
                    'owner_uid' => $uid,
                    'max_member' => input('post.max_member', 200),
                    'member_count' => 1,
                    'status' => 1,
                    'createtime' => time(),
                    'updatetime' => time(),
                    'expire_time' => $jobinfo['endtime'] ? $jobinfo['endtime'] + 86400 : time() + 30 * 86400
                ];

                $ChatgroupModel = new ChatgroupModel();
                $groupid = $ChatgroupModel->createGroup($groupData);

                if ($groupid) {
                    // 群主自动加入
                    $ChatmemberModel = new ChatmemberModel();
                    $ChatmemberModel->addMember([
                        'groupid' => $groupid,
                        'uid' => $uid,
                        'role' => 2,
                        'agreed_rule' => 1,
                        'jointime' => time()
                    ]);

                    // 发送系统消息
                    $ChatmessageModel = new ChatmessageModel();
                    $ChatmessageModel->sendMessage([
                        'groupid' => $groupid,
                        'uid' => 0,
                        'content' => '群聊已创建，欢迎大家加入',
                        'msg_type' => 1,
                        'createtime' => time()
                    ]);

                    return json_encode(array('status' => 1, 'msg' => '群聊创建成功', 'groupid' => $groupid));
                } else {
                    return json_encode(array('status' => 0, 'msg' => '创建失败'));
                }
            } else {
                return json_encode(array('status' => 0, 'msg' => 'Token异常'));
            }
        }
    }

    /**
     * 我的群聊列表
     */
    public function chatList()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $page = input('post.page', 1);

            $ChatgroupModel = new ChatgroupModel();
            $grouplist = $ChatgroupModel->getGroupListByUid($uid, $page, 20);

            if ($grouplist) {
                foreach ($grouplist as $k => $v) {
                    $grouplist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                    if (!empty($v['logo'])) {
                        $grouplist[$k]['logo'] = cdnurl($v['logo'], true);
                    }
                }
            }

            return json_encode(array('status' => 1, 'grouplist' => $grouplist ?: []));
        }
    }

    /**
     * 获取群详情（进入聊天室时调用）
     */
    public function groupDetail()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');

            $group = ChatgroupModel::getOne(['id' => $groupid, 'status' => 1]);
            if (!$group) {
                return json_encode(array('status' => 0, 'msg' => '群聊不存在或已解散'));
            }

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member) {
                return json_encode(array('status' => 0, 'msg' => '您不是群成员'));
            }

            return json_encode(array(
                'status' => 1,
                'group' => [
                    'id' => $group['id'],
                    'group_name' => $group['group_name'],
                    'notice' => $group['notice'],
                    'member_count' => $group['member_count'],
                    'max_member' => $group['max_member'],
                    'owner_uid' => $group['owner_uid'],
                    'jobid' => $group['jobid']
                ],
                'member' => [
                    'role' => $member['role'],
                    'is_muted' => $member['is_muted'],
                    'agreed_rule' => $member['agreed_rule']
                ]
            ));
        }
    }

    /**
     * 获取消息列表（轮询 / 首次加载）
     */
    public function messages()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');
            $lastId = input('post.lastId', 0);

            // 验证是否群成员
            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member) {
                return json_encode(array('status' => 0, 'msg' => '您不是群成员'));
            }

            $ChatmessageModel = new ChatmessageModel();

            if ($lastId > 0) {
                // 增量拉取新消息
                $msglist = $ChatmessageModel->getMessages($groupid, $lastId, 50);
            } else {
                // 首次加载历史消息
                $msglist = $ChatmessageModel->getHistoryMessages($groupid, 0, 30);
                if ($msglist) {
                    $msglist = array_reverse($msglist);
                }
            }

            if ($msglist) {
                foreach ($msglist as $k => $v) {
                    $msglist[$k]['time_str'] = date('H:i', $v['createtime']);
                    $msglist[$k]['is_self'] = ($v['uid'] == $uid) ? 1 : 0;
                    if (!empty($v['avatarUrl'])) {
                        $msglist[$k]['avatarUrl'] = cdnurl($v['avatarUrl'], true);
                    }
                    if (!empty($v['img_url'])) {
                        $msglist[$k]['img_url'] = cdnurl($v['img_url'], true);
                    }
                    $msglist[$k]['name'] = $v['nickname'] ?: '用户';
                    $msglist[$k]['role'] = isset($v['role']) ? intval($v['role']) : 0;
                }
            }

            return json_encode(array('status' => 1, 'msglist' => $msglist ?: [], 'uid' => $uid));
        }
    }

    /**
     * 发送消息
     */
    public function send()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');
            $content = input('post.content');
            $msg_type = input('post.msg_type', 0);
            $img_url = input('post.img_url', '');

            // 图片消息
            if ($msg_type == 2) {
                if (empty($img_url)) {
                    return json_encode(array('status' => 0, 'msg' => '图片地址不能为空'));
                }
                $content = '[图片]';
            } else {
                if (empty($content) || mb_strlen(trim($content)) == 0) {
                    return json_encode(array('status' => 0, 'msg' => '消息不能为空'));
                }
            }

            // 验证群成员
            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member) {
                return json_encode(array('status' => 0, 'msg' => '您不是群成员'));
            }

            // 检查是否同意公约
            if ($member['agreed_rule'] != 1) {
                return json_encode(array('status' => 0, 'msg' => '请先同意群聊公约'));
            }

            // 检查禁言
            if ($member['is_muted'] == 1) {
                return json_encode(array('status' => 0, 'msg' => '您已被禁言'));
            }

            // 检查群状态
            $group = ChatgroupModel::getOne(['id' => $groupid, 'status' => 1]);
            if (!$group) {
                return json_encode(array('status' => 0, 'msg' => '群聊已解散'));
            }

            // 数字限制：不能连续超过2位数字（仅文字消息检查）
            if ($msg_type == 0 && preg_match('/\d{3,}/', $content)) {
                return json_encode(array('status' => 0, 'msg' => '消息中不允许包含连续3位及以上数字'));
            }

            // 敏感词过滤
            if ($msg_type == 0) {
                $content = SensitivewordModel::filterContent($content);
            }

            $ChatmessageModel = new ChatmessageModel();
            $msgData = [
                'groupid' => $groupid,
                'uid' => $uid,
                'content' => $content,
                'msg_type' => $msg_type,
                'img_url' => $img_url,
                'createtime' => time()
            ];
            $msgId = $ChatmessageModel->sendMessage($msgData);

            // 更新群最新活动时间
            $ChatgroupModel = new ChatgroupModel();
            $ChatgroupModel->where(['id' => $groupid])->update(['updatetime' => time()]);

            // 返回完整消息数据，前端可直接展示
            $userInfo = \addons\zpwxsys\model\User::get($uid);
            $avatarUrl = '';
            if ($userInfo && !empty($userInfo['avatarUrl'])) {
                $avatarUrl = cdnurl($userInfo['avatarUrl'], true);
            }

            $msgItem = [
                'id' => $msgId,
                'uid' => $uid,
                'content' => $content,
                'msg_type' => $msg_type,
                'img_url' => $img_url ? cdnurl($img_url, true) : '',
                'createtime' => $msgData['createtime'],
                'time_str' => date('H:i', $msgData['createtime']),
                'is_self' => 1,
                'name' => $userInfo ? ($userInfo['nickname'] ?: '用户') : '用户',
                'avatarUrl' => $avatarUrl,
                'role' => intval($member['role'])
            ];

            return json_encode(array('status' => 1, 'msg' => '发送成功', 'msgId' => $msgId, 'msgItem' => $msgItem));
        }
    }

    /**
     * 上传聊天图片
     */
    public function uploadImage()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $file = request()->file('file');

            if (!$file) {
                return json_encode(array('status' => 0, 'msg' => '请选择图片'));
            }

            $upload = new Upload();
            $upload->setFile($file);
            $fileinfo = $upload->upload();

            if ($fileinfo) {
                return json_encode(array('status' => 1, 'msg' => '上传成功', 'img_url' => $fileinfo['url']));
            } else {
                return json_encode(array('status' => 0, 'msg' => '上传失败'));
            }
        }
    }

    /**
     * 编辑群公告（群主/管理员）
     */
    public function editNotice()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');
            $notice = input('post.notice');

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member || $member['role'] < 1) {
                return json_encode(array('status' => 0, 'msg' => '没有权限'));
            }

            $ChatgroupModel = new ChatgroupModel();
            $ChatgroupModel->where(['id' => $groupid])->update(['notice' => $notice, 'updatetime' => time()]);

            // 发送系统通知
            $ChatmessageModel = new ChatmessageModel();
            $ChatmessageModel->sendMessage([
                'groupid' => $groupid,
                'uid' => 0,
                'content' => '群公告已更新：' . mb_substr($notice, 0, 50),
                'msg_type' => 1,
                'createtime' => time()
            ]);

            return json_encode(array('status' => 1, 'msg' => '公告已更新'));
        }
    }

    /**
     * 禁言/解禁成员
     */
    public function muteMember()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');
            $targetUid = input('post.targetUid');
            $mute = input('post.mute', 1);

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member || $member['role'] < 1) {
                return json_encode(array('status' => 0, 'msg' => '没有权限'));
            }

            $target = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $targetUid]);
            if (!$target) {
                return json_encode(array('status' => 0, 'msg' => '该用户不在群内'));
            }
            if ($target['role'] >= $member['role']) {
                return json_encode(array('status' => 0, 'msg' => '不能操作同级或更高级别'));
            }

            $ChatmemberModel = new ChatmemberModel();
            $ChatmemberModel->where(['groupid' => $groupid, 'uid' => $targetUid])->update(['is_muted' => $mute]);

            return json_encode(array('status' => 1, 'msg' => $mute ? '已禁言' : '已解禁'));
        }
    }

    /**
     * 踢人
     */
    public function kickMember()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');
            $targetUid = input('post.targetUid');

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member || $member['role'] < 1) {
                return json_encode(array('status' => 0, 'msg' => '没有权限'));
            }

            $target = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $targetUid]);
            if (!$target) {
                return json_encode(array('status' => 0, 'msg' => '该用户不在群内'));
            }
            if ($target['role'] >= $member['role']) {
                return json_encode(array('status' => 0, 'msg' => '不能踢同级或更高级别'));
            }

            $ChatmemberModel = new ChatmemberModel();
            $ChatmemberModel->removeMember($groupid, $targetUid);

            return json_encode(array('status' => 1, 'msg' => '已移出群聊'));
        }
    }

    /**
     * 加人（发布者加入岗位报名者到群）
     */
    public function addMember()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');
            $targetUid = input('post.targetUid');

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member || $member['role'] < 1) {
                return json_encode(array('status' => 0, 'msg' => '没有权限'));
            }

            $group = ChatgroupModel::getOne(['id' => $groupid, 'status' => 1]);
            if (!$group) {
                return json_encode(array('status' => 0, 'msg' => '群聊不存在'));
            }

            if ($group['member_count'] >= $group['max_member']) {
                return json_encode(array('status' => 0, 'msg' => '群成员已满'));
            }

            $exist = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $targetUid]);
            if ($exist) {
                return json_encode(array('status' => 0, 'msg' => '该用户已在群内'));
            }

            $ChatmemberModel = new ChatmemberModel();
            $res = $ChatmemberModel->addMember([
                'groupid' => $groupid,
                'uid' => $targetUid,
                'role' => 0,
                'agreed_rule' => 0,
                'jointime' => time()
            ]);

            return json_encode(array('status' => $res ? 1 : 0, 'msg' => $res ? '已加入群聊' : '添加失败'));
        }
    }

    /**
     * 获取群成员列表
     */
    public function memberList()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $groupid = input('post.groupid');

            $member = ChatmemberModel::getOne(['groupid' => $groupid, 'uid' => $uid]);
            if (!$member) {
                return json_encode(array('status' => 0, 'msg' => '您不是群成员'));
            }

            $ChatmemberModel = new ChatmemberModel();
            $members = $ChatmemberModel->getMemberList($groupid);

            if ($members) {
                foreach ($members as $k => $v) {
                    if (!empty($v['avatarUrl'])) {
                        $members[$k]['avatarUrl'] = cdnurl($v['avatarUrl'], true);
                    }
                    $members[$k]['name'] = $v['nickname'] ?: '用户';
                    $members[$k]['jointime'] = date('Y-m-d', $v['jointime']);
                }
            }

            return json_encode(array('status' => 1, 'members' => $members ?: [], 'myRole' => $member['role']));
        }
    }

    /**
     * 企业认证登录检查（复用 Company 控制器模式）
     */
    public function isLogin()
    {
        $ctoken = input('post.ctoken');

        $valid = TokenService::verifyToken($ctoken);
        if ($valid) {
            $companyid = Token::getCurrentCid($ctoken);
            if ($companyid)
                $data = array('error' => 0, 'msg' => '正常', 'companyid' => $companyid);
            else
                $data = array('error' => 1, 'msg' => '数据异常');
        } else {
            $data = array('error' => 1, 'msg' => 'Token异常');
        }

        return $data;
    }
}
