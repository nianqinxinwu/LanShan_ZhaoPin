<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class City extends Model
    {
        protected $name = 'zpwxsys_city';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCityByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getCityCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        
        /**
         * [insertCity 添加]
         * @author
         */
        public function insertCity($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '城市添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '城市添加失败'];
            }
        }
        
        
        /**
         * [updateCity 编辑]
         * @author
         */
        public function updateCity($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '城市编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '城市编辑失败'];
            }
        }
        
        
        /**
         * [getOneCity 根据id获取一条信息]
         * @author
         */
        public function getOneCity($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public function getCity()
        {
            return $this->order('sort desc')->select();
        }
        
        
        /**
         * [delCity 删除]
         * @author
         */
        public function delCity($id)
        {
            $title = $this->where('id', $id)->value('name');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '城市删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '城市删除失败'];
            }
        }
        
        
    }
