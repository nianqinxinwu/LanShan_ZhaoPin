<?php


    namespace addons\zpwxsys\controller\v1;

    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\User as UserModel;
    use addons\zpwxsys\service\Token;

    class Login extends BaseController
    {


        public function checkBind()
        {


            $uid = Token::getCurrentUid();

           // $ctoken = input('post.ctoken');
          //  $companyid = Token::getCurrentCid($ctoken);



            $map['id'] = $uid;

            $userinfo = UserModel::getByUserWhere($map);


            if (!$userinfo) {
                       return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
                    }

            if ($userinfo['tel'] == '') {
                $isbind = false;
            } else {

                $isbind = true;
            }

            $data = array('isbind' => $isbind,'status'=>1,'msg'=>'请求数据正常');
            return json_encode($data);


        }


        public function userLogin()
        {

            $uid = Token::getCurrentUid();
            $tel = input('post.tel');

            if (!$tel) {
                return json_encode(['status' => 0, 'msg' => '请输入手机号']);
            }

            $userinfo = UserModel::getByUserWhere(['id' => $uid]);

            if (!$userinfo) {
                return json_encode(['status' => 0, 'msg' => '用户不存在，请先授权登录']);
            }

            // 检查手机号是否已绑定
            if ($userinfo['tel'] == '' || $userinfo['tel'] != $tel) {
                // 手机号未绑定或不匹配，更新手机号
                $userModel = new UserModel();
                $param['id'] = $uid;
                $param['tel'] = $tel;
                $userModel->updateUser($param);
            }

            $data = [
                'status' => 1,
                'msg' => '登录成功',
                'data' => [
                    'uid' => $uid,
                    'nickname' => $userinfo['nickname'],
                    'avatarUrl' => $userinfo['avatarUrl'],
                    'tel' => $tel
                ]
            ];
            return json_encode($data);
        }

        public function userRegister()
        {

            $uid = Token::getCurrentUid();
            $tel = input('post.tel');
            $nickname = input('post.nickname');

            if (!$tel) {
                return json_encode(['status' => 0, 'msg' => '请输入手机号']);
            }

            $userinfo = UserModel::getByUserWhere(['id' => $uid]);

            if (!$userinfo) {
                return json_encode(['status' => 0, 'msg' => '用户不存在，请先授权登录']);
            }

            // 更新用户信息
            $userModel = new UserModel();
            $param['id'] = $uid;
            $param['tel'] = $tel;
            if ($nickname) {
                $param['nickname'] = $nickname;
            }
            $userModel->updateUser($param);

            $data = [
                'status' => 1,
                'msg' => '注册成功',
                'data' => [
                    'uid' => $uid,
                    'tel' => $tel
                ]
            ];
            return json_encode($data);
        }


        public function userSysinit()
        {
            $uid = Token::getCurrentUid();

            $userinfo = UserModel::getByUserWhere(['id' => $uid]);

            if (!$userinfo) {
                return json_encode(['status' => 0, 'msg' => '用户不存在']);
            }

            $data = [
                'status' => 1,
                'msg' => '请求数据正常',
                'data' => [
                    'uid' => $uid,
                    'nickname' => $userinfo['nickname'],
                    'avatarUrl' => $userinfo['avatarUrl'],
                    'tel' => $userinfo['tel'],
                    'isbind' => $userinfo['tel'] != '' ? true : false
                ]
            ];
            return json_encode($data);
        }


        /**
         * 设置用户角色
         * user_role: 1=报名者, 2=发布者
         */
        public function setUserRole()
        {
            $uid = Token::getCurrentUid();
            $user_role = input('post.user_role/d', 0);

            if (!in_array($user_role, [1, 2])) {
                return json_encode(['status' => 0, 'msg' => '角色参数错误']);
            }

            UserModel::ensureUserRoleField();

            $userModel = new UserModel();
            $param['id'] = $uid;
            $param['user_role'] = $user_role;
            $userModel->updateUser($param);

            return json_encode(['status' => 1, 'msg' => '设置成功', 'data' => ['user_role' => $user_role]]);
        }

        /**
         * 获取当前用户角色
         */
        public function getUserRole()
        {
            $uid = Token::getCurrentUid();

            UserModel::ensureUserRoleField();

            $userinfo = UserModel::getByUserWhere(['id' => $uid]);

            if (!$userinfo) {
                return json_encode(['status' => 0, 'msg' => '用户不存在']);
            }

            $user_role = isset($userinfo['user_role']) ? intval($userinfo['user_role']) : 0;

            return json_encode(['status' => 1, 'msg' => '请求数据正常', 'data' => ['user_role' => $user_role]]);
        }


    }