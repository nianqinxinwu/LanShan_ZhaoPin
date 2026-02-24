<?php

    namespace addons\zpwxsys\model;

    use addons\zpwxsys\model\Jobrecord as JobrecordModel;
    use think\Db;

    class Job extends BaseModel
    {

        protected $name = 'zpwxsys_job';

        public static function ensureZwFields()
        {
            $table = config('database.prefix') . 'zpwxsys_job';
            $columns = Db::query("SHOW COLUMNS FROM `{$table}` LIKE 'job_address'");
            if (empty($columns)) {
                Db::execute("ALTER TABLE `{$table}`
                    ADD COLUMN `job_address` varchar(200) DEFAULT '' COMMENT '岗位工作地址',
                    ADD COLUMN `job_lat` decimal(10,6) DEFAULT NULL COMMENT '岗位纬度',
                    ADD COLUMN `job_lng` decimal(10,6) DEFAULT NULL COMMENT '岗位经度',
                    ADD COLUMN `work_start_date` varchar(20) DEFAULT '' COMMENT '工作开始日期',
                    ADD COLUMN `work_end_date` varchar(20) DEFAULT '' COMMENT '工作结束日期',
                    ADD COLUMN `work_start_time` varchar(10) DEFAULT '' COMMENT '每日开始时间',
                    ADD COLUMN `work_end_time` varchar(10) DEFAULT '' COMMENT '每日结束时间',
                    ADD COLUMN `requirements` text COMMENT '工作要求',
                    ADD COLUMN `tips` text COMMENT '温馨提示',
                    ADD COLUMN `benefit_tag1` varchar(50) DEFAULT '' COMMENT '福利标签1',
                    ADD COLUMN `benefit_tag2` varchar(50) DEFAULT '' COMMENT '福利标签2',
                    ADD COLUMN `hourly_rate` decimal(10,2) DEFAULT NULL COMMENT '工价(元/小时)'
                ");
            }
            // 确保 jobcatename 列存在
            $col2 = Db::query("SHOW COLUMNS FROM `{$table}` LIKE 'jobcatename'");
            if (empty($col2)) {
                Db::execute("ALTER TABLE `{$table}` ADD COLUMN `jobcatename` varchar(50) DEFAULT '' COMMENT '工作类型名称'");
            }
            // 确保 signin_phase 列存在
            $col3 = Db::query("SHOW COLUMNS FROM `{$table}` LIKE 'signin_phase'");
            if (empty($col3)) {
                Db::execute("ALTER TABLE `{$table}` ADD COLUMN `signin_phase` tinyint(4) DEFAULT 0 COMMENT '签到阶段 0=无 1=已发起工作确认 2=已发起签到 3=已发起签退'");
            }
        }
        
        public function getNearJobByWhere($map, $Nowpage, $limits, $od, $latitude, $longitude)
        {
            $joblist = $this->alias('j')->field('j.id AS id,j.special AS special,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney,j.status AS status, IFNULL(c.name, j.jobcatename) AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,IFNULL(a.name, \'\') AS areaname,j.toptime AS toptime,j.settle_type AS settle_type ,ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ' . $latitude . '  * PI() / 180 - m.lat * PI() / 180) / 2),2) + COS(  ' . $latitude . ' * PI() / 180) * COS(m.lat * PI() / 180) * POW(SIN(( ' . $longitude . ' * PI() / 180 - m.lng * PI() / 180) / 2),2))) * 1000) AS distance ')->join('zpwxsys_company m', 'm.id = j.companyid')->join('zpwxsys_area a', 'a.id = m.areaid', 'LEFT')->join('zpwxsys_jobcate c', 'c.id = j.worktype', 'LEFT')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($joblist) {
                foreach ($joblist as $k => $v) {
                    $rmap['jobid'] = $v['id'];
                    $notecount = JobrecordModel::getJobrecordToal($rmap);
                    $joblist[$k]['notecount'] = $notecount;
                    $joblist[$k]['speciallist'] = explode(',', $v['special'] ?? '');
                    $joblist[$k]['createtime'] = date('Y-m-d');
                    $joblist[$k]['updatetime'] = $this->time_tran($v['updatetime']);

                    $joblist[$k]['distance'] = round($joblist[$k]['distance'] / 1000, 2);
                    
                    
                    if ($v['toptime'] > time()) {

                        $joblist[$k]['toptime'] = '置顶中';
                        $joblist[$k]['is_top'] = 1;

                    } else {

                        $joblist[$k]['toptime'] = '未置顶';
                        $joblist[$k]['is_top'] = 0;

                    }


                }

            }


            return $joblist;

        }


        public function getJobByWhere($map, $Nowpage, $limits, $od)
        {
            $joblist = $this->alias('j')->field('j.ischeck AS ischeck,j.id AS id,j.special AS special,j.jobtitle AS jobtitle,j.videourl AS videourl,m.address AS address, j.education AS education,j.dmoney AS dmoney,j.status AS status, IFNULL(c.name, j.jobcatename) AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname ,IFNULL(a.name, \'\') AS areaname,j.toptime AS toptime,j.settle_type AS settle_type,j.work_start_date AS work_start_date,j.work_end_date AS work_end_date,j.work_start_time AS work_start_time,j.work_end_time AS work_end_time,j.hourly_rate AS hourly_rate,j.job_address AS job_address ')->join('zpwxsys_company m', 'm.id = j.companyid')->join('zpwxsys_area a', 'a.id = m.areaid', 'LEFT')->join('zpwxsys_jobcate c', 'c.id = j.worktype', 'LEFT')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            if ($joblist) {
                foreach ($joblist as $k => $v) {
                    $rmap['jobid'] = $v['id'];
                    $notecount = JobrecordModel::getJobrecordToal($rmap);
                    $joblist[$k]['notecount'] = $notecount;
                    $joblist[$k]['speciallist'] = explode(',', $v['special'] ?? '');
                    $joblist[$k]['createtime'] = date('Y-m-d');
                    $joblist[$k]['updatetime'] = $this->time_tran($v['updatetime']);

                    if ($v['toptime'] > time()) {

                        $joblist[$k]['toptime'] = '置顶中';
                        $joblist[$k]['is_top'] = 1;

                    } else {

                        $joblist[$k]['toptime'] = '未置顶';
                        $joblist[$k]['is_top'] = 0;

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
