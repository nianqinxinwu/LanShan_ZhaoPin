<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Order as OrderModel;
    use app\admin\model\zpwxsys\Company as CompanyModel;
    use app\admin\model\zpwxsys\User as WxuserModel;
    
    
    /**
     *
     *
     * 订单管理
     */
    class Order extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Order;
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
                    $od = "create_time desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $order = new OrderModel();
                $count = $order->getListCount($map);
                $Nowpage = $offset / $limit + 1;
                
                $list = $order->getListByWhere($map, $Nowpage, $limit, $od);
                
                $usermodel = new WxuserModel();
                $companymodel = new CompanyModel();
                if ($list) {
                    
                    foreach ($list as $k => $v) {
                        
                        $userinfo = $usermodel->getOneWxUser($v['user_id']);
                        
                        $list[$k]['status'] = $v['status'] == 2 ? '已支付':'未支付';
                        
                        if($userinfo)
                       {
                        $list[$k]['username'] = $userinfo['nickname'] . '/' . $userinfo['tel'];
                       }else{
                           
                            $list[$k]['username'] = '';
                       }
           
                        
                        $list[$k]['create_time'] = date('Y-m-d', $v['create_time']);
                        
                        if ($v['companyid'] > 0) {
                            
                            
                            $companyinfo = $companymodel->getOneCompany($v['companyid']);
                            
                            if ($companyinfo) {
                                
                                $list[$k]['username'] = '(' . $companyinfo['companyname'] . ')' . $list[$k]['username'];
                            }
                            
                            
                        }
                        
                        
                    }
                }
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
    
        
     
        
    }
