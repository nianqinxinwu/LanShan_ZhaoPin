<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Baoming extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_baoming';
        
        public static function getAgentByuid($map)
        {
            
            $agentinfo = self::where($map)->find();
            
            
            return $agentinfo;
            
            
        }
        
        
        public function insertBaoming($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '报名成功!');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '提交失败');
            }
            
            
            return json_encode($data);
            
        }
        
        
    }
