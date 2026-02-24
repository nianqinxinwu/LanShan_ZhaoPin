<?php

namespace addons\zpwxsys\controller\v1;

use addons\zpwxsys\controller\BaseController;
use addons\zpwxsys\model\User as UserModel;
use addons\zpwxsys\model\Note as NoteModel;
use addons\zpwxsys\model\Company as CompanyModel;
use addons\zpwxsys\model\Jobrecord as JobrecordModel;
use addons\zpwxsys\model\Comment as CommentModel;
use addons\zpwxsys\model\Coinrecord as CoinrecordModel;
use addons\zpwxsys\service\Token;

class Usercard extends BaseController
{

    /**
     * 获取报名者名片
     * 参数: uid (目标用户ID，不传则获取自己的)
     */
    public function getApplicantCard()
    {
        $targetUid = input('post.uid');
        $currentUid = Token::getCurrentUid();

        if (!$targetUid) {
            $targetUid = $currentUid;
        }

        // 获取用户基本信息
        $userinfo = UserModel::getByUserWhere(array('id' => $targetUid));
        if (!$userinfo) {
            return json_encode(array('status' => 1, 'msg' => '用户不存在'));
        }

        // 获取简历信息
        $noteinfo = NoteModel::getNoteByuid($targetUid);

        // 统计兼职经历次数（已完成的工作记录 status >= 5）
        $experienceCount = JobrecordModel::getJobrecordToal(array(
            'uid' => $targetUid,
            'status' => array('>=', 5)
        ));

        // 统计签到次数
        $signinCount = JobrecordModel::getJobrecordToal(array(
            'uid' => $targetUid,
            'signed_in' => 1
        ));

        // 统计未签到次数（已录用但未签到 status >= 3 且 signed_in = 0）
        $nosigninCount = JobrecordModel::getJobrecordToal(array(
            'uid' => $targetUid,
            'status' => array('>=', 3),
            'signed_in' => 0
        ));

        // 获取评价分数（被评价的平均分）
        $CommentModel = new CommentModel();
        $avgScore = $this->getAvgScore($targetUid);

        // 手机号脱敏
        $phoneMask = '';
        if ($noteinfo && !empty($noteinfo['tel'])) {
            $phoneMask = $this->maskPhone($noteinfo['tel']);
        } elseif (!empty($userinfo['tel'])) {
            $phoneMask = $this->maskPhone($userinfo['tel']);
        }

        $card = array(
            'uid' => $targetUid,
            'avatar' => $userinfo['avatarUrl'] ?: '',
            'nickname' => $userinfo['nickname'] ?: '',
            'phone_mask' => $phoneMask,
            'school' => $userinfo['school'] ?: '',
            'major' => $userinfo['major'] ?: '',
            'name' => $noteinfo ? ($noteinfo['name'] ?: '') : '',
            'sex' => $noteinfo ? $noteinfo['sex'] : 0,
            'age' => $noteinfo ? $noteinfo['age'] : 0,
            'education' => $noteinfo ? ($noteinfo['education'] ?: '') : '',
            'experience_count' => $experienceCount,
            'signin_count' => $signinCount,
            'nosignin_count' => $nosigninCount,
            'rating' => $avgScore,
            'is_self' => ($targetUid == $currentUid) ? 1 : 0
        );

        return json_encode(array('status' => 0, 'msg' => '请求数据正常', 'card' => $card));
    }

    /**
     * 获取发布者名片
     * 参数: companyid
     */
    public function getPublisherCard()
    {
        $companyid = input('post.companyid');
        $currentUid = Token::getCurrentUid();

        if (!$companyid) {
            // 如果没传companyid，从ctoken获取
            $ctoken = input('post.ctoken');
            if ($ctoken) {
                $valid = Token::verifyToken($ctoken);
                if ($valid) {
                    $companyid = Token::getCurrentCid($ctoken);
                }
            }
        }

        if (!$companyid) {
            return json_encode(array('status' => 1, 'msg' => '参数错误'));
        }

        $companyinfo = CompanyModel::getCompany($companyid);
        if (!$companyinfo) {
            return json_encode(array('status' => 1, 'msg' => '企业不存在'));
        }

        // 获取发布者用户信息
        $userinfo = null;
        if ($companyinfo['uid'] > 0) {
            $userinfo = UserModel::getByUserWhere(array('id' => $companyinfo['uid']));
        }

        // 统计已发布岗位数
        $jobCount = \addons\zpwxsys\model\Job::getCount(array('companyid' => $companyid, 'status' => 1));

        // 获取企业评价平均分
        $CommentModel = new CommentModel();
        $commentMap = array('c.companyid' => $companyid);
        $od = 'c.create_time desc';
        $commentlist = $CommentModel->getCommentList($commentMap, $od);
        $avgScore = 0;
        if (count($commentlist) > 0) {
            $totalScore = 0;
            foreach ($commentlist as $c) {
                $totalScore += $c['score'];
            }
            $avgScore = round($totalScore / count($commentlist), 1);
        }

        // 判断是否是自己的企业
        $isSelf = 0;
        if ($companyinfo['uid'] > 0 && $companyinfo['uid'] == $currentUid) {
            $isSelf = 1;
        }

        $card = array(
            'companyid' => $companyid,
            'avatar' => $userinfo ? ($userinfo['avatarUrl'] ?: '') : '',
            'nickname' => $userinfo ? ($userinfo['nickname'] ?: '') : '',
            'phone' => $companyinfo['tel'] ?: '',
            'company_name' => $companyinfo['companyname'] ?: '',
            'short_name' => $companyinfo['shortname'] ?: '',
            'business_license' => !empty($companyinfo['business_license']) ? cdnurl($companyinfo['business_license'], true) : '',
            'company_logo' => $companyinfo['thumb'] ?: '',
            'mastername' => $companyinfo['mastername'] ?: '',
            'address' => $companyinfo['address'] ?: '',
            'companycate' => $companyinfo['companycate'] ?: '',
            'companytype' => $companyinfo['companytype'] ?: '',
            'companyworker' => $companyinfo['companyworker'] ?: '',
            'job_count' => $jobCount,
            'rating' => $avgScore,
            'comment_count' => count($commentlist),
            'is_self' => $isSelf
        );

        return json_encode(array('status' => 0, 'msg' => '请求数据正常', 'card' => $card));
    }

