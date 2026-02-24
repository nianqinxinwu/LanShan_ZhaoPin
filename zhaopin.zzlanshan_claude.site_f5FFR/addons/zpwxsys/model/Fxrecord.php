<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Fxrecord extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_fxrecord';
        
        
        public static function getList($uid)
        {
            
            $fxrecordlist = self::where('uid', '=', $uid)->order('createtime ASC')->select();
            
            
            return $fxrecordlist;
            
        }
        
        
        public function insert($param)
        {
            
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->saveAll($param);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1);
            }
            
            
            return json_encode($data);
            
            
        }
        
        
    }
