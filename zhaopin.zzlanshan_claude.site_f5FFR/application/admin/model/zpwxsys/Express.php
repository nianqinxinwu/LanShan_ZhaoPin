<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Express extends Model
    {
        protected $name = 'zpwxsys_express';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件文章列表信息
         * @author
         */
         
        public function getExpressByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
    
        public function getExpressCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        public function getAllExpress($map, $od)
        {
            
            
            return $this->where($map)->order($od)->select();
            
            
        }
        
        
        /**
         * [insertJobcate 添加]
         * @author
         */
        public function insertExpress($param)
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
         * [delEdu 删除]
         * @author
         */
        public function delExpress($id)
        {
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