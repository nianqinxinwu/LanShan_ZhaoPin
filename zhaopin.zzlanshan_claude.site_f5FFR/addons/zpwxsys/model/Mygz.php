<?php
    
    namespace addons\zpwxsys\model;
    

    use think\Db;
    
    class Mygz extends BaseModel
    {
        
        protected $name = 'zpwxsys_mygz';
        
        public static function getMygzWhere($map)
        {
            
            $mygz = self::where($map)->find();
            
            return $mygz;
            
        }
        
        public function delMygz($map)
        {
            
            
            Db::startTrans();// 启动事务
            try {
                $this->where($map)->delete();
                Db::commit();// 提交事务
                return true;
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                return false;
            }
            
            
        }
        
        
        public function myGzSave($param)
        {
            
            Db::startTrans();// 启动事务
            try {
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                $data = array('status' => 0, 'msg' => '关注成功');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1, 'msg' => '关注失败');
            }
            
            
            return json_encode($data);
            
        }
        
        public function getGzCompanyList($map, $Nowpage, $limits, $od)
        {
            $mygzlist = $this->alias('r')->field('r.id AS id,m.id AS companyid, m.companycate AS companycate, m.companytype AS companytype,m.companyworker AS companyworker,m.companyname AS companyname,m.shortname AS shortname,m.mastername AS mastername ,a.name AS areaname,r.createtime AS createtime,m.thumb AS thumb ')
                ->join('zpwxsys_company m', 'm.id = r.companyid')
                ->join('zpwxsys_area a', 'a.id = m.areaid')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($od)
                ->select();
            
               foreach ($mygzlist as $k => $v) {
                
                $mygzlist[$k]['thumb'] = cdnurl($v['thumb'], true);
                
             $mygzlist[$k]['createtime'] =  date('Y-m-d',$v['createtime']); 
                
            }
            
            return $mygzlist;
        }
        
        
    }
