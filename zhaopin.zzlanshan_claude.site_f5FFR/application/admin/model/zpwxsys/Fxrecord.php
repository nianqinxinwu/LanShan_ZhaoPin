<?php

 namespace app\admin\model\zpwxsys;
 use think\Model;
 use think\Db;

class Fxrecord extends  Model
{
    protected $name = 'zpwxsys_fxrecord';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    
     public function getFxrecordCount($map)
    {
        return $this->alias ('a')
            ->field('a.id AS id')
            ->join('zpwxsys_agent g', 'g.uid = a.uid')
            ->where($map)
            ->count();

    }
    
    

   
    public function getFxrecordByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->alias ('a')
            ->field('a.id AS id,g.name AS agentname ,a.money AS money ')
            ->join('zpwxsys_agent g', 'g.uid = a.uid')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();

    }
    
  

}