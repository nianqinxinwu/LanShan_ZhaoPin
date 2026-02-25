<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Company as CompanyModel;
    use app\admin\model\zpwxsys\Companyaccount as CompanyaccountModel;
    
    
    /**
     *
     *
     * @区域管理
     */
    class Companyaccount extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort','status'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Companyaccount;
        }
        
        
        public function getcompanylist()
        {
            
            if ($this->request->isAjax()) {
                $AreaModel = new AreaModel();
                
                $cityid = input('post.cityid');//字段
                
                $map['cityid'] = $cityid;
                
                $arealist = $AreaModel->getAreaListBycityId($map);
                
                
                $this->success('', '', $arealist);
                
            }
            
            
        }
        
        
        /**
         * 查看
         */
        public function index()
        {
            //当前是否为关联查询
            $this->relationSearch = true;
            //设置过滤方法
            $this->request->filter(['strip_tags', 'trim']);
            
            
            if ($this->request->isAjax()) {
                //如果发送的来源是Selectpage，则转发到Selectpage
                if ($this->request->request('keyField')) {
                    return $this->selectpage();
                }
                
                $map = [];
                
                $field = input('field');//字段
                $order = input('order');//排序方式
                if ($field && $order) {
                    $od = $field . " " . $order;
                } else {
                    $od = "id desc";
                }
                
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                $companyaccount = new CompanyaccountModel();
                $count = $companyaccount->getCompanyaccountCount($map);
                $Nowpage = $offset / $limit + 1;
                $list = $companyaccount->getCompanyaccountByWhere($map, $Nowpage, $limit, $od);
                
                if($list)
                {
                    foreach ($list as $k=>$v)
                    {
                        
                        $list[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                    }
                }
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
        /**
         * 添加
         */
        public function add()
        {
            
            
            if ($this->request->isPost()) {
                
                
                $params = $this->request->post("row/a");
    
    
             
                
                if ($params) {
                    $params = $this->preExcludeFields($params);
                    
                    if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                        $params[$this->dataLimitField] = $this->auth->id;
                    }
                    $result = false;
                    $companyaccount = new CompanyaccountModel();
                    $params['password'] = md5($params['password']);
                    $params['createtime'] = time();
                    $result = $companyaccount->insertCompanyaccount($params);
                    
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were inserted'));
                    }
                }
                $this->error(__('Parameter %s can not be empty', ''));
            }
    
            $company = new CompanyModel();
    
            $map = [];
            $od = 'g.sort desc';
            $companylist = $company->getAllCompany($map, $od);
            
            $this->view->assign("companylist", $companylist);
            return $this->view->fetch();
        }
        
        /**
         * 编辑
         */
        public function edit($ids = null)
        {
            
            $companyaccount = new CompanyaccountModel();
            $company = new CompanyModel();
    
            $map = [];
            $od = 'g.sort desc';
            $companylist = $company->getAllCompany($map, $od);
            
            
            if ($this->request->isPost()) {
                $params = $this->request->post("row/a");
                if ($params) {
                    
                    $params = $this->preExcludeFields($params);
                    $result = false;
                    
                    if($params['password']!='')
                    {
                        $params['password'] = md5($params['password']);
                        
                    }else{
    
                        unset($params['password']);
    
                    }
                    
                    
                    $result = $companyaccount->updateCompanyaccount($params);
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                    
                    
                }
                
                
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $row = $companyaccount->getOneCompanyaccount($ids);
            
            
            if (!$row) {
                $this->error(__('No Results were found'));
            }
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                if (!in_array($row[$this->dataLimitField], $adminIds)) {
                    $this->error(__('You have no permission'));
                }
            }
            $this->view->assign("row", $row);
            $this->view->assign("companylist", $companylist);
            return $this->view->fetch();
        }
        
    }
