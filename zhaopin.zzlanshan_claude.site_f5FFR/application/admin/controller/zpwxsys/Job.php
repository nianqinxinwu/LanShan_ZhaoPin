<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Company as CompanyModel;
    use app\admin\model\zpwxsys\Job as JobModel;
    use app\admin\model\zpwxsys\Current as CurrentModel;
    use app\admin\model\zpwxsys\Worktype as WorktypeModel;
    use app\admin\model\zpwxsys\Jobprice as JobpriceModel;
    use app\admin\model\zpwxsys\Jobcate as JobcateModel;

    /**
     *
     *
     * 职位管理
     */
    class Job extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','ischeck'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Job;
            // $this->view->assign("statusList", $this->model->getStatusList());
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
                    $od = "j.sort desc";
                    
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $filter = $this->request->get("filter", '');
                $filter = (array)json_decode($filter, true);
                
                if(count($filter)>0)
                {
                
                
                if (array_key_exists("companyname",$filter))
                    {
                            
                        $map['m.companyname'] = ['like',"%" . $filter['companyname'] . "%"];
                    }
    
                if (array_key_exists("jobtitle",$filter))
                    {
                            
                        $map['j.jobtitle'] = ['like',"%" . $filter['jobtitle'] . "%"];
                    }
                
                }
                
        
                $job = new JobModel();
                $count = $job->getJobCount($map);
                $Nowpage = $offset / $limit + 1;
           
                $list = $job->getJobByWhere($map, $Nowpage, $limit, $od);
                if($list)
                {
                    foreach ($list as $k=>$v) {
                        
                        $check = $v['ischeck'] == 0 ? '未审核' : '已审核通过';
                        
                        $list[$k]['ischeck'] = $check;
                        
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
                    $job = new JobModel();
                    
                    $params['worktypeid'] = $params['type'];
                    
                    $params['special'] = implode(',', $params['special']);
                    
              
                    
                    $moneyinfo = JobpriceModel::getOne(array('id' => $params['jobpriceid']));
                    
                    $params['money'] = $moneyinfo['name'];
                    
                    $params['toptime'] = 0;
                    
                    $params['createtime'] = $params['updatetime'] = time();
                    
                    $result = $job->insertJob($params);
                    
                    
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
            
            
            $maps['enabled'] = 1;
            $od = 'sort desc';
            
            $currentlist = CurrentModel::getList($maps, $od);
            
            $worktypelist = WorktypeModel::getList($maps, $od);
            
            
            $mapc = [];
            $odc = 'sort  desc';
            
            $jobcate = new JobcateModel();
            
            $jobprice = new JobpriceModel();
            
            $jobcatelist = $jobcate->getAllJobcate($mapc, $odc);
            $jobpricelist = $jobprice->getJobprice();
            
            
            $this->view->assign("companylist", $companylist);
            $this->view->assign("currentlist", $currentlist);
            $this->view->assign("worktypelist", $worktypelist);
            $this->view->assign("jobcatelist", $jobcatelist);
            $this->view->assign("jobpricelist", $jobpricelist);
            return $this->view->fetch();
        }
        
        /**
         * 编辑
         */
        public function edit($ids = null)
        {
            
            
            $job = new JobModel();
            
            if ($this->request->isPost()) {
                $params = $this->request->post("row/a");
                if ($params) {
                    
                    $params = $this->preExcludeFields($params);
                    $result = false;
                    
                    
                    $moneyinfo = !empty($params['jobpriceid']) ? JobpriceModel::getOne(array('id' => $params['jobpriceid'])) : null;

                    $params['special'] = isset($params['special']) && is_array($params['special']) ? implode(',', $params['special']) : '';

                    if ($moneyinfo) {
                        $params['money'] = $moneyinfo['name'];
                    }
                    
                    
                    $result = $job->updateJob($params);
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                    
                    
                }
                
                
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $row = $job->getOneJob($ids);

            if ($row && is_null($row['special'])) {
                $row['special'] = '';
            }

            if (!$row) {
                $this->error(__('No Results were found'));
            }
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                if (!in_array($row[$this->dataLimitField], $adminIds)) {
                    $this->error(__('You have no permission'));
                }
            }
            
            
            $company = new CompanyModel();
            
            $map = [];
            $od = 'g.sort desc';
            $companylist = $company->getAllCompany($map, $od);
            
            
            $maps['enabled'] = 1;
            $od = 'sort desc';
            
            $currentlist = CurrentModel::getList($maps, $od);
            
            $worktypelist = WorktypeModel::getList($maps, $od);
            
            
            $mapc = [];
            $odc = 'sort  desc';
            
            $jobcate = new JobcateModel();
            
            $jobprice = new JobpriceModel();
            
            $jobcatelist = $jobcate->getAllJobcate($mapc, $odc);
            $jobpricelist = $jobprice->getJobprice();
            
            
            $this->view->assign("companylist", $companylist);
            $this->view->assign("currentlist", $currentlist);
            $this->view->assign("worktypelist", $worktypelist);
            $this->view->assign("jobcatelist", $jobcatelist);
            $this->view->assign("jobpricelist", $jobpricelist);
            
            $speciallist = array('五险', '五险一金', '补充医疗保险', '员工旅游', '交通补贴', '餐饮补贴', '出国机会', '年终奖金', '定期体检', '节日福利', '双休', '调休', '年假', '加班补贴', '职位晋升', '包食宿');
            
            $this->view->assign("speciallist", $speciallist);
            
            
            $this->view->assign("row", $row);
            return $this->view->fetch();
        }
        
    }
