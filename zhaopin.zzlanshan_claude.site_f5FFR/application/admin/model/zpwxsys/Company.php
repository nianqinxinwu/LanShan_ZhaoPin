<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Company extends Model
    {
        protected $name = 'zpwxsys_company';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getCompanyByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('g')->field('g.id AS id, g.companyname AS companyname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort,c.id AS cityid, c.name AS cityname,a.name AS areaname,g.status AS status,g.isrecommand AS isrecommand,g.thumb AS thumb ')->join('zpwxsys_city c', 'g.cityid = c.id','left')->join('zpwxsys_area a', 'g.areaid = a.id','left')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getCompanyCount($map)
        {
            return $this->alias('g')->field('g.id AS id, g.companyname AS companyname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort,c.id AS cityid, c.name AS cityname,a.name AS areaname,g.status AS status')->join('zpwxsys_city c', 'g.cityid = c.id','left')->join('zpwxsys_area a', 'g.areaid = a.id','left')->where($map)->count();
            
        }
        
        
        public function getAllCompany($map, $od)
        {
            
            return $this->alias('g')->field('g.id AS id, g.companyname AS companyname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort,c.id AS cityid, c.name AS cityname,a.name AS areaname')->join('zpwxsys_city c', 'g.cityid = c.id','left')->join('zpwxsys_area a', 'g.areaid = a.id','left')->where($map)->order($od)->select();
            
        }
        
        
        public function getAllCompanyList($map, $od)
        {
            
            return $this->alias('g')->field('g.id AS id, g.companyname AS companyname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort')->where($map)->order($od)->select();
            
        }
        
        
        public static function getCount()
        {
            
            $map = [];
            $count = self::where($map)->count();
            return $count;
        }
        
        /**
         * [insertCompany 添加]
         * @author
         */
        public function insertCompany($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业添加失败'];
            }
        }
        
        
        /**
         * [updateCompany 编辑]
         * @author
         */
        public function updateCompany($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业编辑失败'];
            }
        }
        
        
        /**
         * [getOneCompany 根据id获取一条信息]
         * @author
         */
        public function getOneCompany($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delCompany 删除]
         * @author
         */
        public function delCompany($id)
        {
            $title = $this->where('id', $id)->value('companyname');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '企业删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '企业删除失败'];
            }
        }
        
        
    }