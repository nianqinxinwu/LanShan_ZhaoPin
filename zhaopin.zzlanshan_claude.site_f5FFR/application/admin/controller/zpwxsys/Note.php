<?php
    
    namespace app\admin\controller\zpwxsys;
    
    use app\common\controller\Backend;
    use app\admin\model\zpwxsys\Note as NoteModel;
    use app\admin\model\zpwxsys\Area as AreaModel;
    use app\admin\model\zpwxsys\City as CityModel;
    use app\admin\model\zpwxsys\Current as CurrentModel;
    use app\admin\model\zpwxsys\Worktype as WorktypeModel;
    use app\admin\model\zpwxsys\Jobprice as JobpriceModel;
    use app\admin\model\zpwxsys\Jobcate as JobcateModel;
    use app\admin\model\zpwxsys\Edu as EduModel;
    use app\admin\model\zpwxsys\Express as ExpressModel;


    /**
     *
     *
     * 简历管理
     */
    class Note extends Backend
    {
        
        
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $multiFields = ['enabled', 'sort', 'status','ishidden'];
        
        public function _initialize()
        {
            parent::_initialize();
            $this->model = new \app\admin\model\zpwxsys\Note;
            // $this->view->assign("statusList", $this->model->getStatusList());
        }
        
        
        public function getarealist()
        {
            
            if ($this->request->isAjax()) {
                $AreaModel = new AreaModel();
                
                $cityid = input('post.cityid');//字段
                
                $map['cityid'] = $cityid;
                
                $arealist = $AreaModel->getAreaListBycityId($map);
                
                
                $this->success('', '', $arealist);
                
            }
            
            
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
                    $od = "n.createtime desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                
                $filter = $this->request->get("filter", '');
                $filter = (array)json_decode($filter, true);
                
                if(count($filter)>0)
                {
                
                
                if (array_key_exists("cityname",$filter))
                    {
                            
                        $map['c.name'] = ['like',"%" . $filter['cityname'] . "%"];
                    }
                    
                 if (array_key_exists("areaname",$filter))
                    {
                            
                        $map['a.name'] = ['like',"%" . $filter['areaname'] . "%"];
                    }
    
                if (array_key_exists("name",$filter))
                    {
                            
                        $map['n.name'] = ['like',"%" . $filter['name'] . "%"];
                    }
                
                   if (array_key_exists("tel",$filter))
                    {
                            
                        $map['n.tel'] = ['like',"%" . $filter['tel'] . "%"];
                    }
                    
                     if (array_key_exists("jobtitle",$filter))
                    {
                            
                        $map['n.jobtitle'] = ['like',"%" . $filter['jobtitle'] . "%"];
                    }
                
                }
                
                
                $note = new NoteModel();
                $count = $note->getNoteCount($map);
                $Nowpage = $offset / $limit + 1;
                $list = $note->getNoteByWhere($map, $Nowpage, $limit, $od);
                
                
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
                    $note = new NoteModel();
                    
                    //  $params['worktypeid'] = $params['type'];
                    
                    $moneyinfo = JobpriceModel::getOne(array('id' => $params['jobpriceid']));
                    
                    $params['money'] = $moneyinfo['name'];
                    
                    $params['createtime'] = $params['refreshtime'] = time();
                    
                    $params['uid'] = $params['createtime'];
                     
                     
                    $result = $note->insertNote($params);
                    
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were inserted'));
                    }
                }
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $maps['enabled'] = 1;
            $od = 'sort desc';
            
            $currentlist = CurrentModel::getList($maps, $od);
            
            $worktypelist = WorktypeModel::getList($maps, $od);
            
            
            $mapc = [];
            $odc = 'sort  desc';
            
            $jobcate = new JobcateModel();
            
            $jobprice = new JobpriceModel();
            
            $jobcatelist = $jobcate->getAllJobcate($mapc, $odc);
            $jobpricelist = $jobprice->getJobprice();
            
            $city = new CityModel();
            
            $citylist = $city->getCity();
            
            $this->view->assign("citylist", $citylist);
            
            
            $this->view->assign("currentlist", $currentlist);
            $this->view->assign("worktypelist", $worktypelist);
            $this->view->assign("jobcatelist", $jobcatelist);
            $this->view->assign("jobpricelist", $jobpricelist);
            
            $birthdaylist = array('1960', '1961', '1962', '1963', '1964', '1965', '1966', '1967', '1968', '1969', '1970', '1971', '1972', '1973', '1974', '1975', '1976', '1977', '1978', '1979', '1980', '1981', '1982', '1983', '1984', '1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004');
            
            $this->view->assign("birthdaylist", $birthdaylist);
            return $this->view->fetch();
        }
        
        /**
         * 编辑
         */
        public function edit($ids = null)
        {
            
            
            $note = new NoteModel();
            
            if ($this->request->isPost()) {
                $params = $this->request->post("row/a");
                if ($params) {
                    
                    $params = $this->preExcludeFields($params);
                    $result = false;
                    
                    
                    $moneyinfo = JobpriceModel::getOne(array('id' => $params['jobpriceid']));
                    
                    $params['money'] = $moneyinfo['name'];
                   $params['refreshtime'] = time();
                    
                    $result = $note->updateNote($params);
                    
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                    
                    
                }
                
                
                $this->error(__('Parameter %s can not be empty', ''));
            }
            
            
            $row = $note->getOneNote($ids);
            
            
            if (!$row) {
                $this->error(__('No Results were found'));
            }
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                if (!in_array($row[$this->dataLimitField], $adminIds)) {
                    $this->error(__('You have no permission'));
                }
            }
            
            
            $maps['enabled'] = 1;
            $od = 'sort desc';
            
            $currentlist = CurrentModel::getList($maps, $od);
            
            $worktypelist = WorktypeModel::getList($maps, $od);
            
            
            $mapc = [];
            $odc = 'sort  desc';
            
            $jobcate = new JobcateModel();
            
            $jobprice = new JobpriceModel();
            
            $jobcatelist = $jobcate->getAllJobcate($mapc, $odc);
            $jobpricelist = $jobprice->getJobprice();
            
            $city = new CityModel();
            
            $citylist = $city->getCity();
            
            $area = new AreaModel();
            
            $params['cityid'] = $row['cityid'];
            
            $arealist = $area->getAreaListBycityId($params);
            
            $this->view->assign("citylist", $citylist);
            $this->view->assign("arealist", $arealist);
            
            
            $this->view->assign("currentlist", $currentlist);
            $this->view->assign("worktypelist", $worktypelist);
            $this->view->assign("jobcatelist", $jobcatelist);
            $this->view->assign("jobpricelist", $jobpricelist);
            
            $birthdaylist = array('1960', '1961', '1962', '1963', '1964', '1965', '1966', '1967', '1968', '1969', '1970', '1971', '1972', '1973', '1974', '1975', '1976', '1977', '1978', '1979', '1980', '1981', '1982', '1983', '1984', '1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004');
            
            $this->view->assign("birthdaylist", $birthdaylist);
            
            
            $this->view->assign("row", $row);
            return $this->view->fetch();
        }
        
        
        
        public function delEdunote($ids = null)
        {
            if ($this->request->isPost()) {
                
                    $id = $ids;
                    
                    $eduModel = new EduModel();
                    
                    $res = $eduModel->delEdu($ids);
                    
                    
                 if ($res['code']== 200) {
                            
                     
                            
                            $this->success();
                            
                        }else{
                            
                             $this->error(__('操作失败'));
                        }
                    
                    
                    
            }
        }
        
        
         public function delExpressnote($ids = null)
        {
            if ($this->request->isPost()) {
                
                    $id = $ids;
                    
                    $expressModel = new ExpressModel();
                    
                    $res = $expressModel->delExpress($ids);
                    
                    
                 if ($res['code']== 200) {
                            
                     
                            
                            $this->success();
                            
                        }else{
                            
                             $this->error(__('操作失败'));
                        }
                    
                    
                    
            }
        }
        
        
           public function  lookedunote($noteid = null)
        {
            
              //当前是否为关联查询
            $this->relationSearch = true;
            //设置过滤方法
            $this->request->filter(['strip_tags', 'trim']);
            
            
            if ($this->request->isAjax()) {
                
                $noteid = input('get.noteid');
                
                
                $umap = [];
                
                $umap['id'] = $noteid;
                $noteinfo = NoteModel::getOne($umap);
                
      
                //如果发送的来源是Selectpage，则转发到Selectpage
                if ($this->request->request('keyField')) {
                    return $this->selectpage();
                }
                
                $map = [];
                
                $map['uid'] = $noteinfo['uid'];
                
                $field = input('field');//字段
                $order = input('order');//排序方式
                if ($field && $order) {
                    $od = $field . " " . $order;
                } else {
                    $od = "id desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                

                $eduModel = new EduModel();
                $count = $eduModel->getEduCount($map);
                $Nowpage = $offset / $limit + 1;
                $list = $eduModel->getEduByWhere($map, $Nowpage, $limit, $od);
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            
         $this->view->assign('noteid',$noteid);
            return $this->view->fetch();
            
            
            
            
        }
        
        
        
             public function  lookexpressnote($noteid = null)
        {
            
          
              //当前是否为关联查询
            $this->relationSearch = true;
            //设置过滤方法
            $this->request->filter(['strip_tags', 'trim']);
            
            
            if ($this->request->isAjax()) {
                
                $noteid = input('get.noteid');
                
                
                $umap = [];
                
                $umap['id'] = $noteid;
                $noteinfo = NoteModel::getOne($umap);
                
      
                //如果发送的来源是Selectpage，则转发到Selectpage
                if ($this->request->request('keyField')) {
                    return $this->selectpage();
                }
                
                $map = [];
                
                $map['uid'] = $noteinfo['uid'];
                
                $field = input('field');//字段
                $order = input('order');//排序方式
                if ($field && $order) {
                    $od = $field . " " . $order;
                } else {
                    $od = "id desc";
                }
                list($where, $sort, $order, $offset, $limit) = $this->buildparams();
                

                $expressModel = new ExpressModel();
                $count = $expressModel->getExpressCount($map);
                $Nowpage = $offset / $limit + 1;
                $list = $expressModel->getExpressByWhere($map, $Nowpage, $limit, $od);
                
                
                $result = array("total" => $count, "rows" => $list);
                
                return json($result);
            }
            
         $this->view->assign('noteid',$noteid);
            return $this->view->fetch();
            
            
            
            
        }
        
        
        
           public function  addedunote($noteid = null)
        {
            
            if ($this->request->isPost()) {
                
                $params = $this->request->post("row/a");
                
                $EduModel = new EduModel();
                
               $res =  $EduModel->insertEdu($params);
               
                
            
                
                 if ($res['code']== 200) {
                            
                     
                            
                            $this->success();
                            
                        }else{
                            
                             $this->error(__('操作失败'));
                        }
                
            
            }
            
            
            $map = [];
            
            $map['id'] = $noteid;
            $noteinfo = NoteModel::getOne($map);
            
            $this->view->assign('noteid',$noteinfo['uid']);
        
            
            return $this->view->fetch();
            
            
            
            
        }
        
        
           public function  addexpressnote($noteid = null)
        {
            
            if ($this->request->isPost()) {
                
                $params = $this->request->post("row/a");
                
                $ExpressModel = new ExpressModel();
                
                $res =  $ExpressModel->insertExpress($params);
                
                if ($res['code']== 200) {
                            
                     
                            
                            $this->success();
                            
                        }else{
                            
                             $this->error(__('操作失败'));
                        }
                
            
            }
            
            
            $map = [];
            
            $map['id'] = $noteid;
            $noteinfo = NoteModel::getOne($map);
            
            $this->view->assign('noteid',$noteinfo['uid']);
        
            
            return $this->view->fetch();
            
            
            
            
        }
        
        
        
        
    }
