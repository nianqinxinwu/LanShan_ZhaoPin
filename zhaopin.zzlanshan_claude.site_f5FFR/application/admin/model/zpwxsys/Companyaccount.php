<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Companyaccount extends Model
    {
        protected $name = 'zpwxsys_companyaccount';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCompanyaccountByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('a')->field('a.id AS id,c.companyname AS companyname,a.name AS name, a.createtime AS createtime,a.status AS status ')->join('zpwxsys_company c', 'a.companyid = c.id')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getCompanyaccountCount($map)
        {
            return $this->alias('a')->field('a.id AS id,c.companyname AS companyname ')->join('zpwxsys_company c', 'a.companyid = c.id')->where($map)->count();
            
        }
        
      
        
        
        /**
         * [insertCompanyaccount 添加]
         * @author
         */
        public function insertCompanyaccount($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业账号添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业账号添加失败'];
            }
        }
        
        
        /**
         * [updateCompanyaccount 编辑]
         * @author
         */
        public function updateCompanyaccount($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业账号编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业账号编辑失败'];
            }
        }
        
        
        /**
         * [getOneCompanyaccount 根据id获取一条信息]
         * @author
         */
        public function getOneCompanyaccount($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delCompanyaccount 删除]
         * @author
         */
        public function delCompanyaccount($id)
        {
            $title = $this->where('id', $id)->value('name');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '企业账号删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '企业账号删除失败'];
            }
        }
        
        
    }