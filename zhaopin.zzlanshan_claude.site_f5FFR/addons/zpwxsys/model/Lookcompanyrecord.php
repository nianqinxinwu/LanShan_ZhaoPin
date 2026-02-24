<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Lookcompanyrecord extends BaseModel
    {
        
        protected $name = 'zpwxsys_lookcompanyrecord';
        
        
        public static function getOne($map)
        {
            
            $info = self::where($map)->find();
            
            
            return $info;
            
        }
        
        
        public function insertLookrecord($param)
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
