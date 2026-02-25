<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Active as ActiveModel;
    use app\admin\model\zpwxsys\Activerecord as ActiverecordModel;
    
    
    /**
     *
     *
     * @icon fa fa-circle-o
     */
    class Active extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Active;
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
                    $od = "sort desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $active = new ActiveModel();
                $count = $active->getActiveCount($map);
                
                $Nowpage = $offset / $limit + 1;
                $list = $active->getActiveByWhere($map, $Nowpage, $limit, $od);
                
                if($list)
                {
                    foreach ($list as $k=>$v)
                    {
                    
                        $list[$k]['count'] = ActiverecordModel::getRecordCount(array('aid'=>$v['id']));
                   
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
                    
                    
                    $begintime = strtotime($params['begintime']);
                    $endtime = strtotime($params['endtime']);
                    if ($begintime >= $endtime) {
                        $this->error(__('结束日期不能小于开始日期'));
                    }
                    
                    
                    $active = new ActiveModel();
                    $result = $active->insertActive($params);
                    
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were inserted'));
                    }
                }
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            return $this->view->fetch();
        }
        
        /**
         * 编辑
         */
        public function edit($ids = null)
        {
            
            $active = new  ActiveModel();
            
            if ($this->request->isPost()) {
                $params = $this->request->post("row/a");
                if ($params) {
                    
                    $params = $this->preExcludeFields($params);
                    $result = false;
                    $begintime = strtotime($params['begintime']);
                    $endtime = strtotime($params['endtime']);
                    if ($begintime >= $endtime) {
                        $this->error(__('结束日期不能小于开始日期'));
                    }
                    
                    $result = $active->updateActive($params);
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                    
                    
                }
                
                
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $row = $active->getOneActive($ids);
            
            
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
            return $this->view->fetch();
        }
        
    }

