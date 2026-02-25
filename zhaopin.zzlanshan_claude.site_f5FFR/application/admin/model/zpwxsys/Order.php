<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Order extends Model
    {
        protected $name = 'zpwxsys_order';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getListByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getListCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        
        /**
         * [delOrder 删除]
         * @author
         */
        public function delOrder($id)
        {
            $title = $this->where('id', $id)->value('id');
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