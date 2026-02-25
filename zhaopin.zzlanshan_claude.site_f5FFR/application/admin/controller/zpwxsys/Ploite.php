<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Ploite as PloiteModel;
    
    
    /**
     *
     *
     * 用户管理
     */
    class Ploite extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Ploite;
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
                    $od = "p.createtime desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
               // $map['avatarUrl'] = array('neq', '');
                
                $ploite = new PloiteModel();
                $count = $ploite->getPloiteCount($map);
                
                $Nowpage = $offset / $limit + 1;
                
                $list = $ploite->getPloiteByWhere($map, $Nowpage, $limit, $od);
                
                $typelist = array(0=>'虚假信息',1=>'广告',2=>'其他');
                if ($list) {
                    
                    foreach ($list as $k => $v) {
                        $list[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                        $list[$k]['type'] = $typelist[$v['type']];
                        
                    }
                }
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
        
        
        
    }
