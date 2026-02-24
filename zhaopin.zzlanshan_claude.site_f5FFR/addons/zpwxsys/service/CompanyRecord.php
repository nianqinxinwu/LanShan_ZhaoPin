<?php
    
    namespace addons\zpwxsys\service;
    
    
    use addons\zpwxsys\model\Companyrecord as CompanyRecordModel;
    use think\Exception;
    use think\Log;
    
    class CompanyRecord
    {
        
        public $companyid;
        
        public $notenum;
        
        public $jobnum;
        
        public $topnum;

        public $spreadnum;

        public $mark = '';
        
        
        function __construct($companyid)
        {
            
            
            if (!$companyid) {
                throw new Exception('用户异常');
            }
            
            $this->companyid = $companyid;
            
            // $this->looknum = $looknum;
            
        }
        
        
        public function SetRecord()
        {
            
            $list = $this->GetFirstRecord();
            
            $CompanyRecordModel = new CompanyRecordModel();
            
            
            if ($list) {
                Log::record($list, 'orderlist');
                
                $CompanyRecordModel->totalnotenum = $list[0]['totalnotenum'] + $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = $list[0]['totaljobnum'] + $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                
                $CompanyRecordModel->totaltopnum = $list[0]['totaltopnum'] + $this->topnum;
                
                $CompanyRecordModel->topnum = $this->topnum;
                
                
                $CompanyRecordModel->mark = $this->mark;
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
                
            } else {
                
                $CompanyRecordModel->totalnotenum = $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                $CompanyRecordModel->totaltopnum = $this->topnum;
                
                $CompanyRecordModel->topnum = $this->topnum;
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
            }
            
            $CompanyRecordModel->save();
            
            return true;
            
        }
        
        
        public function SetNoteNumRecord()
        {
            
            $list = $this->GetFirstRecord();
            
            $CompanyRecordModel = new CompanyRecordModel();
            
            
            if ($list) {
               // Log::record($list, 'orderlist');
                
                $CompanyRecordModel->totalnotenum = $list[0]['totalnotenum'] + $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = $list[0]['totaljobnum'];
                
                $CompanyRecordModel->jobnum = $list[0]['jobnum'];
                
                
                $CompanyRecordModel->totaltopnum = $list[0]['totaltopnum'];
                
                $CompanyRecordModel->topnum = $list[0]['topnum'];
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
                
            } else {
                
                $CompanyRecordModel->totalnotenum = $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = 0;
                
                $CompanyRecordModel->jobnum = 0;
                
                $CompanyRecordModel->totaltopnum = 0;
                
                $CompanyRecordModel->topnum = 0;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->createtime = time();
                
            }
            
            $CompanyRecordModel->save();
            
            return true;
            
        }
        
        
        public function SetJobNumRecord()
        {
            
            $list = $this->GetFirstRecord();
            
            $CompanyRecordModel = new CompanyRecordModel();
            
            
            if ($list) {
                Log::record($list, 'orderlist');
                
                $CompanyRecordModel->totaljobnum = $list[0]['totaljobnum'] + $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                
                $CompanyRecordModel->totalnotenum = $list[0]['totalnotenum'];
                
                $CompanyRecordModel->notenum = $list[0]['notenum'];
                
                $CompanyRecordModel->totaltopnum = $list[0]['totaltopnum'];
                
                $CompanyRecordModel->topnum = $list[0]['topnum'];
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
                
            } else {
                
                $CompanyRecordModel->totaljobnum = $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                $CompanyRecordModel->totalnotenum = 0;
                
                $CompanyRecordModel->notenum = 0;
                
                $CompanyRecordModel->totaltopnum = 0;
                
                $CompanyRecordModel->topnum = 0;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->createtime = time();
                
            }
            
            $CompanyRecordModel->save();
            
            return true;
            
        }
        
        
        public function SetTopNumRecord()
        {

            $list = $this->GetFirstRecord();

            $CompanyRecordModel = new CompanyRecordModel();


            if ($list) {
                Log::record($list, 'orderlist');

                $CompanyRecordModel->totaltopnum = $list[0]['totaltopnum'] + $this->topnum;

                if ($this->topnum > 0) {
                    $CompanyRecordModel->type = 0;

                } else {

                    $CompanyRecordModel->type = 1;
                }


                $CompanyRecordModel->topnum = $this->topnum;

                $CompanyRecordModel->totalnotenum = $list[0]['totalnotenum'];

                $CompanyRecordModel->notenum = $list[0]['notenum'];

                $CompanyRecordModel->totaljobnum = $list[0]['totaljobnum'];

                $CompanyRecordModel->jobnum = $list[0]['jobnum'];

                $CompanyRecordModel->companyid = $this->companyid;

                $CompanyRecordModel->mark = $this->mark;

                $CompanyRecordModel->createtime = time();


            } else {

                $CompanyRecordModel->totaltopnum = $this->topnum;

                $CompanyRecordModel->topnum = $this->topnum;

                $CompanyRecordModel->totalnotenum = 0;

                $CompanyRecordModel->notenum = 0;

                $CompanyRecordModel->totaljobnum = 0;

                $CompanyRecordModel->jobnum = 0;

                $CompanyRecordModel->companyid = $this->companyid;

                $CompanyRecordModel->mark = $this->mark;

                $CompanyRecordModel->createtime = time();

            }

            $CompanyRecordModel->save();

            return true;

        }


        public function SetSpreadNumRecord()
        {
            $list = $this->GetFirstRecord();
            $CompanyRecordModel = new CompanyRecordModel();

            if ($list) {
                $CompanyRecordModel->totalspreadnum = $list[0]['totalspreadnum'] + $this->spreadnum;

                if ($this->spreadnum > 0) {
                    $CompanyRecordModel->type = 0;
                } else {
                    $CompanyRecordModel->type = 1;
                }

                $CompanyRecordModel->totaltopnum = $list[0]['totaltopnum'];
                $CompanyRecordModel->topnum = $list[0]['topnum'];
                $CompanyRecordModel->totalnotenum = $list[0]['totalnotenum'];
                $CompanyRecordModel->notenum = $list[0]['notenum'];
                $CompanyRecordModel->totaljobnum = $list[0]['totaljobnum'];
                $CompanyRecordModel->jobnum = $list[0]['jobnum'];
                $CompanyRecordModel->companyid = $this->companyid;
                $CompanyRecordModel->mark = $this->mark;
                $CompanyRecordModel->createtime = time();
            } else {
                $CompanyRecordModel->totalspreadnum = $this->spreadnum;
                $CompanyRecordModel->totaltopnum = 0;
                $CompanyRecordModel->topnum = 0;
                $CompanyRecordModel->totalnotenum = 0;
                $CompanyRecordModel->notenum = 0;
                $CompanyRecordModel->totaljobnum = 0;
                $CompanyRecordModel->jobnum = 0;
                $CompanyRecordModel->companyid = $this->companyid;
                $CompanyRecordModel->mark = $this->mark;
                $CompanyRecordModel->createtime = time();
            }

            $CompanyRecordModel->save();
            return true;
        }
        
        
        public function GetFirstRecord()
        {
            
            $CompanyRecordModel = new CompanyRecordModel();
            
            $map['companyid'] = $this->companyid;
            
            
            $list = $CompanyRecordModel->getCompanyrecordPoP($map);
            
            
            return $list;
            
            
        }
        
        public static function getStaticFirst($companyid)
        {
             $CompanyRecordModel = new CompanyRecordModel();
             $map['companyid'] = $companyid;
             $list = $CompanyRecordModel->getCompanyrecordPoP($map);
            
            
            return $list;
        }
        
        
        public function setNewFreeRecord()
        {
         //  $list = $this->GetFirstRecord();
            
            $CompanyRecordModel = new CompanyRecordModel();
            
            
         
                
                $CompanyRecordModel->totaltopnum = $this->topnum;
                
                $CompanyRecordModel->topnum = $this->topnum;
                
                $CompanyRecordModel->totalnotenum = $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
            
            
            $CompanyRecordModel->save();
            
            return true; 
            
            
        }
        
        
        public function setFreeRecord()
        {
            $list = $this->GetFirstRecord();
            
            $CompanyRecordModel = new CompanyRecordModel();
            
            
            if ($list) {
                Log::record($list, 'orderlist');
                
                $CompanyRecordModel->totaltopnum = $list[0]['totaltopnum'] + $this->topnum;
                
                $CompanyRecordModel->topnum = $this->topnum;
                
                $CompanyRecordModel->totalnotenum = $list[0]['totalnotenum'] + $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = $list[0]['totaljobnum'] + $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
                
            } else {
                
                $CompanyRecordModel->totaltopnum = $this->topnum;
                
                $CompanyRecordModel->topnum = $this->topnum;
                
                $CompanyRecordModel->totalnotenum = $this->notenum;
                
                $CompanyRecordModel->notenum = $this->notenum;
                
                $CompanyRecordModel->totaljobnum = $this->jobnum;
                
                $CompanyRecordModel->jobnum = $this->jobnum;
                
                $CompanyRecordModel->mark = $this->mark;
                
                $CompanyRecordModel->companyid = $this->companyid;
                
                $CompanyRecordModel->createtime = time();
                
            }
            
            $CompanyRecordModel->save();
            
            return true;
            
            
        }
        
        
    }