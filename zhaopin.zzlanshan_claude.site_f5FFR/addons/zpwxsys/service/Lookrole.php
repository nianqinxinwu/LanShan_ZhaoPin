<?php
    
    namespace addons\zpwxsys\service;
    
    use addons\zpwxsys\model\Lookrecord as LookrecordModel;
    use addons\zpwxsys\model\Lookcompanyrecord as LookcompanyrecordModel;
    
    
    class LookRole
    {
        
        protected $uid;
        protected $companyid;
        protected $noteid;
        
        
        function __construct($uid, $companyid, $noteid)
        {
            
            
            $this->uid = $uid;
            
            $this->companyid = $companyid;
            
            $this->noteid = $noteid;
            
        }
        
        
        public function CheckIsLookNote()
        {
            
            $isLook = false;
            
            if ($this->companyid > 0) {
                
                $cmap['companyid'] = $this->companyid;
                $cmap['noteid'] = $this->noteid;
                
                
                $clookinfo = LookcompanyrecordModel::getOne($cmap);
                
                
                if ($clookinfo) {
                    $isLook = true;
                }
                
            }
            
            if (!$isLook) {
                $map['uid'] = $this->uid;
                $map['jobid'] = $this->noteid;
                
                
                $lookinfo = LookrecordModel::getOne($map);
                
                if ($lookinfo) {
                    $isLook = true;
                }
                
                
            }
            
            return $isLook;
            
            
        }
        
        
    }