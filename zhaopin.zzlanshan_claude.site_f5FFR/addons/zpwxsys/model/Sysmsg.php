<?php
    
    namespace addons\zpwxsys\model;
    

    use think\Db;
    
    class Sysmsg extends BaseModel
    {

        protected $name = 'zpwxsys_sysmsg';

        protected static $migrated = false;

        /**
         * 运行时自动添加新字段（幂等）
         */
        public static function ensureNewFields()
        {
            if (self::$migrated) {
                return;
            }
            self::$migrated = true;
            $table = (new self)->getTable();
            $columns = Db::query("SHOW COLUMNS FROM `{$table}`");
            $existing = array_column($columns, 'Field');
            $newFields = [
                'jobtitle'    => "ALTER TABLE `{$table}` ADD COLUMN `jobtitle` varchar(100) NOT NULL DEFAULT '' COMMENT '关联岗位名称'",
                'money'       => "ALTER TABLE `{$table}` ADD COLUMN `money` varchar(30) NOT NULL DEFAULT '' COMMENT '工价'",
                'companyname' => "ALTER TABLE `{$table}` ADD COLUMN `companyname` varchar(100) NOT NULL DEFAULT '' COMMENT '发布人/企业名称'",
                'jobcatename' => "ALTER TABLE `{$table}` ADD COLUMN `jobcatename` varchar(50) NOT NULL DEFAULT '' COMMENT '岗位分类名'",
                'link'        => "ALTER TABLE `{$table}` ADD COLUMN `link` varchar(500) NOT NULL DEFAULT '' COMMENT '消息跳转链接'",
            ];
            foreach ($newFields as $field => $sql) {
                if (!in_array($field, $existing)) {
                    try {
                        Db::execute($sql);
                    } catch (\Exception $e) {
                        // 字段已存在或其他非致命错误，忽略
                    }
                }
            }
        }

        public static function getTotal($map)
        {

            $count = self::where($map)->count();
            return $count;

        }
        
        
        public function getSysmsgByWhere($map, $Nowpage, $limits, $od, $readstatus = null)
        {
            $query = $this->where($map);
            if ($readstatus !== null) {
                $query = $query->where('status', $readstatus);
            }
            $newslist = $query->page($Nowpage, $limits)->order($od)->select();
            if ($newslist) {
                foreach ($newslist as $k => $v) {
                    $newslist[$k]['createtime'] = date('Y-m-d', $v['createtime']);
                }

            }


            return $newslist;

        }


        public static function getUnreadCount($uid)
        {
            return self::where(['uid' => $uid, 'status' => 0])->count();
        }
        
        
        public static function getAllByuid($map)
        {
            
            $list = self::where($map)->order('createtime DESC')->select();
            
            
            return $list;
            
            
        }
        
        
        public function insertSysmsg($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '创建成功!');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '提交失败');
            }
            
            
            return json_encode($data);
            
        }
        
        public function updateSysmsg($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['uid' => $param['uid']]);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                $data = array('status' => 1);
            }

            return json_encode($data);
        }

        public function markAsReadById($id, $uid)
        {
            return self::where(['id' => $id, 'uid' => $uid])->update(['status' => 1]);
        }
        
        
    }
