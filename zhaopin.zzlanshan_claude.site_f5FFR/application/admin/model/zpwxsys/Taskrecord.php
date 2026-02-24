<?php

namespace app\admin\model\zpwxsys;
use think\Model;
use think\Db;

class Taskrecord extends  Model
{
    protected $name = 'zpwxsys_taskrecord';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * 根据搜索条件获取文章列表信息
     * @author
     */
    public function getTaskrecordByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->alias ('r')
            ->field('r.id AS id,j.jobtitle AS jobtitle, m.companyname AS companyname,t.title AS title,t.num AS num,r.createtime AS r,t.status,t.content AS content,t.money AS money, a.name AS name')
            ->join('zpwxsys_task t', 't.id = r.taskid')
            ->join('zpwxsys_agent a', 'a.uid = r.uid')
            ->join('zpwxsys_job j', 'j.id = t.jobid')
            ->join('zpwxsys_company m', 'm.id = j.companyid')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();

    }
    
    
     public function getTaskrecordCount($map)
    {
           return $this->alias ('r')
            ->field('r.id AS id,j.jobtitle AS jobtitle, m.companyname AS companyname,t.title AS title,t.num AS num,t.createtime AS createtime,t.status,t.content AS content,t.money AS money, a.name AS name')
            ->join('zpwxsys_task t', 't.id = r.taskid')
            ->join('zpwxsys_agent a', 'a.uid = r.uid')
            ->join('zpwxsys_job j', 'j.id = t.jobid')
            ->join('zpwxsys_company m', 'm.id = j.companyid')
            ->where($map)
            ->count();

    }
    
    
}