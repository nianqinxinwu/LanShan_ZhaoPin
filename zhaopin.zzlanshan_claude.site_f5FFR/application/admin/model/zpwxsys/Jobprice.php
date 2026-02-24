<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Jobprice extends Model
    {
        protected $name = 'zpwxsys_jobprice';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        
        public function getJobpriceByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getJobpriceCount($map)
        {
            return $this->where($map)->count();
            
        }
        
        public function getAllJobprice($map, $od)
        {
            
            
            return $this->where($map)->order($od)->select();
            
            
        }
        
        
        /**
         * [insertJobprice 添加]
         * @author
         */
        public function insertJobprice($param)
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
         * [updateJobprice 编辑]
         * @author
         */
        public function updateJobprice($param)
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
         * [getOneJobprice 根据id获取一条信息]
         * @author
         */
        public function getOneJobprice($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        public static function getOne($map)
        {
            
            return self::where($map)->find();
            
            
        }
        
        
        public function getJobprice()
        {
            return $this->order('sort desc')->select();
        }
        
        
        /**
         * [delJobprice 删除]
         * @author
         */
        public function delJobprice($id)
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
