<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Area extends Model
    {
        protected $name = 'zpwxsys_area';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getAreaByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('a')->field('a.id AS id,a.name AS name ,a.sort AS sort,c.id AS cityid, c.name AS cityname,a.enabled AS enabled')->join('zpwxsys_city c', 'a.cityid = c.id')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getAreaCount($map)
        {
            return $this->alias('a')->field('a.id AS id,a.name AS name ,a.sort AS sort,c.id AS cityid, c.name AS cityname,a.enabled AS enabled')->join('zpwxsys_city c', 'a.cityid = c.id')->where($map)->count();
            
        }
        
        public function getAreaListBycityId($param)
        {
            
            return $this->where('cityid', $param['cityid'])->order('sort desc')->select();
            
        }
        
        
        /**
         * [insertArea 添加]
         * @author
         */
        public function insertArea($param)
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
         * [updateArea 编辑]
         * @author
         */
        public function updateArea($param)
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
         * [getOneArea 根据id获取一条信息]
         * @author
         */
        public function getOneArea($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delArea 删除]
         * @author
         */
        public function delArea($id)
        {
            $title = $this->where('id', $id)->value('name');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '区域删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '区域删除失败'];
            }
        }
        
        
    }