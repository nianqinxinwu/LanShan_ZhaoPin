<?php
    
    namespace addons\zpwxsys\model;
    
    
    use think\Db;
    
    class Comment extends BaseModel
    {
        
        
        protected $name = 'zpwxsys_comment';
    
        public function getCommentByWhere($map, $Nowpage, $limits, $od)
        {
            $commentlist = $this->alias('c')
                ->field('g.companyname AS companyname,c.id AS id,c.content AS content,c.companyid AS companyid,c.score AS score,c.piclist AS piclist,c.create_time AS create_time,u.nickname AS nickname,u.avatarUrl AS avatarUrl')
                ->join('zpwxsys_user u', 'u.id = c.uid')
                ->join('zpwxsys_company g', 'g.id = c.companyid')
                ->where($map)
                ->page($Nowpage, $limits)
                ->order($od)
                ->select();
            
        
            foreach ($commentlist as $k => $v) {

                $commentlist[$k]['avatarUrl'] = cdnurl($v['avatarUrl'], true);
                $commentlist[$k]['create_time'] = date('Y-m-d',$v['create_time']);
                if (!empty($v['piclist'])) {
                    $picArr = explode(',', $v['piclist']);
                    foreach ($picArr as $pk => $pv) {
                        $picArr[$pk] = cdnurl($pv, true);
                    }
                    $commentlist[$k]['piclist'] = $picArr;
                } else {
                    $commentlist[$k]['piclist'] = [];
                }

            }


            return $commentlist;

        }


        public function getCommentList($map,$od)
        {
            $commentlist = $this->alias('c')
                ->field('c.id AS id,c.content AS content,c.companyid AS companyid,c.score AS score,c.create_time AS create_time,u.nickname AS nickname,u.avatarUrl AS avatarUrl')
                ->join('zpwxsys_user u', 'u.id = c.uid','left')
                ->where($map)
                ->order($od)
                ->select();
        
        
            foreach ($commentlist as $k => $v) {
            
                $commentlist[$k]['avatarUrl'] = cdnurl($v['avatarUrl'], true);
                $commentlist[$k]['create_time'] = date('Y-m-d',$v['create_time']);
            
            
            }
        
        
            return $commentlist;
        
        }
        
        public function insertComment($param)
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
            
            return $data;
            
            
        }
        
        public function delComment($map)
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
        

        
        
   
        
        
    }

