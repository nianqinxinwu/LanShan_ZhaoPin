<?php

namespace app\admin\model\zpwxsys;
    
use think\Model; 
use think\Db;

class Money extends  Model
{
	 protected $name = 'zpwxsys_money';
    
    // 开启自动写入时间戳字段
     protected $autoWriteTimestamp = true;

       /**
     * 根据搜索条件获取文章列表信息
     * @author
     */
    public function getListByWhere($map, $Nowpage, $limits,$od)
    {
                return $this->alias ('m')
            ->field('m.uid AS uid,m.cityid AS cityid,m.id AS id, m.money AS money,m.totalmoney AS totalmoney,m.createtime AS createtime,u.nickname AS nickname,m.content AS content,m.status AS status,m.type AS type,m.paytype AS paytype,u.tel AS tel')
            ->join('zpwxsys_user u', 'u.id = m.uid','left')
              ->join('zpwxsys_city c', 'c.id = m.cityid','left')
            ->join('zpwxsys_order o', 'o.id = m.orderid','left')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();

    }


    

    
    
       public function getListCount($map)
    {
                return $this->alias ('m')
            ->field('m.id AS id, m.money AS money,m.totalmoney AS totalmoney,m.createtime AS createtime ')
            ->join('zpwxsys_user u', 'u.id = m.uid','left')
            ->join('zpwxsys_city c', 'c.id = m.cityid','left')
            ->join('zpwxsys_order o', 'o.id = m.orderid')
            ->where($map)
            ->count();

    }
    
    
      public static function getlist($map)
     {
       
       
       return self::where($map)->order('createtime DESC')->select();
       
       
     }
     
     public static function getOne($map)
     {
         
         return self::where($map)->find();
     }
   
  
    public function insertMoney($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param);
            Db::commit();// 提交事务
            return ['code' => 200, 'data' => '', 'msg' => '添加成功'];
        }catch( \Exception $e){
            
            Db::rollback();// 回滚事务
            return ['code' => 100, 'data' => '', 'msg' =>'添加失败'];
        }
    }
    
     public function updateMoney($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            //  writelog(session('uid'),session('username'),'文章【'.$param['title'].'】编辑成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '操作成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            return ['code' => 100, 'data' => '', 'msg' => '操作失败'];
        }
    }
    
    
    public static function getLastOne($map)
    {
        
        return self::where($map)->order('createtime desc')->select();
        
    }
    
    
    
}