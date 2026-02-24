<?php
    
    namespace addons\zpwxsys\model;
    

    use think\Db;
    
    class Note extends BaseModel
    {
        
        protected $name = 'zpwxsys_note';
        
        public function getNoteByWhere($map, $Nowpage, $limits, $od)
        {
            $notelist = $this->alias('n')->field('n.id AS id,n.status AS status,n.name AS name ,n.avatarUrl AS avatarUrl ,n.jobtitle AS jobtitle,c.name AS cityname,j.name AS jobcatename,n.education AS education,n.express AS express,n.createtime AS createtime,n.refreshtime AS refreshtime,n.sex AS sex, n.tel AS tel,n.address AS address , n.birthday AS birthday,n.jobcateid AS jobcateid ')->join('zpwxsys_city c', 'c.id = n.cityid')->join('zpwxsys_jobcate j', 'j.id = n.jobcateid')->where($map)->page($Nowpage, $limits)->order($od)->select();
            
            $data['from'] = 1;
            
            foreach ($notelist as $k => $v) {
                
                
                $notelist[$k]['refreshtime'] = $this->time_tran($v['refreshtime']);
                
                $notelist[$k]['age'] = date('Y') - $v['birthday'];
                
                
                if ($v['avatarUrl'] == '') {
                    
                    
                    $notelist[$k]['avatarUrl'] = '../../imgs/icon/male' . $v['sex'] . '.png';
                    
                    
                } else {
                    
                    $notelist[$k]['avatarUrl'] = cdnurl($v['avatarUrl'], true);
                    
                }
                
                
            }
            
            
            return $notelist;
            
        }
    
    
    
        public static function getNoteMap($map)
        {
        
            $notedetail = self::where($map)->find();
        
        
        
            if ($notedetail['avatarUrl'] == '') {
            
            
                $notedetail['avatarUrl'] = '../../imgs/icon/male' . $notedetail['sex'] . '.png';
            
            
            } else {
            
                $notedetail['avatarUrl'] = cdnurl($notedetail['avatarUrl'], true);
            
            }
        
        
            return $notedetail;
        
        }
        
        
        
        
        public static function getNote($id)
        {
            
            $notedetail = self::where('id', '=', $id)->find();
            
            
     
            
            
            if ($notedetail['avatarUrl'] == '') {
                
                
                $notedetail['avatarUrl'] = '../../imgs/icon/male' . $notedetail['sex'] . '.png';
                
                
            } else {
                
                $notedetail['avatarUrl'] = cdnurl($notedetail['avatarUrl'], true);
                
            }
            
            
            return $notedetail;
            
        }
        
        public static function getNoteByuid($uid)
        {
            
            $notedetail = self::where('uid', '=', $uid)->find();
            
            
            $data['from'] = 1;
            
            if ($notedetail) {
                if ($notedetail['avatarUrl'] == '') {
                    
                    
                    $notedetail['avatarUrl'] = '../../imgs/icon/male' . $notedetail['sex'] . '.png';
                    
                    
                } else {
                    
                    $notedetail['avatarUrl'] = cdnurl($notedetail['avatarUrl'], true);;
                    
                }
    
                $notedetail['age'] = date('Y') - $notedetail['birthday'];
                
            }
            return $notedetail;
            
        }
        
        public function insertNote($param)
        {
            
            
            Db::startTrans();// 启动事务
            try {
                
                $this->allowField(true)->save($param);
                
                $id = DB::getLastInsID();
                
                Db::commit();// 提交事务
                $data = array('status' => 0, 'id' => $id);
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                
                $data = array('status' => 1);
            }
            
            
            return json_encode($data);
            
            
        }
        
        
        public function updateNote($param)
        {
            Db::startTrans();// 启动事务
            try {
                
                $this->allowField(true)->save($param, ['id' => $param['id']]);
                Db::commit();// 提交事务
                $data = array('status' => 0,'msg'=>'刷新成功');
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                $data = array('status' => 1,'msg'=>'刷新失败');
            }
            
            return json_encode($data);
        }
        
        
    }
