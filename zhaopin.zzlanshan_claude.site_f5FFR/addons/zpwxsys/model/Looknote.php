<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Looknote extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_looknote';
        
        public function getListByWhere($map, $Nowpage, $limits, $od)
        {
            $list = $this->alias('k')->field('k.id AS id, g.companycate AS companycate, g.companytype AS companytype,g.companyworker AS companyworker,g.companyname AS companyname,g.shortname AS shortname,g.mastername AS mastername, g.tel AS tel,g.address AS address,g.createtime AS createtime,g.sort AS sort,g.thumb AS thumb,k.createtime AS createtime,g.id AS companyid ')->join('zpwxsys_company g', 'g.id = k.companyid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            
            $data['from'] = 1;
            
            foreach ($list as $k => $v) {
                
                $list[$k]['thumb'] = cdnurl($v['thumb'], true);
                
                $list[$k]['createtime'] = date('Y-m-d', $v['createtime']);
            }
            
            
            return $list;
            
        }
        
        
        public static function getOne($map)
        {
            
            $jobrecord = self::where($map)->find();
            
            
            return $jobrecord;
            
        }
        
        
        public function saveRecord($param)
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
