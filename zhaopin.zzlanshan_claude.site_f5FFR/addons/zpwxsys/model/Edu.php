<?php
    
    namespace addons\zpwxsys\model;
    

    use think\Db;
    
    class Edu extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_edu';
        
        public static function getEdu($id)
        {
            
            $notedetail = self::where('id', '=', $id)->find();
            
            
            return $notedetail;
            
        }
        
        public static function getEduByUidSelect($uid)
        {
            
            
            $edulist = self::where('uid', '=', $uid)->order('id ASC')->select();
            
            return $edulist;
            
        }
        
        public static function getEduByUidAll($uid)
        {
            
            $edulist = self::where('uid', '=', $uid)->order('id ASC')->select();
            
            $ln = 0;
            
            if (count($edulist) > 0) {
                $ln = count($edulist) - 1;
                
                
                $eduinfo['begindateschool'] = $edulist[0]['begindateschool'];
                $eduinfo['enddateschool'] = $edulist[0]['enddateschool'];
                $eduinfo['school'] = $edulist[0]['school'];
                $eduinfo['educationname'] = $edulist[0]['educationname'];
                $eduinfo['vocation'] = $edulist[0]['vocation'];
                
                if ($ln > 0) {
                    
                    $eduinfo['begindateschool2'] = $edulist[1]['begindateschool'];
                    $eduinfo['enddateschool2'] = $edulist[1]['enddateschool'];
                    $eduinfo['school2'] = $edulist[1]['school'];
                    $eduinfo['educationname2'] = $edulist[1]['educationname'];
                    $eduinfo['vocation2'] = $edulist[1]['vocation'];
                    
                }
                
                $data = array('eduinfo' => $eduinfo, 'ln' => $ln);
            } else {
                
                $data = array('eduinfo' => array(), 'ln' => $ln);
                
            }
            
            
            return json_encode($data);
            
        }
        
        public function insertEdu($param)
        {
            
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->saveAll($param);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1);
            }
            
            
            return json_encode($data);
            
            
        }
        
        public function delEduByuid($uid)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->where('uid', $uid)->delete();
                Db::commit();// 提交事务
                $data = array('status' => 0);
                
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1);
                
            }
            
            
            return json_encode($data);
            
        }
        
        public function updateEdu($param)
        {
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                $data = array('status' => 1);
            }
            
            return json_encode($data);
        }
        
        
        public function time_tran($time)
        {
            $t = time() - $time;
            $f = array('31536000' => '年', '2592000' => '个月', '604800' => '星期', '86400' => '天', '3600' => '小时', '60' => '分钟', '1' => '秒');
            foreach ($f as $k => $v) {
                if (0 != $c = floor($t / (int)$k)) {
                    return $c . $v . '前';
                }
            }
        }
        
        
    }
