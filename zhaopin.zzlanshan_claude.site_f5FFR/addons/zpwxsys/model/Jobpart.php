<?php
    
    namespace addons\zpwxsys\model;
    
    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use think\Db;
    
    class Jobpart extends BaseModel
    {
        
        protected $name = 'zpwxsys_jobpart';
        
        public function getJobByWhere($map, $Nowpage, $limits, $od)
        {
            $joblist = $this->alias('j')->field('j.id AS id,j.special AS special,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney,j.status AS status, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,a.name AS areaname,j.toptime AS toptime ')->join('jobcate c', 'c.id = j.worktype')->join('company m', 'm.id = j.companyid')->join('areainfo a', 'a.id = m.areaid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($joblist) {
                foreach ($joblist as $k => $v) {
                    $rmap['jobid'] = $v['id'];
                    $notecount = JobrecordModel::getJobrecordToal($rmap);
                    $joblist[$k]['notecount'] = $notecount;
                    $joblist[$k]['speciallist'] = explode(',', $v['special']);
                    $joblist[$k]['createtime'] = date('Y-m-d');
                    $joblist[$k]['updatetime'] = $this->time_tran($v['updatetime']);
                    
                    if ($v['toptime'] > time()) {
                        
                        $joblist[$k]['toptime'] = '置顶中';
                        
                    } else {
                        
                        $joblist[$k]['toptime'] = '未置顶';
                        
                    }
                    
                    
                }
                
            }
            
            
            return $joblist;
            
        }
        
        public function getJobTotal($map)
        {
            
            $count = Job::where($map)->count();
            return $count;
            
        }
        
        public static function getCount($map)
        {
            
            return self::where($map)->count();
            
        }
        
        
        public static function getJobbyCompanyId($map)
        {
            
            $joblist = self::where($map)->select();
            
            
            return $joblist;
            
        }
        
        public static function getOne($map)
        {
            
            
            return self::where($map)->find();
        }
        
        public function getJob($map)
        {
            
            
            /*
              $jobinfo = $this->alias ('j')
                  ->field('j.id AS id,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney,j.status AS status, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,a.name AS areaname ')
                  ->join('jobcate c', 'c.id = j.worktype')
                  ->join('company m', 'm.id = j.companyid')
                  ->join('areainfo a', 'a.id = m.areaid')
                  ->where($map)
                  ->find();
                  
              
          */
            
            
            $jobinfo = self::with('companyinfo')->where($map)->find();
            
            
            return $jobinfo;
            
        }
        
        
        public function companyinfo()
        {
            
            return $this->hasOne('Company', 'id', 'companyid');
        }
        
        
        public function insertJob($param)
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
        
        
        public function updateJob($param)
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
