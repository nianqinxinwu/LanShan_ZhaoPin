<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Active extends Model
    {
        protected $name = 'zpwxsys_active';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        
        /**
         * 根据搜索条件获取文章列表信息
         * @author
         */
        public function getActiveByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getActiveCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        
        /**
         * [insertArticle 添加文章]
         * @author
         */
        public function insertActive($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                $aid = DB::getLastInsID();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '招聘会添加成功', 'aid' => $aid];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '招聘会添加失败'];
            }
        }
        
        
        /**
         * [updateArticle 编辑文章]
         * @author
         */
        public function updateActive($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '招聘会编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '招聘会编辑失败'];
            }
        }
        
        
        /**
         * [getOneArticle 根据文章id获取一条信息]
         * @author
         */
        public function getOneActive($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delArticle 删除文章]
         * @author
         */
        public function delActive($id)
        {
            $title = $this->where('id', $id)->value('title');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '招聘会删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '招聘会删除失败'];
            }
        }
        
   
        
     
        
    }
