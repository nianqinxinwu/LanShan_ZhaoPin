<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Nav extends Model
    {
        protected $name = 'zpwxsys_nav';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getNavByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getNavCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        
        /**
         * [insertNav 添加]
         * @author
         */
        public function insertNav($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '导航添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '导航添加失败'];
            }
        }
        
        
        /**
         * [updateNav 编辑]
         * @author
         */
        public function updateNav($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '导航编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '导航编辑失败'];
            }
        }
        
        
        /**
         * [getOneNav 根据id获取一条信息]
         * @author
         */
        public function getOneNav($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delNav 删除]
         * @author
         */
        public function delNav($id)
        {
            $title = $this->where('id', $id)->value('advname');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '幻灯片删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '幻灯片删除失败'];
            }
        }
        
        
    }