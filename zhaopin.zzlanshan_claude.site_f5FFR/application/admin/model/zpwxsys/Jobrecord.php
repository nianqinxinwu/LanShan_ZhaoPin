<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Jobrecord extends Model
    {
        protected $name = 'zpwxsys_jobrecord';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getListByWhere($map, $Nowpage, $limits, $od)
        {
            
            return $this->alias('r')->field('r.taskid AS taskid,r.id AS id, c.companyname AS companyname,r.createtime AS createtime,j.jobtitle AS jobtitle,n.name AS name ,n.tel AS tel,r.status AS status ')->join('zpwxsys_note n', 'n.uid = r.uid')->join('zpwxsys_job j', 'j.id = r.jobid')->join('zpwxsys_company c', 'c.id = r.companyid')
                ->join('zpwxsys_task t', 't.id = r.taskid','left')
                ->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
          public function getListSendByWhere($map, $Nowpage, $limits, $od)
        {
            
            return $this->alias('r')->field(' r.taskid AS taskid,r.id AS id, c.companyname AS companyname,c.createtime AS createtime,j.jobtitle AS jobtitle,n.name AS name ,n.tel AS tel,r.status AS status ,t.title AS tasktitle,a.name AS agentname,t.money AS money')->join('zpwxsys_note n', 'n.uid = r.uid')->join('zpwxsys_job j', 'j.id = r.jobid')->join('zpwxsys_company c', 'c.id = r.companyid')
                ->join('zpwxsys_task t', 't.id = r.taskid')
                ->join('zpwxsys_agent a', 'a.uid = r.agentuid')
                ->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
          public function getOne($map)
        {
            
            return $this->alias('r')->field('r.taskid AS taskid,r.id AS id, c.companyname AS companyname,r.createtime AS createtime,j.jobtitle AS jobtitle,n.name AS name ,n.tel AS tel,r.status AS status ,t.title AS tasktitle,a.name AS agentname,t.money AS money,r.agentuid AS agentuid')->join('zpwxsys_note n', 'n.uid = r.uid')->join('zpwxsys_job j', 'j.id = r.jobid')->join('zpwxsys_company c', 'c.id = r.companyid')
                ->join('zpwxsys_task t', 't.id = r.taskid')
                ->join('zpwxsys_agent a', 'a.uid = r.agentuid')
                ->where($map)->find();
            
        }
        
        
          public function getListSendCount($map)
        {
            
            return $this->alias('r')->field('r.taskid AS taskid,r.id AS id, c.companyname AS companyname,r.createtime AS createtime,j.jobtitle AS jobtitle,n.name AS name ,n.tel AS tel,r.status AS status ,t.title AS tasktitle,a.name AS agentname')->join('zpwxsys_note n', 'n.uid = r.uid')->join('zpwxsys_job j', 'j.id = r.jobid')->join('zpwxsys_company c', 'c.id = r.companyid')
                ->join('zpwxsys_task t', 't.id = r.taskid')
                ->join('zpwxsys_agent a', 'a.uid = r.agentuid')
                ->where($map)
                ->count();
            
        }
        
        
        public function getListCount($map)
        {
            
            return $this->alias('r')->field('r.id AS id, c.companyname AS companyname,r.createtime AS createtime,j.jobtitle AS jobtitle,n.name AS name ,n.tel AS tel,r.status AS status ')->join('zpwxsys_note n', 'n.uid = r.uid')->join('zpwxsys_job j', 'j.id = r.jobid')->join('zpwxsys_company c', 'c.id = r.companyid')->where($map)->count();
            
        }
        
        
         public function updateJobrecord($param){
        Db::startTrans();// 启动事务
        try{
             
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            $data = array('status'=>0);
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            $data = array('status'=>1);
        }
        
         return json_encode($data);
        
        
    }
        
        
        /**
         * [delJobrecord 删除]
         * @author
         */
        public function delJobrecord($id)
        {
            $title = $this->where('id', $id)->value('id');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '删除失败'];
            }
        }
        
        
    }