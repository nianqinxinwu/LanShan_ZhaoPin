<?php
    
    namespace app\admin\model\zpwxsys;
    
    use think\Model;
    use think\Db;
    
    class Activerecord extends Model
    {
        protected $name = 'zpwxsys_activerecord';
        
        // 开启自动写入时间戳字段
        protected $autoWriteTimestamp = true;
        
        /**
         * 根据搜索条件获取列表信息
         * @author
         */
        public function getListByWhere($map, $Nowpage, $limits, $od)
        {
            
            return $this->alias('r')->field('r.id AS id,r.createtime AS createtime,a.title AS title,g.companyname AS companyname ')->join('zpwxsys_active a', 'a.id = r.aid')->join('zpwxsys_company g', 'g.id = r.companyid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
        }
        
        public function getCount($map)
        {
            
            return $this->alias('r')->field('r.id AS id,r.createtime AS createtime,a.title AS title,g.companyname AS companyname ')->join('zpwxsys_active a', 'a.id = r.aid')->join('zpwxsys_company g', 'g.id = r.companyid')->where($map)->count();
            
        }
        
        public static function getRecordCount($map)
        {
            
            return self::where($map)->count();
        }
        
   
        
        
  
        
        
   
        
        

        
        
    }