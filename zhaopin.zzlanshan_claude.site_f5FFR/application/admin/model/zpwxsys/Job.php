<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Job extends Model
    {
        protected $name = 'zpwxsys_job';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getJobByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('j')->field('j.id AS id,j.jobtitle AS jobtitle, j.dmoney AS dmoney,j.status AS status,j.ischeck AS ischeck, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname,t.name AS worktypename')->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')->join('zpwxsys_worktype t', 't.id = j.type','left')->join('zpwxsys_company m', 'm.id = j.companyid','left')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getJobList($map,$od)
        {
            
            return $this->alias('j')->field('j.id AS id,j.jobtitle AS jobtitle, j.dmoney AS dmoney,j.status AS status,j.ischeck AS ischeck, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname,t.name AS worktypename')->join('zpwxsys_jobcate c', 'c.id = j.worktype','left')->join('zpwxsys_worktype t', 't.id = j.type','left')->join('zpwxsys_company m', 'm.id = j.companyid','left')->where($map)->order($od)->select();
            
            
        }
        
        public function getJobCount($map)
        {
            return $this->alias('j')->field('j.id AS id,j.jobtitle AS jobtitle, j.dmoney AS dmoney,j.status AS status, c.name AS jobcatename,j.num AS num,j.sex AS sex,j.age AS age,j.money AS money,j.endtime AS endtime,j.updatetime AS updatetime,j.createtime AS createtime, j.sort AS sort, m.companyname AS companyname,t.name AS worktypename')->join('zpwxsys_jobcate c', 'c.id = j.worktype')->join('zpwxsys_worktype t', 't.id = j.type')->join('zpwxsys_company m', 'm.id = j.companyid')->where($map)->count();
            
        }
        
        
        public static function getCount()
        {
            
            $map = [];
            $count = self::where($map)->count();
            
            return $count;
            
        }
        
        /**
         * [insertJob 添加]
         * @author
         */
        public function insertJob($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业添加失败'];
            }
        }
        
        
        /**
         * [updateJob 编辑]
         * @author
         */
        public function updateJob($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '职位编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '职位编辑失败'];
            }
        }
        
        
        /**
         * [getOneJob 根据id获取一条信息]
         * @author
         */
        public function getOneJob($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delJob 删除]
         * @author
         */
        public function delJob($id)
        {
            $title = $this->where('id', $id)->value('jobtitle');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '职位删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '职位删除失败'];
            }
        }
        
        
    }