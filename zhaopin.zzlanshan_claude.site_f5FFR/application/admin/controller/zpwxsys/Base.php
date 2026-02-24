<?php
    
    namespace app\admin\controller\Zpwxsys;
    
    use app\common\controller\Backend;
    
    /**
     * 系统配置
     *
     *
     */
    class Base extends Backend
    {
        protected $model = null;
        protected $noNeedRight = ['*'];
        protected $site_id = 0;
        
        public function _initialize()
        {
            parent::_initialize();
        }
    }