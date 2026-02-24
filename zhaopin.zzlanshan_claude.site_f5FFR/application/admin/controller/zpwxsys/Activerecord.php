<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Activerecord as ActiverecordModel;
    
    
    /**
     *
     *
     * @icon fa fa-circle-o
     */
    class Activerecord extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Activerecord;
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
                    $od = "r.createtime desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $active = new ActiverecordModel();
                $count = $active->getCount($map);
                
                $Nowpage = $offset / $limit + 1;
                $list = $active->getListByWhere($map, $Nowpage, $limit, $od);
                
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
        
      
        
    }

