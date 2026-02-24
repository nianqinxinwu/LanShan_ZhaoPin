<?php
    
    namespace addons\zpwxsys\model;
    
    
    use think\Db;
    
    class Companyrecord extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_companyrecord';
        
        public static function getCompanyrecordPoP($map)
        {
            
            $companyrecordlist = self::where($map)->order('createtime DESC')->select();
            
            if($companyrecordlist)
            {
                
                foreach ($companyrecordlist as $k=>$v)
                {
                    $companyrecordlist[$k]['create_time'] = date('Y-m-d',$v['createtime']);
                }
            }
            
            
            return $companyrecordlist;
            
        }
        
        
        public function insertCompanyrecord($param)
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
