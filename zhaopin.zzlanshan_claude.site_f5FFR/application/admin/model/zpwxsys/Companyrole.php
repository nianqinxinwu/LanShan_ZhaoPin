<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Companyrole extends Model
    {
        protected $name = 'zpwxsys_companyrole';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCompanyroleByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getCompanyroleCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        public function getAllCompanyrole($map, $od)
        {
            
            
            return $this->where($map)->order($od)->select();
            
            
        }
        
        public static function getOne($map)
        {
            
            return self::where($map)->find();
        }
        
        
        /**
         * [insertCompanyrole 添加]
         * @author
         */
        public function insertCompanyrole($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业套餐添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业套餐添加失败'];
            }
        }
        
        
        /**
         * [updateCompanyrole 编辑]
         * @author
         */
        public function updateCompanyrole($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业套餐编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业套餐编辑失败'];
            }
        }
        
        
        /**
         * [getOneCompanyrole 根据id获取一条信息]
         * @author
         */
        public function getOneCompanyrole($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public function delCompanyrole($id)
        {
            $title = $this->where('id', $id)->value('title');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业套餐删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业套餐删除失败'];
            }
        }
        
        
    }