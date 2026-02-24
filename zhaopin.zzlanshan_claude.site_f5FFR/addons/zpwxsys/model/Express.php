<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Model;
    use think\Db;
    
    class Express extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_express';
        
        public static function getExpress($id)
        {
            
            $expressdetail = self::where('id', '=', $id)->find();
            
            
            return $expressdetail;
            
        }
        
        
        public static function getExpressByUidSelect($uid)
        {
            
            $expresslist = self::where('uid', '=', $uid)->order('id ASC')->select();
            
            return $expresslist;
            
        }
        
        public static function getExpressByUidAll($uid)
        {
            
            $expresslist = self::where('uid', '=', $uid)->order('id ASC')->select();
            
            $ln = 0;
            
            if (count($expresslist) > 0) {
                $ln = count($expresslist) - 1;
                
                
                $expressinfo['begindatejob'] = $expresslist[0]['begindatejob'];
                $expressinfo['enddatejob'] = $expresslist[0]['enddatejob'];
                $expressinfo['companyname'] = $expresslist[0]['companyname'];
                $expressinfo['jobtitle'] = $expresslist[0]['jobtitle'];
                
                if ($ln > 0) {
                    
                    $expressinfo['begindatejob2'] = $expresslist[1]['begindatejob'];
                    $expressinfo['enddatejob2'] = $expresslist[1]['enddatejob'];
                    $expressinfo['companyname2'] = $expresslist[1]['companyname'];
                    $expressinfo['jobtitle2'] = $expresslist[1]['jobtitle'];
                    
                }
                $data = array('expressinfo' => $expressinfo, 'ln' => $ln);
            } else {
                
                $data = array('expressinfo' => array(), 'ln' => 0);
                
            }
            
            
            return json_encode($data);
            
        }
        
        public function insertExpress($param)
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
        
        public function delExpressByuid($uid)
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
        
        public function updateExpress($param)
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
