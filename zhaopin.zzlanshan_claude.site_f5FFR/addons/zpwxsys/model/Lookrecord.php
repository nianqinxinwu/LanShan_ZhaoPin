<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Lookrecord extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_lookrecord';
        
        
        public static function getOne($map)
        {
            
            $info = self::where($map)->find();
            
            
            return $info;
            
        }
        
        
        public function getLookRecordByWhere($map, $Nowpage, $limits, $od)
        {
            $taskrecordlist = $this->alias('r')->field('r.id AS id,j.jobtitle AS jobtitle,t.title AS title,t.money AS money,t.num AS num,r.createtime AS createtime,r.status AS status,c.companyname AS companyname ')->join('task t', 't.id = r.taskid')->join('job j', 'j.id = t.jobid')->join('company c', 'c.id = t.companyid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($taskrecordlist) {
                foreach ($taskrecordlist as $k => $v) {
                    
                    $taskrecordlist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                }
                
            }
            
            
            return $taskrecordlist;
            
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
