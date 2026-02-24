<?php
    
    namespace addons\zpwxsys\model;
    
    use addons\zpwxsys\model\Job as JobModel;
    use think\Db;
    
    class Company extends BaseModel
    {
        protected $name = 'zpwxsys_company';
        
        public function getCompanyByWhere($map, $Nowpage, $limits, $od)
        {
            $companylist = $this->alias('g')->field('g.id AS id, g.companycate AS companycate, g.companytype AS companytype,g.companyworker AS companyworker,g.companyname AS companyname,g.shortname AS shortname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort,c.id AS cityid, c.name AS cityname,a.name AS areaname,g.thumb AS thumb')->join('zpwxsys_city c', 'g.cityid = c.id')->join('zpwxsys_area a', 'g.areaid = a.id')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            
            $data['from'] = 1;
            
            foreach ($companylist as $k => $v) {
                
                $companylist[$k]['thumb'] = cdnurl($v['thumb'], true);
                
                $companylist[$k]['jobcount'] = JobModel::getCount(array('companyid' => $v['id']));
                
                $companylist[$k]['joblist'] = JobModel::getJobbyCompanyId(array('companyid' => $v['id']));
                
            }
            
            
            return $companylist;
            
        }
        
        public static function getCompany($id)
        {

            $companydetail = self::where('id', '=', $id)->find();

            if (!$companydetail) {
                return null;
            }


            $companydetail['thumb'] = cdnurl($companydetail['thumb'],true);


            $cardimglist = explode(',', $companydetail['cardimg'] ?? '');

            $companyimglist = explode(',', $companydetail['companyimg'] ?? '');
            
            
            
            if ($cardimglist) {
                
                foreach ($cardimglist as $k => $v) {
                    
                    $cardimglist[$k] = cdnurl($v,true);
                    
                    
                }
                
                
            }
            
            if ($companyimglist) {
                
                foreach ($companyimglist as $k => $v) {
                    
                    $companyimglist[$k] = cdnurl($v,true);
                    
                    
                }
                
                
            }
            
            
            $companydetail['cardimg'] = $cardimglist;
            
            $companydetail['companyimg'] = $companyimglist;
            
            
            return $companydetail;
            
        }
        
        public static function getCompanyByuid($uid)
        {
            
            $companyinfo = self::where('uid', '=', $uid)->find();
            
            
            return $companyinfo;
            
            
        }
        
        
        public function insertCompany($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                $companyid = Db::getLastInsID();
                Db::commit();// 提交事务
                
                $data = array('status' => 0,'companyid' => $companyid);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1);
            }
            
            
            return $data;
            
        }
        
        
        public function updateCompany($param)
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
