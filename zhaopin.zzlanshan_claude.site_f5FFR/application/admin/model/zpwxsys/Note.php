<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Note extends Model
    {
        protected $name = 'zpwxsys_note';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取信息
         * @author
         */
        public function getNoteByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('n')->field('n.id AS id,n.status AS status,n.name AS name ,n.avatarUrl AS avatarUrl ,n.jobtitle AS jobtitle,c.name AS cityname,j.name AS jobcatename,n.education AS education,n.express AS express,n.createtime AS createtime,n.sex AS sex, n.tel AS tel,n.address AS address , n.birthday AS birthday,n.jobcateid AS jobcateid,n.ishidden AS ishidden,a.name AS areaname ')->join('zpwxsys_city c', 'c.id = n.cityid')->join('zpwxsys_area a', 'a.id = n.areaid')->join('zpwxsys_jobcate j', 'j.id = n.jobcateid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getNoteCount($map)
        {
            return $this->alias('n')->field('n.id AS id,n.status AS status,n.name AS name ,n.avatarUrl AS avatarUrl ,n.jobtitle AS jobtitle,c.name AS cityname,j.name AS jobcatename,n.education AS education,n.express AS express,n.createtime AS createtime,n.sex AS sex, n.tel AS tel,n.address AS address , n.birthday AS birthday,n.jobcateid AS jobcateid,a.name AS areaname')->join('zpwxsys_city c', 'c.id = n.cityid')->join('zpwxsys_area a', 'a.id = n.areaid')->join('zpwxsys_jobcate j', 'j.id = n.jobcateid')->where($map)->count();
            
        }
        
        
   
      
        
        public static function getCount()
        {
            
            $map = [];
            $count = self::where($map)->count();
            return $count;
        }
        
        
        public function insertNote($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '简历添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '简历添加失败'];
            }
        }
        
        
        public function updateNote($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '简历编辑成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '简历编辑失败'];
            }
        }
        
        
        public static function getOne($map)
        {
            
            return self::where($map)->find();
        }
        
        public function getOneNote($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public function delNote($id)
        {
            $title = $this->where('id', $id)->value('name');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '简历删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '简历删除失败'];
            }
        }
        
        
    }