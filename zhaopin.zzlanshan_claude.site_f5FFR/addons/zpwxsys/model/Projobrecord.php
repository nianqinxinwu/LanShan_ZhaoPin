<?php

namespace addons\zpwxsys\model;
    
use think\Db;

class Projobrecord extends BaseModel
{
    
    protected $name = 'zpwxsys_projobrecord';

    public function insertProjobrecord($param){


        Db::startTrans();// 启动事务
        try{


             $this->allowField(true)->save($param);
            $id = DB::getLastInsID();
            Db::commit();// 提交事务
            $data = array('status'=>0,'id'=>$id);
            
            
            
        }catch( \Exception $e){
            Db::rollback();// 回滚事务

            $data = array('status'=>1);
        }


        return json_encode($data);



    }
    
    
    
      public static function getlist($map)
   {
       
         
       
       
       return self::where($map)->order('createtime DESC')->select();
       
       
       
       
   
       
   }
    
    

}