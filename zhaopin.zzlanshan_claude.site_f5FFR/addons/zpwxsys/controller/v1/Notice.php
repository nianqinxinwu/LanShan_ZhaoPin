<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Cate as CateModel;
    use addons\zpwxsys\model\Notice as NoticeModel;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;
    use addons\zpwxsys\service\Token;
    use think\Controller;
    
    class Notice extends BaseController
    {
        
        
        public function getNoticedetail()
        {
            
            $id = input('post.id');
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            
            
            $map = array('id' => $id);
            
            $NoticeModel = new NoticeModel();
            
            
            $noticeinfo = $NoticeModel->getNoticeDetail($map);
            
            if (!$noticeinfo) {
               return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            
            $data = array('noticeinfo' => $noticeinfo,'status'=>0,'msg'=>'请求数据正常');
            
            return json_encode($data);
        }
        
        
    }