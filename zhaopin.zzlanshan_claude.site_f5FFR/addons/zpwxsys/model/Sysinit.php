<?php
    
    namespace addons\zpwxsys\model;
    

    
    use think\Db;
    
    class Sysinit extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_sysinit';
        

        public static function getSysinfo()
        {
            
            
            $sysinfo = self::where('id', '>', 0)->find();
            
            $data['from'] = 1;
            // $sysinfo['logo'] = self::prefixImgUrl($sysinfo['logo'],$data);
            
            
            return $sysinfo;
        }
        
        public function updateSysView($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '更新成功'];
                
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '更新失败'];
                
            }
            
        }
        
    }
