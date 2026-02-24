<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Notice extends Model
    {
        protected $name = 'zpwxsys_notice';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getNoticeByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getNoticeCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        /**
         * [insertNotice 添加]
         * @author
         */
        public function insertNotice($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '添加成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '添加失败'];
            }
        }
        
        
        /**
         * [updateNotice 编辑]
         * @author
         */
        public function updateNotice($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '编辑失败'];
            }
        }
        
        
        /**
         * [getOneNotice 根据id获取一条信息]
         * @author
         */
        public function getOneNotice($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delNotice 删除]
         * @author
         */
        public function delNotice($id)
        {
            $title = $this->where('id', $id)->value('title');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '删除失败'];
            }
        }
        
        
    }
