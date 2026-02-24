<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Agent extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_agent';
        
        
        public static function getAgentByuid($map)
        {
            
            $agentinfo = self::where($map)->find();
        
            if ($agentinfo)
                
                $agentinfo['qrcode'] = cdnurl($agentinfo['qrcode'], true);
            
            return $agentinfo;
            
            
        }
        
        
        public function insertAgent($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '提交成功,我们会尽快审核!');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '提交失败');
            }
            
            
            return json_encode($data);
            
        }
        
        
    }
