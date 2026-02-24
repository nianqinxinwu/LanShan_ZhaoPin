<?php
    
    namespace addons\zpwxsys\model;
    
    use think\Db;
    
    class User extends BaseModel
    {
        protected $autoWriteTimestamp = true;
//    protected $createTime = ;
        protected $name = 'zpwxsys_user';
        
        public function orders()
        {
            return $this->hasMany('Order', 'user_id', 'id');
        }
        
        public function address()
        {
            return $this->hasOne('UserAddress', 'user_id', 'id');
        }
        
        
        public function updateUser($param)
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
        
        /**
         * 用户是否存在
         * 存在返回uid，不存在返回0
         */
        public static function getByOpenID($openid)
        {
            $user = User::where('openid', '=', $openid)->find();
            return $user;
        }
        
        public static function getByUserWhere($map)
        {
            $user = User::where($map)->find();
            if($user['avatarUrl']!='')
            {
                $user['avatarUrl'] = cdnurl($user['avatarUrl'], true);
            }
            
            
            if($user['qrcode']!='')
            {
                $user['qrcode'] = cdnurl($user['qrcode'], true);
            }
            
            
            
            return $user;
        }
        
        
        public static function getByUserlistWhere($map, $Nowpage, $limits, $od)
        {
            
            $userlist = user::where($map)->order($od)->select();
          
            
            return $userlist;
        }
        
        
          /**
     * 确保用户表存在 login_days / last_login_date 字段（幂等）
     */
    public static function ensureProfileFields()
    {
        $table = config('database.prefix') . 'zpwxsys_user';
        $columns = Db::query("SHOW COLUMNS FROM `{$table}` LIKE 'login_days'");
        if (empty($columns)) {
            Db::execute("ALTER TABLE `{$table}` ADD COLUMN `last_login_date` DATE DEFAULT NULL, ADD COLUMN `login_days` INT DEFAULT 0");
        }
    }

    /**
     * 确保用户表存在 user_role 字段（幂等）
     * user_role: 0=未选择, 1=报名者, 2=发布者
     */
    public static function ensureUserRoleField()
    {
        $table = config('database.prefix') . 'zpwxsys_user';
        $columns = Db::query("SHOW COLUMNS FROM `{$table}` LIKE 'user_role'");
        if (empty($columns)) {
            Db::execute("ALTER TABLE `{$table}` ADD COLUMN `user_role` tinyint(4) DEFAULT 0 COMMENT '0=未选择,1=报名者,2=发布者'");
        }
    }

    /**
     * 更新连续登录天数，返回当前天数
     */
    public static function refreshLoginDays($uid)
    {
        self::ensureProfileFields();

        $today = date('Y-m-d');
        $user = Db::name('zpwxsys_user')->where('id', $uid)->field('last_login_date,login_days')->find();

        if (!$user) {
            return 1;
        }

        $lastDate = $user['last_login_date'];
        $days = intval($user['login_days']);

        if ($lastDate === $today) {
            // 今天已经更新过
            return max($days, 1);
        }

        $yesterday = date('Y-m-d', strtotime('-1 day'));
        if ($lastDate === $yesterday) {
            $days += 1;
        } else {
            $days = 1;
        }

        Db::name('zpwxsys_user')->where('id', $uid)->update([
            'last_login_date' => $today,
            'login_days' => $days
        ]);

        return $days;
    }

    public function getLevelAgentList($map)
    {
            $list =  $this->alias ('u')
            ->field('u.id AS id,u.nickname AS name ,u.tel AS tel,u.create_time AS create_time,u.avatarUrl AS avatarUrl ')
            
            //->join('agent a', 'a.uid = u.id','left')
            ->where($map)
            ->order('u.create_time desc')
            ->select();
            $data['from'] = 1;
            if($list)
            {
                foreach ($list as $k=>$v)
                {
                    
                   if($v['name'] == '')
                   {
                       
                        $list[$k]['name']='新用户';
                   }
                    
                   $list[$k]['avatarUrl'] =  self::prefixImgUrl($v['avatarUrl'],$data);
                }
            }
            
            return $list;

    }
    
    
        
    }
