<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Lookrole extends Model
    {
        protected $name = 'zpwxsys_lookrole';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getLookroleByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getLookroleCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        public function getAllLookrole($map, $od)
        {
            
            
            return $this->where($map)->order($od)->select();
            
            
        }
        
        
        /**
         * [insertLookrole 添加]
         * @author
         */
        public function insertLookrole($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '简历包添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '简历包添加失败'];
            }
        }
        
        
        /**
         * [updateLookrole 编辑]
         * @author
         */
        public function updateLookrole($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '简历包编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '简历包编辑失败'];
            }
        }
        
        
        /**
         * [getOneLookrole 根据id获取一条信息]
         * @author
         */
        public function getOneLookrole($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public function delLookrole($id)
        {
            $title = $this->where('id', $id)->value('title');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '简历包删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '简历包删除失败'];
            }
        }
        
        
    }