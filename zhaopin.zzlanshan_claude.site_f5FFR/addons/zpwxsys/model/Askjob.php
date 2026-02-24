<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Askjob extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_askjob';
        
        public function saveAskjob($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '提交成功');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '提交失败');
            }
            
            
            return json_encode($data);
            
        }
        
        
    }
