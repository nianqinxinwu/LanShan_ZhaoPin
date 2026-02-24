<?php
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\City as CityModel;
    
    /*
     * 城市管理接口
     */
    
    class City extends BaseController
    {
        /*
         * 小程序端获取开通城市列表
         * 按字母排序展示
         * return array
         *  citylist
         *  hotlist
         */
        public function getCityList()
        {
            
            
            $hot_od = "sort desc";
            $hot_map['enabled'] = 1;
            $hot_map['ishot'] = 1;
            
            $CityModel = new CityModel();
            
            $hotlist = $CityModel->getCityByWhere($hot_map, $hot_od);
            
            
            $od = "firstname,sort desc";
            //$od = '';
            $group = 'firstname';
            
            $map['enabled'] = 1;
            $citylist = $CityModel->getCityGroupByWhere($map, $od, $group);
            
            if ($citylist) {
                
                foreach ($citylist as $k => $v) {
                    $gmap['enabled'] = 1;
                    $gmap['firstname'] = $v['firstname'];
                    
                    $god = "sort desc";
                    
                    $god = '';
                    
                    $list = $CityModel->getCityByWhere($gmap, $god);
                    
                    $citylist[$k]['firstlist'] = $list;
                    
                }
                
            }
            
            
            $data = array('hotlist' => $hotlist, 'citylist' => $citylist,
            
            );
            
            
            return json_encode($data);
            
            
        }
        
        
    }