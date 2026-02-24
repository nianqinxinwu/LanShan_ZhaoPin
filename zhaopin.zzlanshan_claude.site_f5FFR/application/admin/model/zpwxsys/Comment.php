<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Comment extends Model
    {
        protected $name = 'zpwxsys_comment';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCommentByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias ('c')
                ->field('g.companyname AS companyname,c.id AS id,c.content AS content,c.companyid AS companyid,c.score AS score,c.create_time AS create_time,u.nickname AS nickname,u.avatarUrl AS avatarUrl')
                ->join('zpwxsys_user u', 'u.id = c.uid')
                ->join('zpwxsys_company g', 'g.id = c.companyid')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($od)
                ->select();
        }
        
        public function getCommentCount($map)
        {
            return $this->alias ('c')
                ->field('g.companyname AS companyname,c.id AS id,c.content AS content,c.companyid AS companyid,c.score AS score,c.create_time AS create_time,u.nickname AS nickname,u.avatarUrl AS avatarUrl')
                ->join('zpwxsys_user u', 'u.id = c.uid')
                ->join('zpwxsys_company g', 'g.id = c.companyid')
                ->where($map)
                ->count();
        }
        
        
        
        
        
        
        
        
        /**
         * [delComment 删除]
         * @author
         */
        public function delComment($id)
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
