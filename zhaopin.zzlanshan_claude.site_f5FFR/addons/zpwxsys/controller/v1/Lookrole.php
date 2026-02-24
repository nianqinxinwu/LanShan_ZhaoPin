<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Lookrole as LookroleModel;
    use addons\zpwxsys\model\Lookrolerecord as LookrolerecordModel;
    use addons\zpwxsys\service\Token;
    
    class Lookrole extends BaseController
    {
        
        public function getLookRoleList()
        {
            
            
            $od = "sort desc";
            $map['enabled'] = 1;
            
            $LookroleModel = new LookroleModel();
            $lookrolelist = $LookroleModel->getLookroleByWhere($map, $od);
            
            $LookrolerecordModel = new LookrolerecordModel();
            
            $rod = 'create_time desc';
            
            $rmap['uid'] = Token::getCurrentUid();
            
            $lookrolerecordlist = $LookrolerecordModel->getLookRoleRecordByWhere($rmap, $rod);
            
            $data = array('lookrolelist' => $lookrolelist, 'lookrolerecordlist' => $lookrolerecordlist
            
            );
            
            
            return json_encode($data);
            
            
        }
        
        
    }