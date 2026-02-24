<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class User extends Model
    {
        protected $name = 'zpwxsys_user';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getWxuserByWhere($map, $Nowpage, $limits, $od)
        {
           // return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            return $this->alias('u')->field('u.id AS id,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,u.create_time AS create_time,a.name AS agentname ')
                ->join('zpwxsys_agent a', 'a.uid = u.tid','left')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($od)->select();
        }
        
        public function getWxuserCount($map)
        {
            return $this->alias('u')->field('u.id AS id,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,u.create_time AS create_time,a.name AS agentname ')
                ->join('zpwxsys_agent a', 'a.uid = u.tid','left')
                ->where($map)->count();
            
        }
        
        
        public function getRectuserCount($map)
        {
            return $this->alias('u')->field('u.id AS id,r.name AS name,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,u.create_time AS createtime ')->join('rect r', 'r.id = u.rectid')->where($map)->count();
            
        }
        
        
        public function getRectuserByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('u')->field('u.id AS id,r.name AS name,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,u.create_time AS createtime ')->join('rect r', 'r.id = u.rectid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getRectnoteCount($map)
        {
            return $this->alias('u')->field('u.id AS id,n.name AS notename, n.jobtitle AS jobtitle,r.name AS name,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,u.create_time AS createtime ')->join('rect r', 'r.id = u.rectid')->join('note n', 'n.uid = u.id')->where($map)->count();
            
        }
        
        
        public function getRectnoteByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('u')->field('u.id AS id,n.name AS notename, n.jobtitle AS jobtitle,r.name AS name,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,n.createtime AS createtime ')->join('rect r', 'r.id = u.rectid')->join('note n', 'n.uid = u.id')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getRectcompanyCount($map)
        {
            return $this->alias('u')->field('u.id AS id,c.companyname AS companyname,r.name AS name,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,u.create_time AS createtime ')->join('rect r', 'r.id = u.rectid')->join('company c', 'c.uid = u.id')->where($map)->count();
            
        }
        
        
        public function getRectcompanyByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('u')->field('u.id AS id,c.companyname AS companyname,r.name AS name,u.avatarUrl AS avatarUrl,u.nickname AS nickname,u.tel AS tel,c.createtime AS createtime ')->join('rect r', 'r.id = u.rectid')->join('company c', 'c.uid = u.id')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getOneWxUser($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
          public static function getOne($map)
             {
                 
                 return self::where($map)->find();
             }
   
        
        public static function getCount($map)
        {
            return self::where($map)->count();
        }
        
        
        public function delUser($id)
        {
            $title = $this->where('id', $id)->value('id');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '删除失败'];
            }
        }
        
        
    }