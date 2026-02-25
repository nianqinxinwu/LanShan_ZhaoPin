<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Cate extends Model
    {
        protected $name = 'zpwxsys_cate';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCateByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getCateCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        public function getAllCate($map, $od)
        {
            
            
            return $this->where($map)->order($od)->select();
            
            
        }
        
        
        /**
         * [insertCate 添加]
         * @author
         */
        public function insertCate($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '分类添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '分类添加失败'];
            }
        }
        
        
        /**
         * [updateCate 编辑]
         * @author
         */
        public function updateCate($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '分类编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '分类编辑失败'];
            }
        }
        
        
        /**
         * [getOneCate 根据id获取一条信息]
         * @author
         */
        public function getOneCate($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public function getCate()
        {
            return $this->order('sort desc')->select();
        }
        
        
        /**
         * [delCate 删除]
         * @author
         */
        public function delCate($id)
        {
            $title = $this->where('id', $id)->value('name');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '分类删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '分类删除失败'];
            }
        }
        
        
    }
