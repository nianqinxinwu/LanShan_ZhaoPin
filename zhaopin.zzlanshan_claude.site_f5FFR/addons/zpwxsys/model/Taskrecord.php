<?php
    
    namespace addons\zpwxsys\model;
    

    use think\Db;
    
    class Taskrecord extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_taskrecord';
        
        
         public function getTaskCount($map)
       {
           
           return $this->where($map)->count();
       }
        
        public function getTaskRecordByWhere($map, $Nowpage, $limits, $od)
        {
            $taskrecordlist = $this->alias('r')->field('r.id AS id,j.jobtitle AS jobtitle,t.title AS title,t.money AS money,t.num AS num,r.createtime AS createtime,r.status AS status,c.companyname AS companyname ,r.taskid AS taskid')->join('zpwxsys_task t', 't.id = r.taskid')->join('zpwxsys_job j', 'j.id = t.jobid')->join('zpwxsys_company c', 'c.id = t.companyid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($taskrecordlist) {
                foreach ($taskrecordlist as $k => $v) {
                    
                    $taskrecordlist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                }
                
            }
            
            
            return $taskrecordlist;
            
        }
        
        
        public static function getTaskrecord($map)
        {
            
            $taskdetail = self::where($map)->find();
            
            
            return $taskdetail;
            
        }
        
        
        public function getTaskDetail($map)
        {
            
            //  $taskdetail = self::where($map)->find();
            
            $taskdetail = self::with(['jobinfo', 'companyinfo'])->where($map)->find();
            $taskdetail['totalmoney'] = $taskdetail['money'] * $taskdetail['num'];
            $data['from'] = 1;
            $taskdetail['companyinfo']['thumb'] = self::prefixImgUrl($taskdetail['companyinfo']['thumb'], $data);
            
            
            return $taskdetail;
            
        }
        
        
        public function jobinfo()
        {
            
            return $this->hasOne('Job', 'id', 'jobid');
        }
        
        
        public function companyinfo()
        {
            
            return $this->hasOne('Company', 'id', 'companyid');
        }
        
        
        public function insertTaskrecord($param)
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