    /**
     * 保存报名者名片（学校、专业信息）
     */
    public function saveApplicantCard()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();

            $school = input('post.school');
            $major = input('post.major');

            $userinfo = UserModel::getByUserWhere(array('id' => $uid));
            if (!$userinfo) {
                return json_encode(array('status' => 1, 'msg' => '用户不存在'));
            }

            $UserModel = new UserModel();
            $param = array(
                'id' => $uid,
                'school' => $school ?: '',
                'major' => $major ?: ''
            );

            $result = $UserModel->updateUser($param);

            return json_encode(array('status' => 0, 'msg' => '保存成功'));
        }
    }

    /**
     * 保存发布者名片（营业执照）
     */
    public function savePublisherCard()
    {
        if (request()->isPost()) {
            $msg = $this->isLogin();
            if ($msg['error'] != 0) {
                return json_encode(array('status' => 1, 'msg' => '请先登录企业账号'));
            }

            $companyid = $msg['companyid'];
            $business_license = input('post.business_license');
            $companyname = input('post.companyname');

            // 校验：填写公司全称 → 营业执照必填
            if (!empty($companyname) && empty($business_license)) {
                return json_encode(array('status' => 1, 'msg' => '填写公司全称时营业执照为必填项'));
            }

            $CompanyModel = new CompanyModel();
            $param = array(
                'id' => $companyid
            );

            if ($business_license !== null) {
                $param['business_license'] = $business_license;
            }
            if ($companyname !== null) {
                $param['companyname'] = $companyname;
            }

            $CompanyModel->updateCompany($param);

            return json_encode(array('status' => 0, 'msg' => '保存成功'));
        }
    }

    /**
     * 解锁手机号（临时通话功能，需消耗1章鱼币）
     */
    public function unlockPhone()
    {
        if (request()->isPost()) {
            $uid = Token::getCurrentUid();
            $targetUid = input('post.target_uid');
            $targetType = input('post.target_type'); // applicant or publisher

            if (!$targetUid) {
                return json_encode(array('status' => 1, 'msg' => '参数错误'));
            }

            // 消耗1章鱼币
            $consumeResult = CoinrecordModel::consume($uid, 1, 'tempcall', $targetUid, '临时通话解锁手机号');
            if ($consumeResult['status'] != 0) {
                return json_encode(array('status' => 2, 'msg' => $consumeResult['msg'], 'balance' => $consumeResult['balance'] ?? 0));
            }

            $phone = '';
            if ($targetType == 'publisher') {
                $companyid = input('post.companyid');
                if ($companyid) {
                    $companyinfo = CompanyModel::getCompany($companyid);
                    $phone = $companyinfo ? $companyinfo['tel'] : '';
                }
            } else {
                $noteinfo = NoteModel::getNoteByuid($targetUid);
                if ($noteinfo) {
                    $phone = $noteinfo['tel'];
                } else {
                    $userinfo = UserModel::getByUserWhere(array('id' => $targetUid));
                    $phone = $userinfo ? $userinfo['tel'] : '';
                }
            }

            if (empty($phone)) {
                return json_encode(array('status' => 1, 'msg' => '未找到联系方式'));
            }

            return json_encode(array('status' => 0, 'msg' => '解锁成功', 'phone' => $phone, 'balance' => $consumeResult['balance']));
        }
    }

    /**
     * 检测是否登录（企业）
     */
    public function isLogin()
    {
        $ctoken = input('post.ctoken');

        $valid = Token::verifyToken($ctoken);
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

    /**
     * 手机号脱敏
     */
    private function maskPhone($phone)
    {
        if (strlen($phone) >= 7) {
            return substr($phone, 0, 3) . '****' . substr($phone, -4);
        }
        return $phone;
    }

    /**
     * 获取用户被评价的平均分
     */
    private function getAvgScore($uid)
    {
        // 查询该用户完成工作后被评价的记录
        // 通过 jobrecord 关联 comment：用户参与的岗位对应的企业的评价
        // 简化实现：获取用户作为求职者完成工作后，企业对其的评价
        $score = \think\Db::name('zpwxsys_comment')
            ->where('uid', $uid)
            ->avg('score');

        return $score ? round($score, 1) : 0;
    }
}
