<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Companyaccount extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_companyaccount';
        
        public static function getCompanyaccount($id)
        {
            
            $companydetail = self::where('id', '=', $id)->find();
            
            
            return $companydetail;
            
        }
        
        public static function getCompanyaccountByuid($companyid)
        {
            
            $companyaccountinfo = self::where('companyid', '=', $companyid)->find();
            
            
            return $companyaccountinfo;
            
            
        }
        
        
        public function insertCompanyaccount($param)
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
        
        
        public function updateCompanyaccount($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                $data = array('status' => 1);
            }
            
            return json_encode($data);
        }
        
        
        public static function getCompanyLogin($param)
        {
            
            $companyaccount = self::where($param)->find();
            
            
            return $companyaccount;
            
        }
        
        
    }
