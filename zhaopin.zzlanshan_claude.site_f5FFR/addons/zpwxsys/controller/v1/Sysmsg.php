<?php


    namespace addons\zpwxsys\controller\v1;

    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Sysmsg as SysmsgModel;
    use addons\zpwxsys\service\Token;

    class Sysmsg extends BaseController
    {

        public function getSysmsgList()
        {

            $uid = Token::getCurrentUid();

            SysmsgModel::ensureNewFields();

            $od = "createtime desc";

            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;

            $limits = $Nowpage * 20;
            $Nowpage = 1;

            $map['uid'] = $uid;

            $readstatus = input('post.readstatus');
            $readstatusVal = null;
            if ($readstatus !== null && $readstatus !== '') {
                $readstatusVal = intval($readstatus);
            }

            $SysmsgModel = new SysmsgModel();
            $sysmsglist = $SysmsgModel->getSysmsgByWhere($map, $Nowpage, $limits, $od, $readstatusVal);

            $unreadCount = SysmsgModel::getUnreadCount($uid);

            $data = array('sysmsglist' => $sysmsglist, 'unreadCount' => $unreadCount);


            return json_encode($data);


        }

        public function updateSysmsg()
        {

            $uid = Token::getCurrentUid();

            $param['uid'] = $uid;

            $param['status'] = 1;

            $SysmsgModel = new SysmsgModel();

            $SysmsgModel->updateSysmsg($param);


            $data = array('status' => 0);


            return json_encode($data);

        }

        public function markAsRead()
        {
            $uid = Token::getCurrentUid();
            $id = input('post.id');

            if (!$id) {
                return json_encode(array('status' => 1, 'msg' => '参数错误'));
            }

            $SysmsgModel = new SysmsgModel();
            $SysmsgModel->markAsReadById($id, $uid);

            return json_encode(array('status' => 0, 'msg' => '标记成功'));
        }

    }
