<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\User as WxuserModel;

    
    /**
     *
     *
     * 用户管理
     */
    class User extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort'];
        
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
                
                
                if (array_key_exists("nickname",$filter))
                    {
                            
                        $map['u.nickname'] = ['like',"%" . $filter['nickname'] . "%"];
                    }
    
                if (array_key_exists("tel",$filter))
                    {
                            
                        $map['u.tel'] = ['like',"%" . $filter['tel'] . "%"];
                    }
                
              
                    
                
                }
                
                
                $map['u.avatarUrl'] = array('neq', '');
                
                $wxuser = new WxuserModel();
                $count = $wxuser->getWxuserCount($map);
                
                $Nowpage = $offset / $limit + 1;
                
                $list = $wxuser->getWxuserByWhere($map, $Nowpage, $limit, $od);
                if ($list) {
                    
                    foreach ($list as $k => $v) {
                        
                        
                        $list[$k]['createtime'] = date('Y-m-d', $v['create_time']);
                        
                        // $list[$k]['tel'] = '演示不可看';
                    }
                }
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
     
   
        
    }
