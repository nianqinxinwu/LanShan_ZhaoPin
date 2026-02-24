<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Sysinit extends Model
    {
        protected $name = 'zpwxsys_sysinit';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        
        /**
         * [insertSysinit 添加]
         * @author
         */
        public function insertSysinit($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '保存成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '保存失败'];
            }
        }
        
        
        public function updateSysinit($param)
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
         * [getOneSysinit 根据id获取一条信息]
         * @author
         */
        public function getOneSysinit()
        {
            $map['id'] = array('gt', 0);
            return $this->where($map)->find();
        }
        
        
    }