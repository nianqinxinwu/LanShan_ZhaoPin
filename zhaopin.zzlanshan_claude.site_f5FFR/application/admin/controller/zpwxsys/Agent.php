<?php

namespace app\admin\controller\zpwxsys;

use app\common\controller\Backend;
use app\admin\model\zpwxsys\Adv as AdvModel;
use app\admin\model\zpwxsys\City as CityModel;
use app\admin\model\zpwxsys\Agent as AgentModel;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Agent extends Backend
{


    protected $model = null;
    protected $noNeedRight = ['*'];
    protected $multiFields = ['enabled','sort','status'];
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\zpwxsys\Agent;
        // $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function import()
    {
        parent::import();
    }



    /**
     * 查看
     */


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

            $field=input('field');//字段
            $order=input('order');//排序方式
            if($field && $order){
                $od= $field." ".$order;
            }else{
                $od="createtime desc";
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $agent = new AgentModel();
            $count = $agent->getAgentCount($map);
            $Nowpage = $offset/$limit +1;
            $list = $agent->getAgentByWhere($map, $Nowpage, $limit,$od);


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
                $agent = new AgentModel();
                $result = $agent->insertAgent($params);


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

        $agent = new  AgentModel();

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {

                $params = $this->preExcludeFields($params);
                $result = false;


                $result = $agent->updateAgent($params);

                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }




            }




            $this->error(__('Parameter %s can not be empty', ''));
        }



        $row = $agent->getOneAgent($ids);


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
