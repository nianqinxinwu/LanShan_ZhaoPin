<?php
    
    namespace addons\zpwxsys\service;
    
    
    use addons\zpwxsys\model\Lookrolerecord as LookRoleRecordModel;
    use think\Exception;
    use think\Log;
    
    class LookRoleRecord
    {
        
        protected $uid;
        
        public $mark = '';
        
        public $looknum;
        
        
        function __construct($uid, $looknum)
        {
            
            
            if (!$uid) {
                throw new Exception('用户异常');
            }
            
            $this->uid = $uid;
            
            $this->looknum = $looknum;
            
        }
        
        
        public function SetRecord()
        {
            
            $list = $this->GetFirstRecord();
            
            $LookRoleRecordModel = new LookRoleRecordModel();
            
            
            if (!$list->isEmpty()) {
                Log::record($list, 'orderlist');
                
                $LookRoleRecordModel->totallooknum = $list[0]['totallooknum'] + $this->looknum;
                
                $LookRoleRecordModel->looknum = $this->looknum;
                
                $LookRoleRecordModel->uid = $this->uid;
                
                $LookRoleRecordModel->mark = $this->mark;
                
                $LookRoleRecordModel->createtime = time();
                
                
            } else {
                
                $LookRoleRecordModel->totallooknum = $this->looknum;
                
                $LookRoleRecordModel->looknum = $this->looknum;
                
                $LookRoleRecordModel->uid = $this->uid;
                
                $LookRoleRecordModel->mark = $this->mark;
                
                $LookRoleRecordModel->createtime = time();
                
            }
            
            $LookRoleRecordModel->save();
            
            return true;
            
        }
        
        public function GetFirstRecord()
        {
            
            $LookRoleRecordModel = new LookRoleRecordModel();
            
            $map['uid'] = $this->uid;
            
            $od = 'create_time desc';
            
            $list = $LookRoleRecordModel->getLookRoleRecordByWhere($map, $od);
            
            
            return $list;
            
            
        }
        
        
    }