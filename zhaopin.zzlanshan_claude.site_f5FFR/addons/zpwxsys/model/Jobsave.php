<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Jobsave extends BaseModel
    {
        
        protected $name = 'zpwxsys_jobsave';
        
        public static function getJobsaveWhere($map)
        {
            
            $jobsave = self::where($map)->find();
            
            
            return $jobsave;
            
        }
        
        public function delJobsave($map)
        {
            
            
            Db::startTrans();// 启动事务
            try {
                $this->where($map)->delete();
                Db::commit();// 提交事务
                return true;
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return false;
            }
            
            
        }
        
        
        public function jobSave($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '收藏成功');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '收藏失败');
            }
            
            
            return json_encode($data);
            
        }
        
        public function getMySaveList($map,$Nowpage, $limits, $od)
        {
            $jobsavelist = $this->alias('r')
                ->field('r.id AS id,j.id AS jobid,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney,j.status AS status, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,a.name AS areaname ')->join('zpwxsys_job j', 'j.id = r.jobid')
                ->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')
                ->join('zpwxsys_company m', 'm.id = j.companyid','left')
                ->join('zpwxsys_area a', 'a.id = m.areaid','left')
                ->where($map)
                ->order($od)
                ->select();
            
            return $jobsavelist;
        }
        
        
    }
