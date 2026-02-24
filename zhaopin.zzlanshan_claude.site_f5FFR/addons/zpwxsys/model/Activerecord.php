<?php
    
    namespace addons\zpwxsys\model;
    
    use addons\zpwxsys\model\Job as JobModel;
    use think\Db;
    
    class Activerecord extends BaseModel
    {
        protected $name = 'zpwxsys_activerecord';
        
        public function getActiverecordByWhere($map, $Nowpage, $limits, $od)
        {
            $list = $this->alias('a')->field('a.id AS id, g.id AS companyid,g.companyname AS companyname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort,g.thumb AS thumb')->join('zpwxsys_company g', 'g.id = a.companyid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            
            $data['from'] = 1;
            
            foreach ($list as $k => $v) {
                
                $list[$k]['thumb'] = self::prefixImgUrl($v['thumb'], $data);
                
                $list[$k]['jobcount'] = JobModel::getCount(array('companyid' => $v['companyid']));
                
                $joblist = JobModel::getJobbyCompanyId(array('companyid' => $v['companyid']));
                
                foreach ($joblist as $k2 => $v2) {
                    
                    $joblist[$k2]['jobtitle'] = mb_substr($v2['jobtitle'], 0, 4);
                }
                
                $list[$k]['joblist'] = $joblist;
                
            }
            
            
            return $list;
            
        }
        
        public static function getOne($map)
        {
            
            return self::where($map)->find();
        }
        
        
        public function insertActiverecord($param)
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
        
    }