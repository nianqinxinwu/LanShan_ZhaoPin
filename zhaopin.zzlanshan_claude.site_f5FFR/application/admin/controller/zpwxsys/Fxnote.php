<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Note as NoteModel;
    /**
     *
     *
     * 推荐人才列表
     */
    class Fxnote extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','ischeck'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Note;
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
                
                $map['n.tid'] = array('gt',0);
                
                $field = input('field');//字段
                $order = input('order');//排序方式
                if ($field && $order) {
                    $od = $field . " " . $order;
                } else {
                    $od = "n.createtime desc";
                    
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
                
        
                $note = new NoteModel();
                $count = $note->getNoteCount($map);
                $Nowpage = $offset / $limit + 1;
           
                $list = $note->getNoteByWhere($map, $Nowpage, $limit, $od);
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
        
    
 
        
    }
