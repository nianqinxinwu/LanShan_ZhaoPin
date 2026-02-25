<?php

namespace app\admin\model\zpwxsys;
use think\Model;
use think\Db;

class Task extends  Model
{
    protected $name = 'zpwxsys_task';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * 根据搜索条件获取文章列表信息
     * @author
     */
    public function getTaskByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->alias ('t')
            ->field('t.id AS id,j.jobtitle AS jobtitle, m.companyname AS companyname,t.title AS title,t.num AS num,t.createtime AS createtime,t.status,t.content AS content,t.money AS money')
            ->join('zpwxsys_job j', 'j.id = t.jobid')
            ->join('zpwxsys_company m', 'm.id = j.companyid')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();

    }
    
    
     public function getTaskCount($map)
    {
          return $this->alias ('t')
            ->field('t.id AS id,j.jobtitle AS jobtitle, m.companyname AS companyname,t.title AS title')
            ->join('zpwxsys_job j', 'j.id = t.jobid')
            ->join('zpwxsys_company m', 'm.id = j.companyid')
            ->where($map)
            ->count();

    }


    public static function getCount()
    {
        
        $map =[];
        $count = self::where($map)->count();
        
        return $count;
        
    }


 public function getOneTask($id)
        {
            return $this->where('id', $id)->find();
        }

   public function updateTask($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '编辑成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '编辑失败'];
            }
        }

    /**
     * [delTask 删除]
     * @author
     */
    public function delTask($id)
    {
        $title = $this->where('id',$id)->value('jobtitle');
        Db::startTrans();// 启动事务
        try{
            $this->where('id', $id)->delete();
            Db::commit();// 提交事务
            return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            return ['code' => 100, 'data' => '', 'msg' =>  '删除失败'];
        }
    }






}