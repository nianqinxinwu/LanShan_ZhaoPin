<?php
    
    namespace addons\zpwxsys\model;
    

    use think\Db;
    
    class Task extends BaseModel
    {
        
        protected $name = 'zpwxsys_task';
        
        public function getTaskByWhere($map, $Nowpage, $limits, $od)
        {
            $tasklist = $this->alias('t')->field('t.id AS id,j.jobtitle AS jobtitle,t.title AS title,t.money AS money,t.num AS num,t.createtime AS createtime,t.status AS status,c.companyname AS companyname ')->join('zpwxsys_job j', 'j.id = t.jobid')->join('zpwxsys_company c', 'c.id = t.companyid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($tasklist) {
                foreach ($tasklist as $k => $v) {
                    
                    $tasklist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                }
                
            }
            
            
            return $tasklist;
            
        }
        
        
        public static function getTask($map)
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
        
        
        public function insertTask($param)
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
        
        
        public function updateTask($param)
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
        
        
    }
