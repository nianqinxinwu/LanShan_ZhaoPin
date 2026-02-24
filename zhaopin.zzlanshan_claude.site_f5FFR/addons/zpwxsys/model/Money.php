<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class Money extends BaseModel
    {
    
        protected $name = 'zpwxsys_money';
        
        public static function getlist($map)
        {
            
            return self::where($map)->order('createtime DESC')->select();
            
        }
        
        public function getListOrderByWhere($map,$od)
        {
            return $this->alias ('m')
                ->field('m.id AS id, m.money AS money,m.totalmoney AS totalmoney,m.createtime AS createtime,m.content AS content,m.status AS status ,m.type AS type')
                ->join('zpwxsys_user u', 'u.id = m.uid')
                ->where($map)
                ->order($od)
                ->select();
            
        }
        
        
        
        
        
        public function insertMoney($param)
        {
            Db::startTrans();// 启动事务
            try{
                $this->allowField(true)->save($param);
                Db::commit();// 提交事务
                return ['code' => 200, 'data' => '', 'msg' => '添加成功'];
            }catch( \Exception $e){
                
                Db::rollback();// 回滚事务
                return ['code' => 100, 'data' => '', 'msg' =>'添加失败'];
            }
        }
        
        
        public static function getLastOne($map)
        {
            
            return self::where($map)->order('createtime desc')->select();
            
        }
        
    }

