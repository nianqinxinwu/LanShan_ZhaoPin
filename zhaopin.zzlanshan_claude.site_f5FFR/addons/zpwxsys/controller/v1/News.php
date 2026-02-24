<?php
    
    
    namespace addons\zpwxsys\controller\v1;
    
    use addons\zpwxsys\controller\BaseController;
    use addons\zpwxsys\model\Cate as CateModel;
    use addons\zpwxsys\model\News as NewsModel;
    use addons\zpwxsys\model\Sysinit as SysinitModel;
    use addons\zpwxsys\validate\IDMustBePositiveInt;
    use addons\zpwxsys\lib\exception\MissException;

    
    class News extends BaseController
    {
       
        public function getNewslist()
        {
            
            
            $od = "sort desc, createtime desc";
            $map['status'] = 1;
            $map['type'] = 0;
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
    
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $NewsModel = new NewsModel();
            $newslist = $NewsModel->getNewsByWhere($map, $Nowpage, $limits, $od);
            
            
            $CateModel = new CateModel();
            
            $catelist = $CateModel->getCatelist();
            
            
            $data = array('newslist' => $newslist, 'catelist' => $catelist
            
            );
            
            
            return json_encode($data);
            
            
        }
        
        public function getnewslistByCateid()
        {
            
            $cateid = input('post.cateid');
            $od = "sort desc createtime desc";
            $map['status'] = 1;
            $map['type'] = 0;
            $map['cateid'] = $cateid;
            $Nowpage = input('post.page');
            if ($Nowpage) $Nowpage = $Nowpage; else
                $Nowpage = 1;
    
            $limits = $Nowpage * 10;
            $Nowpage = 1;
            
            
            $NewsModel = new NewsModel();
            $newslist = $NewsModel->getNewsByWhere($map, $Nowpage, $limits, $od);
            
            
            $data = array('newslist' => $newslist);
            
            
            return json_encode($data);
            
            
        }
        
        
        public function getNewsdetail()
        {
            
            
            $id = input('post.id');
            $validate = new IDMustBePositiveInt();
            $validate->goCheck();
            
            
            $map = array('id' => $id);
            
            $NewsModel = new NewsModel();
            
            
            $newsinfo = $NewsModel->getNewsDetail($map);
            
            if (!$newsinfo) {
                  return json_encode(['status'=>1,'msg'=>'请求数据不存在']);
            }
            
            
            $data = array('newsinfo' => $newsinfo,'status'=>0,'msg'=>'请求数据正常');
            
            return json_encode($data);
        }
    
    
        public function getFxRule()
        {
    
            $sysinfo = SysinitModel::getSysinfo();
        
            $data = array('sysinfo' => $sysinfo);
        
            return json_encode($data);
        }
        
        
        
        
        
    }