<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Adv extends Model
    {
        protected $name = 'zpwxsys_adv';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getAdvByWhere($map, $Nowpage, $limits, $od)
        {
            return $this->alias('a')->field('a.id AS id,a.advname AS advname,a.sort AS sort ,c.id AS cityid, c.name AS cityname,a.enabled AS enabled,a.thumb AS thumb')->join('zpwxsys_city c', 'a.cityid = c.id')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        
        public function getAdvCount($map)
        {
            return $this->alias('a')->field('a.id AS id,a.advname AS advname,a.sort AS sort ,c.id AS cityid, c.name AS cityname,a.enabled AS enabled')->join('zpwxsys_city c', 'a.cityid = c.id')->where($map)->count();
            
        }
        
        
        /**
         * [insertAdv 添加]
         * @author
         */
        public function insertAdv($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'城市'.$param['name'].'】添加成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '幻灯片添加成功'];
            } catch (\Exception $e) {
                
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'城市'.$param['name'].'】添加失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '幻灯片添加失败'];
            }
        }
        
        
        /**
         * [updateAdv 编辑]
         * @author
         */
        public function updateAdv($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '幻灯片编辑成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' => '幻灯片编辑失败'];
            }
        }
        
        
        /**
         * [getOneAdv 根据文章id获取一条信息]
         * @author
         */
        public function getOneAdv($id)
        {
            return $this->where('id', $id)->find();
        }
        
        
        /**
         * [delArticle 删除]
         * @author
         */
        public function delAdv($id)
        {
            $title = $this->where('id', $id)->value('advname');
            Db::startTrans();// 启动事务
            try {
                $this->where('id', $id)->delete();
                Db::commit();// 提交事务
                // writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
                return ['code' => 200, 'data' => '', 'msg' => '幻灯片删除成功'];
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                //  writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
                return ['code' => 100, 'data' => '', 'msg' => '幻灯片删除失败'];
            }
        }
        
        
    }