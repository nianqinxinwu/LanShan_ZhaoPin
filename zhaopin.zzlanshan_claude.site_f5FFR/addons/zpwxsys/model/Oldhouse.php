<?php

namespace addons\zpwxsys\model;

use think\Model;
use think\Db;

class Oldhouse extends BaseModel
{
   
   
   
   protected $name = 'zpwxsys_oldhouse';
   
 
   
    public  function getNearHouseByWhere($map, $Nowpage, $limits,$od,$latitude,$longitude)
    {
                $list =  $this->alias ('h')
            ->field('h.id AS id,h.title AS title,h.area AS area,h.lat AS lat,h.lng AS lng, h.direction AS direction,h.floor1 AS floor1,h.floor2 AS floor2,h.address AS address, h.money AS money,h.status AS status, h.name AS name,h.updatetime AS updatetime,h.createtime AS createtime, h.enabled AS enabled,h.housetype AS housetype,c.id AS cityid, c.name AS cityname,a.name AS areaname,h.thumb AS thumb, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ' . $latitude . '  * PI() / 180 - h.lat * PI() / 180) / 2),2) + COS(' . $latitude . ' * PI() / 180) * COS(h.lat * PI() / 180) * POW(SIN(( ' . $longitude . ' * PI() / 180 - h.lng * PI() / 180) / 2),2))) * 1000)  AS  distance')
            ->join('zpwxsys_city c', 'h.cityid = c.id','left')
            ->join('zpwxsys_area a', 'h.areaid = a.id','left')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
            
            
          
              
                  $typelist = array(0=>'住宅',1=>'商铺',2=>'公寓');
        
                foreach ($list as $k =>$v)
                
                {
                    
                
                    $list[$k]['thumb'] =cdnurl($v['thumb'],true);
                    
                    
                   $list[$k]['typename'] = $typelist[$v['type']]; 

                          
                     $list[$k]['distance'] = round($list[$k]['distance'] /1000,2);
                }
                    
                    
            
            
            return  $list;

    }
    
   
   
   public  function getHouseByWhere($map, $Nowpage, $limits,$od)
    {
                $list =  $this->alias ('h')
            ->field('h.id AS id,h.title AS title,h.area AS area,h.lat AS lat,h.lng AS lng, h.direction AS direction,h.floor1 AS floor1,h.floor2 AS floor2,h.address AS address, h.money AS money,h.status AS status, h.name AS name,h.updatetime AS updatetime,h.createtime AS createtime, h.enabled AS enabled,h.housetype AS housetype,c.id AS cityid, c.name AS cityname,a.name AS areaname,h.thumb AS thumb,h.type AS type')
            ->join('zpwxsys_city c', 'h.cityid = c.id','left')
            ->join('zpwxsys_area a', 'h.areaid = a.id','left')
            ->where($map)
            ->page($Nowpage, $limits)
            ->order($od)
            ->select();
            
            

                  $typelist = array(0=>'住宅',1=>'商铺',2=>'公寓');
        
                foreach ($list as $k =>$v)
                {
                    $list[$k]['typename'] = $typelist[$v['type']]; 
                    $list[$k]['thumb'] =cdnurl($v['thumb'],true);
                }
                    
                    
            
            
            return  $list;

    }
    
    
    
    
    
    
   
    
    
    
    
       public static function getHouse($id)
    {
        
          $housedetail = self::where('id', '=', $id)->find();
          
           $housedetail['thumb'] = cdnurl($housedetail['thumb'],true);
           
           if($housedetail['videourl']!='')
                $housedetail['videourl'] = cdnurl($housedetail['videourl'],true);
       $piclist = explode(',',$housedetail['thumb_url']);
       
        $speciallist =   explode(',',$housedetail['special']);
        
          $housedetail['speciallist'] = $speciallist;
          
          
       
       if($piclist)
       {
       
               foreach ($piclist as $k=>$v)
               {
                   
                    $piclist[$k] = self::prefixImgUrl($v,$data);
                   
                   
               }
               
      
           
       }
             $typelist = array(0=>'住宅',1=>'商铺',2=>'公寓');
       
         $housedetail['typename'] = $typelist[$housedetail['type']];
 
      $housedetail['piclist'] = $piclist; 
          
        return $housedetail;
      
    }
    
    public static function getCompanyByuid($uid)
    {
        
        $companyinfo = self::where('uid', '=', $uid)->find();
        
        
     
        return $companyinfo;
        
        
    }
    
    
  
    
    
       public  function insertHouse($param)
    {
        
         Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param);
            Db::commit();// 提交事务
            $data = array('status'=>0);
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
         
           $data = array('status'=>1);
        }
        
        
        return json_encode($data);
      
    }
    
          public function updateHouse($param)
    {
        
         Db::startTrans();// 启动事务
        try{
           
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            $data = array('status'=>0);
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            
         
           $data = array('status'=>1);
        }
        
        return json_encode($data);
        
        
    }
    
    
     public function delHouse($param)
    {
        $title = $this->where($param)->value('title');
        Db::startTrans();// 启动事务
        try{
            $this->where($param)->delete();
            Db::commit();// 提交事务

            $data = array('status'=>0, 'msg' => '删除成功');
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            $data = array('status'=>1, 'msg' => '删除失败');

        }
        return json_encode($data);
        
    }
    
    
    
    
    
    


}
