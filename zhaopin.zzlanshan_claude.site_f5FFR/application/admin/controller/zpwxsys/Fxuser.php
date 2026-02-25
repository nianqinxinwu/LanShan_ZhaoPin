<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Agent as AgentModel;
    use app\admin\model\zpwxsys\User as UserModel;
    /**
     *
     *
     * 推荐用户记录
     */
    class Fxuser extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','ischeck'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\User;
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
                
                $map['u.tid'] = ['gt',0];
                
                $field = input('field');//字段
                $order = input('order');//排序方式
                if ($field && $order) {
                    $od = $field . " " . $order;
                } else {
                    $od = "u.create_time desc";
                    
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
                
        
                $user = new UserModel();
                $count = $user->getWxuserCount($map);
                $Nowpage = $offset / $limit + 1;
           
                $list = $user->getWxuserByWhere($map, $Nowpage, $limit, $od);
                if($list)
                {
                    foreach ($list as $k=>$v) {
                        
                      $list[$k]['create_time'] = date('Y-m-d', $v['create_time']);
                        
                    }
                }
                
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
    
 
        
    }
