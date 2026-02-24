<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Jobrecord extends BaseModel
    {
        
        protected $name = 'zpwxsys_jobrecord';
        
        public static function getJobrecordWhere($map)
        {
            
            $jobrecord = self::where($map)->find();
            
            
            return $jobrecord;
            
        }
        
        public static function getJobrecordToal($map)
        {
            
            $count = self::where($map)->count();
            
            
            return $count;
            
        }
        
        
        public function getgetMyTaskFindCount($map)
        {
            return $this->alias ('r')
                ->field('r.id AS id,r.status AS status,n.name AS name,n.sex AS sex,n.avatarUrl AS avatarUrl,j.id AS jobid,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,t.agentmoney AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,a.name AS areaname ,n.id AS noteid,r.taskid AS taskid,t.title AS tasktitle')
                ->join('zpwxsys_job j', 'j.id = r.jobid')
                ->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')
                ->join('zpwxsys_note n', 'n.uid = r.uid','left')
                ->join('zpwxsys_company m', 'm.id = j.companyid','left')
                ->join('zpwxsys_area a', 'a.id = m.areaid','left')
                ->join('zpwxsys_task t', 't.id = r.taskid','left')
                ->where($map)
                ->count();
            
        }
        
        
        public  function getMyTaskFindList($map, $Nowpage, $limits,$od)
        {
            $jobrecordlist =  $this->alias ('r')
                ->field('r.id AS id,r.status AS status,n.name AS name,n.sex AS sex,n.avatarUrl AS avatarUrl,j.id AS jobid,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,t.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,a.name AS areaname ,n.id AS noteid,r.taskid AS taskid,t.title AS tasktitle,n.avatarUrl AS avatarUrl')
                ->join('zpwxsys_job j', 'j.id = r.jobid')
                ->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')
                ->join('zpwxsys_note n', 'n.uid = r.uid','left')
                ->join('zpwxsys_company m', 'm.id = j.companyid','left')
                ->join('zpwxsys_area a', 'a.id = m.areaid','left')
                ->join('zpwxsys_task t', 't.id = r.taskid','left')
                ->where($map)
                ->order($od)
                ->select();
            
            
            
            
            foreach ($jobrecordlist as $k =>$v)
            {
                
                if($v['avatarUrl'] == '')
                {
                    $jobrecordlist[$k]['avatarUrl'] = '../../imgs/icon/male'.$v['sex'].'.png';
                    
                }else{
                    $jobrecordlist[$k]['avatarUrl'] =  cdnurl($v['avatarUrl'],true);
                    
                }
                $jobrecordlist[$k]['createtime'] = date('Y-m-d',$v['createtime']);
                
                
            }
            
            
            return  $jobrecordlist;
        }
        
        
        public function getOne($map)
        {
            
            
            $jobrecordinfo =  $this->alias ('r')
                ->field('r.companyid AS companyid,r.id AS id,r.status AS status,n.name AS name,n.sex AS sex,n.avatarUrl AS avatarUrl,j.id AS jobid,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,r.createtime AS createtime, m.companyname AS companyname ,n.id AS noteid,n.express AS express ,n.education AS education,n.tel AS tel,m.thumb AS thumb,m.companyworker AS companyworker,m.companytype AS companytype, m.companycate AS companycate ,n.uid AS uid, c.name AS jobcatename')
                ->join('zpwxsys_job j', 'j.id = r.jobid','left')
                ->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')
                ->join('zpwxsys_note n', 'n.uid = r.uid','left')
                ->join('zpwxsys_company m', 'm.id = r.companyid','left')
                ->where($map)
                ->find();
            
            
            
            
            
            
            
            
            
            if($jobrecordinfo['avatarUrl'] == '')
            {
                $jobrecordinfo['avatarUrl'] = '../../imgs/icon/male'.$jobrecordinfo['sex'].'.png';
                
            }else{
                $jobrecordinfo['avatarUrl']= cdnurl($jobrecordinfo['avatarUrl'],true);
                
            }
            $jobrecordinfo['createtime'] = date('Y-m-d',$jobrecordinfo['createtime']);
            
            
            return  $jobrecordinfo;
            
            
            
        }
        
        
        public function getMyFindList($map, $Nowpage, $limits, $od)
        {
            $jobrecordlist = $this->alias('r')->field('r.agentuid AS agentuid,r.id AS id,r.uid AS uid,r.status AS status,r.signed_in AS signed_in,r.signin_time AS signin_time,r.signout_time AS signout_time,n.name AS name,n.sex AS sex,n.avatarUrl AS avatarUrl,j.id AS jobid,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney, c.name AS jobcatename,j.num AS num,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname, m.thumb AS logo ,a.name AS areaname ,n.id AS noteid,n.age AS note_age,n.education AS note_education,n.birthday AS birthday,j.work_start_date AS work_start_date,j.work_end_date AS work_end_date,j.work_start_time AS work_start_time,j.work_end_time AS work_end_time,j.hourly_rate AS hourly_rate,j.settle_type AS settle_type,j.sex AS job_sex,j.job_address AS job_address,j.signin_phase AS signin_phase')
                ->join('zpwxsys_job j', 'j.id = r.jobid')
                ->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')
                ->join('zpwxsys_note n', 'n.uid = r.uid', 'left')
                ->join('zpwxsys_company m', 'm.id = j.companyid')
                ->join('zpwxsys_area a', 'a.id = m.areaid','left')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($od)
                ->select();
            
            
            $data['from'] = 1;
            
            
            foreach ($jobrecordlist as $k => $v) {

                if (empty($v['avatarUrl'])) {
                    $jobrecordlist[$k]['avatarUrl'] = '../../imgs/icon/male' . ($v['sex'] ?: 1) . '.png';

                } else {
                    $jobrecordlist[$k]['avatarUrl'] = cdnurl($v['avatarUrl'],true);

                }
                $jobrecordlist[$k]['createtime'] = date('Y-m-d', $v['createtime'] ?: time());
                
                
            }
            
            
            return $jobrecordlist;
        }
        
        
        public function updateJobrecord($param){
            Db::startTrans();// 启动事务
            try{
                
                $this->allowField(true)->save($param, ['id' => $param['id'],'companyid'=>$param['companyid']]);
                Db::commit();// 提交事务
                $data = array('status'=>0);
            }catch( \Exception $e){
                Db::rollback();// 回滚事务
                $data = array('status'=>1);
            }
            
            return json_encode($data);
            
            
        }
        
        
        
        public function saveJobrecord($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                $flag = true;
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                //$data = array('status'=>1,'msg'=>'更新失败');
                $flag = false;
            }
            
            
            return $flag;
            
        }
        
        
        public function sendJob($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '投递成功');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '投递失败');
            }
            
            
            return json_encode($data);
            
        }
        
        
    }
