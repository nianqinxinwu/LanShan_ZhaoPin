<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    
    use app\admin\model\zpwxsys\Jobrecord as JobrecordModel;
    use app\admin\model\zpwxsys\Money as MoneyModel;

    
    /**
     *
     *
     * @icon fa fa-circle-o
     */
    class Sendnote extends Backend
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
                    $od = "r.id desc";
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
                
                $map['r.taskid'] = ['gt',0];
                
                $jobrecord = new JobrecordModel();
                $count = $jobrecord->getListSendCount($map);
                $Nowpage = $offset / $limit + 1;
                $list = $jobrecord->getListSendByWhere($map, $Nowpage, $limit, $od);
                
                if($list)
                {
                   //  $status_arr = array(0=>'新提交',1=>'面试成功',-1=>'面试失败',2=>'录用成功',-2=>'录用失败',3=>'试用成功',-3=>'试用失败',4=>'已完成',-4=>'订单失败');
                   
                   
                   $status_arr = array(0=>'新提交',1=>'同意面试',-1=>'拒绝面试',2=>'面试成功',-2=>'面试失败',3=>'录用成功',-3=>'录用失败',4=>'试用成功',-4=>'试用失败',5=>'已完成',-5=>'订单失败',6=>'已完成/已分佣',-6=>'分佣失败');
                     
                    foreach ($list as $k=>$v)
                    {
                       $list[$k]['status'] = $status_arr[$v['status']];
                    }
                }
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            return $this->view->fetch();
        }
        
        
             public function  dealmoney($id = null)
        {
            
            if ($this->request->isPost()) {
                
                $params = $this->request->post("row/a");
                
                $id = $params['id'];
              
                
         
                $JobrecordModel = new JobrecordModel();
                
                
                $jobrecordinfo = $JobrecordModel->getOne(['r.id'=>$id]);
                //分配经纪人
              $res=  $this->dealMoneyRecord($jobrecordinfo, $jobrecordinfo['money'],$jobrecordinfo['agentuid']);
                //分配给合作社
              //  $this->dealMoneyRecord($jobrecordinfo, $jobrecordinfo['hzmoney'],-2);
                //分配给平台
               // $res = $this->dealMoneyRecord($jobrecordinfo, $jobrecordinfo['spmoney'],-1);
                
                
                
                 if ($res['code']== 200) {
                $status_money = [];
                $status_money['id'] =$id;
                $status_money['status'] = 6; 
                $JobrecordModel->updateJobrecord($status_money);
                            $this->success();
                            
                        }else{
                            
                               $status_money = [];
                            $status_money['id'] =$id;
                            $status_money['status'] = -6; 
                             $this->error(__('操作失败'));
                        }
                
            
            }
            
            $JobrecordModel = new JobrecordModel();
            $jobrecordinfo = $JobrecordModel->getOne(['r.id'=>$id]);
            
            if($jobrecordinfo['status'] == 6 || $jobrecordinfo['status'] == -6)
            {
                 $this->error(__('分配佣金操作失败'));
                
            }
            $this->view->assign('jobrecordinfo',$jobrecordinfo);
            $this->view->assign('id',$id);
        
            
            return $this->view->fetch();
            
            
            
            
        }
        
        
          public function dealMoneyRecord($orderinfo,$money,$uid)
  
  {
      
      
          
          
             //  $uid  = $orderinfo['agentuid'];
       
             //  $money = $orderinfo['money'] * $rate;
               
              $lastmoney =  MoneyModel::getLastOne(array('uid'=>$uid));//最新的余额 
               
               
               if($lastmoney)
               {
                   
                  $totalmoney = $lastmoney[0]['totalmoney'];
                  
                   
               }else{
                   
                  $totalmoney = 0;
                  
               }
               

               $totalmoney = $totalmoney + $money;
               
               $param_money['money'] = $money;
               
               $param_money['totalmoney'] = $totalmoney;
               
               $param_money['uid'] = $uid;
               

               $param_money['orderid'] = $orderinfo['id'];
               
               $param_money['taskid'] = $orderinfo['taskid'];
               $param_money['content'] = '推荐【'.$orderinfo['tasktitle'].'】获得佣金';
               
               $param_money['createtime'] = time();
               
               
               $moneyModel = new MoneyModel();
               
             $res =   $moneyModel->insertMoney($param_money);
               
              return $res;
  }
    
        
    }
