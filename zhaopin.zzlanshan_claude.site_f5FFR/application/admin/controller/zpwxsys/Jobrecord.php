<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    
    use app\admin\model\zpwxsys\Jobrecord as JobrecordModel;

    
    /**
     *
     *
     * @icon fa fa-circle-o
     */
    class Jobrecord extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Jobrecord;
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
                    $od = "r.createtime desc";
                }
                
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $filter = $this->request->get("filter", '');
                $filter = (array)json_decode($filter, true);
                
                if(count($filter)>0)
                {
                
                
                if (array_key_exists("companyname",$filter))
                    {
                            
                        $map['c.companyname'] = ['like',"%" . $filter['companyname'] . "%"];
                    }
    
                if (array_key_exists("jobtitle",$filter))
                    {
                            
                        $map['j.jobtitle'] = ['like',"%" . $filter['jobtitle'] . "%"];
                    }
                
                if (array_key_exists("name",$filter))
                    {
                            
                        $map['n.name'] = ['like',"%" . $filter['name'] . "%"];
                    }
                    
                if (array_key_exists("tel",$filter))
                    {
                            
                        $map['n.tel'] = ['like',"%" . $filter['tel'] . "%"];
                    }
                    
                    
                
                
                }
                
                
                $jobrecord = new JobrecordModel();
                $count = $jobrecord->getListCount($map);
                $Nowpage = $offset / $limit + 1;
                $list = $jobrecord->getListByWhere($map, $Nowpage, $limit, $od);
                    $status_arr = array(0=>'已提交',1=>'企业同意面试',-1=>'拒绝面试',2=>'面试成功',-2=>'面试失败',3=>'录用成功',-3=>'录用失败',4=>'试用成功',-4=>'试用失败',5=>'已完成',-5=>'订单失败',6=>'已评价');
                
                if($list)
                {
                    foreach ($list as $k=>$v)
                    {
                       
                         
                         $list[$k]['status_str'] = $status_arr[$v['status']];
                    }
                }
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
        
    }
