<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Company as CompanyModel;
    use app\admin\model\zpwxsys\Area as AreaModel;
    use app\admin\model\zpwxsys\City as CityModel;
    use app\admin\model\zpwxsys\Companyrole as CompanyroleModel;
    use addons\zpwxsys\service\CompanyRecord as CompanyRecordService;
    
    
    /**
     *
     *
     * @企业管理
     */
    class Company extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','isrecommand'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Company;
        }
        
        
        public function getarealist()
        {
            
            if ($this->request->isAjax()) {
                $AreaModel = new AreaModel();
                
                $cityid = input('post.cityid');//字段
                
                $map['cityid'] = $cityid;
                
                $arealist = $AreaModel->getAreaListBycityId($map);
                
                
                $this->success('', '', $arealist);
                
            }
            
            
        }
        
        
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
                    $od = "createtime desc";
                }
                
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                
                $filter = $this->request->get("filter", '');
                $filter = (array)json_decode($filter, true);
                
                if(count($filter)>0)
                {
                
                
                if (array_key_exists("cityname",$filter))
                    {
    
                        $map['c.name'] = $filter['cityname'];
                    }
                    
                if (array_key_exists("areaname",$filter))
                    {
    
                        $map['a.name'] = $filter['areaname'];
                    }
                    
                    
                
                if (array_key_exists("companyname",$filter))
                    {
                            
                        $map['g.companyname'] = ['like',"%" . $filter['companyname'] . "%"];
                    }
    
                if (array_key_exists("tel",$filter))
                    {
                            
                        $map['g.tel'] = $filter['tel'];
                    }
                
                }
                
                
                $company = new CompanyModel();
                $count = $company->getCompanyCount($map);
                
                $Nowpage = $offset / $limit + 1;
                
                
                $list = $company->getCompanyByWhere($map, $Nowpage, $limit, $od);
                
                if ($list) {
                    foreach ($list as $k => $v) {
                        
                        $list[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                        
                        $roleinfo = CompanyRecordService::getStaticFirst($v['id']);
                        
                        if(!empty($roleinfo))
                        {
                            
                           $list[$k]['rolename'] = '职位:'.$roleinfo[0]['totaljobnum'].';简历:'.$roleinfo[0]['totalnotenum'].';'; 
                        }else{
                           
                           $list[$k]['rolename'] = '职位:0;简历:0;';
                            
                        }
                    
                        
                        
                        
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
                    $companyrole = new CompanyModel();
                    
                    $params['createtime'] = time();
                    
                    $result = $companyrole->insertCompany($params);
                    
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were inserted'));
                    }
                }
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $city = new CityModel();
            
            $citylist = $city->getCity();
            
            $this->view->assign("citylist", $citylist);
            return $this->view->fetch();
        }
        
        /**
         * 编辑
         */
        public function edit($ids = null)
        {
            
            $company = new CompanyModel();
            
            if ($this->request->isPost()) {
                $params = $this->request->post("row/a");
                if ($params) {
                    
                    $params = $this->preExcludeFields($params);
                    $result = false;
                    
                    
                    $result = $company->updateCompany($params);
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                    
                    
                }
                
                
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $row = $company->getOneCompany($ids);
            
            
            if (!$row) {
                $this->error(__('No Results were found'));
            }
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                if (!in_array($row[$this->dataLimitField], $adminIds)) {
                    $this->error(__('You have no permission'));
                }
            }
            $city = new CityModel();
            
            $citylist = $city->getCity();
            
            
            $area = new AreaModel();
            
            $params['cityid'] = $row['cityid'];
            
            $arealist = $area->getAreaListBycityId($params);
            
            $this->view->assign("citylist", $citylist);
            $this->view->assign("arealist", $arealist);
            
            
            $this->view->assign("row", $row);
            return $this->view->fetch();
        }
        
        public function  setcompanyrole($companyid = null)
        {
            
            if ($this->request->isPost()) {
                
                $params = $this->request->post("row/a");
                
                $companyid = $params['companyid'];
               
                $roleid = $params['roleid'];
                
                
               $companyrole  =  CompanyroleModel::getOne(array('id'=>$roleid));
                
            
                
                 if ($companyrole) {
                            
                            $CompanyRecordService = new CompanyRecordService($companyid);
                            
                            $CompanyRecordService->topnum = $companyrole['topnum'];
                            
                            $CompanyRecordService->notenum = $companyrole['notenum'];
                            
                            $CompanyRecordService->jobnum = $companyrole['jobnum'];
                            
                            $CompanyRecordService->mark = '【后台充值】'.$companyrole['title'];
                            
                            $CompanyRecordService->setNewFreeRecord();
                            
                            $this->success();
                            
                        }else{
                            
                             $this->error(__('操作失败'));
                        }
                
            
            }
            
            $map = [];
            $map['enabled'] = 1;
            $map['isinit'] = 0 ;
            $od = 'sort asc';
            $CompanyroleModel = new CompanyroleModel();
            $companyrolelist = $CompanyroleModel->getAllCompanyrole($map,$od);
            
            $this->view->assign('companyid',$companyid);
            $this->view->assign('companyrolelist',$companyrolelist);
      
            
            
            
            return $this->view->fetch();
            
            
            
            
        }
        
    }
