<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Companyrole as CompanyroleModel;
    use addons\zpwxsys\model\Companyrecord as CompanyrecordModel;
    use addons\zpwxsys\service\Token;
    use addons\zpwxsys\lib\exception\MissException;

    
    class Companyrole extends BaseController
    {
        //获取企业套餐列表
        public function getCompanyRoleList()
        {
            
            
           // $companyid = input('post.companyid');
            
               $ctoken = input('post.ctoken');
                
                $companyid = Token::getCurrentCid($ctoken);
                
                
            $od = "sort asc";
            $map['enabled'] = 1;
            
            $map['isinit'] = 0;
            
            
            $CompanyroleModel = new CompanyroleModel();
            $companyrolelist = $CompanyroleModel->getCompanyroleByWhere($map, $od);
            
            
            $CompanyrecordModel = new CompanyrecordModel();
            
            
            $rmap['companyid'] = $companyid;
            
            $companyrecordlist = $CompanyrecordModel->getCompanyrecordPoP($rmap);
            
            
            $data = array('companyrolelist' => $companyrolelist, 'companyrecordlist' => $companyrecordlist
            
            );
            
            
            return json_encode($data);
            
            
        }
        
        
    }