<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Active extends BaseModel
    {
        protected $name = 'zpwxsys_active';
        
        public function getActiveByWhere($map, $Nowpage, $limits, $od)
        {
            $activelist = $this->where($map)->page($Nowpage, $limits)->order($od)->select();
            $data['from'] = 1;
            if ($activelist) {
                foreach ($activelist as $k => $v) {
                    $endtime = strtotime($v['endtime']);
                    
                    if ($endtime > time()) {
                        
                        $activelist[$k]['statusstr'] = '正在进行中';
                        
                    } else {
                        
                        $activelist[$k]['statusstr'] = '已结束';
                        
                    }
                    
                    $activelist[$k]['thumb'] = cdnurl($v['thumb'], true);;
                }
                
            }
            
            
            return $activelist;
            
        }
        
        
        public static function getOne($map)
        {
            
            $activeinfo = self::where($map)->find();
            
            
            return $activeinfo;
            
        }
        
        
        public static function getTask($map)
        {
            
            $taskdetail = self::where($map)->find();
            
            
            return $taskdetail;
            
        }
        
        
        public function getActiveDetail($map)
        {
            
            $activeinfo = self::where($map)->find();
            
            $data['from'] = 1;
            //   $activeinfo['thumb'] = self::prefixImgUrl( $activeinfo['thumb'],$data);
            $activeinfo['thumb'] = cdnurl($activeinfo['thumb'], true);
            
            
            return $activeinfo;
            
        }
        
        
        public function jobinfo()
        {
            
            return $this->hasOne('Job', 'id', 'jobid');
        }
        
        
        public function companyinfo()
        {
            
            return $this->hasOne('Company', 'id', 'companyid');
        }
        
        
        public function insertTask($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                
                $data = array('status' => 1);
            }
            
            
            return json_encode($data);
            
            
        }
        
        
        public function updateActive($param)
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
        
        
    }
