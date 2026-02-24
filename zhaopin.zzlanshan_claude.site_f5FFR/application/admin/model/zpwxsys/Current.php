<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class  Current extends Model
    {
        protected $name = 'zpwxsys_current';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCurrentByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getCurrentCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        
        public static function getList($map, $od)
        {
            $list = self::where($map)->order($od)->select();
            
            return $list;
            
        }
        
        
        /**
         * [insertCurrent 添加]
         * @author
         */
        public function insertCurrent($param)
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
         * [updateArticle 编辑文章]
         * @author
         */
        public function updateCurrent($param)
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
         * [getOneCurrent 根据id获取一条信息]
         * @author
         */
        public function getOneCurrent($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public function getCurrent()
        {
            return $this->order('sort desc')->select();
        }
        
        
        /**
         * [delCurrent 删除文章]
         * @author
         */
        public function delCurrent($id)
        {
            $title = $this->where('id', $id)->value('name');
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
