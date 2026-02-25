<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Ploite extends Model
    {
        protected $name = 'zpwxsys_ploite';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getPloiteByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias ('p')
                ->field('p.id AS id , p.type AS type,p.content AS content,p.createtime AS createtime,j.jobtitle AS jobtitle,u.nickname AS nickname,u.tel AS tel')
                ->join('zpwxsys_job j', 'j.id = p.pid','left')
                ->join('zpwxsys_user u', 'u.id = p.uid','left')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($od)
                ->select();
        }
        
        public function getPloiteCount($map)
        {
            return $this->alias ('p')
                  ->field('p.id AS id , p.type AS type,p.content AS content,p.createtime AS createtime,j.jobtitle AS jobtitle,u.nickname AS nickname,u.tel AS tel')
                ->join('zpwxsys_job j', 'j.id = p.pid','left')
                ->join('zpwxsys_user u', 'u.id = p.uid','left')
                ->where($map)
                ->count();
        }
        
        


  
        

        
        /**
         * [delPloite 删除]
         * @author
         */
        public function delPloite($id)
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
