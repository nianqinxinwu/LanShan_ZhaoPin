<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Task as TaskModel;
    use app\admin\model\zpwxsys\Company as CompanyModel;
    use app\admin\model\zpwxsys\Job as JobModel;

    /**
     *
     *
     * 任务管理
     */
    class Task extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','ischeck'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Task;
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
                
        
                $task = new TaskModel();
                $count = $task->getTaskCount($map);
                $Nowpage = $offset / $limit + 1;
           
                $list = $task->getTaskByWhere($map, $Nowpage, $limit, $od);
                if($list)
                {
                    foreach ($list as $k=>$v) {
                        
                      ///  $check = $v['ischeck'] == 0 ? '未审核' : '已审核通过';
                        
                      //  $list[$k]['ischeck'] = $check;
                        
                    }
                }
                
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        

        /**
         * 编辑
         */
        public function edit($ids = null)
        {
            
            
            $taskModel = new TaskModel();
            
            if ($this->request->isPost()) {
                $params = $this->request->post("row/a");
                if ($params) {
                    
                    $params = $this->preExcludeFields($params);
                    $result = false;
                    
                
                    
                    
                    $result = $taskModel->updateTask($params);
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                    
                    
                }
                
                
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $row = $taskModel->getOneTask($ids);
            
            
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
            
            
            $this->view->assign("companylist", $companylist);

            $jobModel = new JobModel();
            $jmap = [];
            $jmap['j.companyid'] = $row['companyid'];
            $od = 'j.id desc';
            $joblist = $jobModel->getJobList($jmap, $od);
            
            $this->view->assign("joblist", $joblist);
            
            
        
            

            
            $this->view->assign("row", $row);
            return $this->view->fetch();
        }
        
    }
