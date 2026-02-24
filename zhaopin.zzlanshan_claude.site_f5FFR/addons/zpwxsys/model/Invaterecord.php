<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Invaterecord extends BaseModel
    {
        protected $name = 'zpwxsys_invaterecord';
        
        public static function getSendinvatejobWhere($map)
        {
            
            $jobrecord = self::where($map)->find();
            
            
            return $jobrecord;
            
        }
        
        
        public function sendinvatejob($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '邀请成功');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '邀请失败');
            }
            
            
            return json_encode($data);
            
        }
        
        
    }
