<?php
    namespace addons\zpwxsys\model;
    use think\Db;
    class Ploite extends BaseModel
    {
        protected $name = 'zpwxsys_ploite';
        
        
    
        public function insertPloite($param)
        {
        
            Db::startTrans();// 启动事务
            try {
            
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
            
                $data = array('status' => 1);
            }
        
            return json_encode($data);
        
        
        }
    
    }
