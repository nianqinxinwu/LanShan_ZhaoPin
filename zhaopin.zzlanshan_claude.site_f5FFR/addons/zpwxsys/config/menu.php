<?php
/**
 * 菜单配置文件
 */

return [
    [
        'type' => 'file',
        'name' => 'zpwxsys',
        'title' => '招聘系统',
        'icon' => 'fa fa-list',
        'condition' => '',
        'remark' => '',
        'ismenu' => 1,
        'sublist' => [


        	[
                'type' => 'file',
                'name' => 'zpwxsys/sysinit',
                'title' => '系统设置',
                'icon' => 'fa fa-cogs',
                'weigh'   => 20,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/sysinit/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/sysinit/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/sysinit/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/sysinit/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/city',
                'title' => '城市管理',
                'icon' => 'fa fa-location-arrow',
                'weigh'   => 19,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/city/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/city/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/city/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/city/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],


            [
                'type' => 'file',
                'name' => 'zpwxsys/area',
                'title' => '区域管理',
                'icon' => 'fa fa-compass',
                'weigh'   => 18,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/area/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/area/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/area/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/area/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],



            [
                'type' => 'file',
                'name' => 'zpwxsys/companyinfo',
                'title' => '企业管理',
                'icon' => 'fa fa-navicon',
                'weigh'   => 17,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                
                        [
                            'name'    => 'zpwxsys/company',
                            'title'   => '企业列表管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 4,
                            'sublist' => [
                                ['name' => 'zpwxsys/company/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/company/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/company/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/company/del', 'title' => '删除'],
                                ['name' => 'zpwxsys/company/getarealist', 'title' => '获取区域'],
                            ]
                        ],


                        [
                            'name'    => 'zpwxsys/companyrole',
                            'title'   => '企业套餐管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 3,
                            'sublist' => [
                                ['name' => 'zpwxsys/companyrole/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/companyrole/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/companyrole/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/companyrole/del', 'title' => '删除'],
                            ]
                        ],


                        [
                            'name'    => 'zpwxsys/companyaccount',
                            'title'   => '企业账号管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 2,
                            'sublist' => [
                                ['name' => 'zpwxsys/companyaccount/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/companyaccount/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/companyaccount/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/companyaccount/del', 'title' => '删除'],
                            ]
                        ],

                        [
                            'name'    => 'zpwxsys/comment',
                            'title'   => '评论管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 1,
                            'sublist' => [
                                ['name' => 'zpwxsys/comment/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/comment/del', 'title' => '删除'],
                            ]
                        ],
                
                  
                ],
            ],


            [
                'type' => 'file',
                'name' => 'zpwxsys/job',
                'title' => '职位管理',
                'icon' => 'fa fa-sitemap',
                'weigh'   => 16,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/job/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/job/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/job/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/job/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/note',
                'title' => '简历管理',
                'icon' => 'fa fa-group',
                'weigh'   => 15,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/getarealist',
                        'title' => '获取区域',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/addedunote',
                        'title' => '添加教育经历',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/lookedunote',
                        'title' => '查看教育经历',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/addexpressnote',
                        'title' => '添加工作经历',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/note/lookexpressnote',
                        'title' => '查看工作经历',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],


            [
                'type' => 'file',
                'name' => 'zpwxsys/jobrecord',
                'title' => '应聘管理',
                'icon' => 'fa fa-map',
                'weigh'   => 14,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/jobrecord/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/jobrecord/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/task',
                'title' => '任务管理',
                'icon' => 'fa fa-group',
                'weigh'   => 13,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/task/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/task/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/task/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    
                  
                ],
            ],


            [
                'type' => 'file',
                'name' => 'zpwxsys/agentinfo',
                'title' => '经纪人与分销管理',
                'icon' => 'fa fa-navicon',
                'weigh'   => 12,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                
                        [
                            'name'    => 'zpwxsys/agent',
                            'title'   => '经纪人列表管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 5,
                            'sublist' => [
                                ['name' => 'zpwxsys/agent/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/agent/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/agent/del', 'title' => '删除'],
                            ]
                        ],

                        [
                            'name'    => 'zpwxsys/sendnote',
                            'title'   => '派遣人才管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 4,
                            'sublist' => [
                                ['name' => 'zpwxsys/sendnote/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/sendnote/del', 'title' => '删除'],
                            ]
                        ],

                        [
                            'name'    => 'zpwxsys/fxuser',
                            'title'   => '推荐用户管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 3,
                            'sublist' => [
                                ['name' => 'zpwxsys/fxuser/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/fxuser/del', 'title' => '删除'],
                            ]
                        ],


                        [
                            'name'    => 'zpwxsys/fxnote',
                            'title'   => '推荐人才库管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 2,
                            'sublist' => [
                                ['name' => 'zpwxsys/fxnote/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/fxnote/del', 'title' => '删除'],
                            ]
                        ],

                        [
                            'name'    => 'zpwxsys/fxrecord',
                            'title'   => '分佣金记录管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 2,
                            'sublist' => [
                                ['name' => 'zpwxsys/fxrecord/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/fxrecord/del', 'title' => '删除'],
                            ]
                        ],

                
                  
                ],
            ],



            [
                'type' => 'file',
                'name' => 'zpwxsys/setting',
                'title' => '自定义管理',
                'icon' => 'fa fa-navicon',
                'weigh'   => 11,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                        [
                            'name'    => 'zpwxsys/nav',
                            'title'   => '自定义导航管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 6,
                            'sublist' => [
                                ['name' => 'zpwxsys/nav/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/nav/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/nav/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/nav/del', 'title' => '删除'],
                            ]
                        ],
                
                        [
                            'name'    => 'zpwxsys/jobcate',
                            'title'   => '行业分类管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 5,
                            'sublist' => [
                                ['name' => 'zpwxsys/jobcate/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/jobcate/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/jobcate/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/jobcate/del', 'title' => '删除'],
                            ]
                        ],


                        [
                            'name'    => 'zpwxsys/jobprice',
                            'title'   => '薪资筛选管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 4,
                            'sublist' => [
                                ['name' => 'zpwxsys/jobprice/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/jobprice/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/jobprice/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/jobprice/del', 'title' => '删除'],
                            ]
                        ],


                        [
                            'name'    => 'zpwxsys/lookrole',
                            'title'   => '简历包标签管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 3,
                            'sublist' => [
                                ['name' => 'zpwxsys/lookrole/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/lookrole/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/lookrole/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/lookrole/del', 'title' => '删除'],
                            ]
                        ],

                        [
                            'name'    => 'zpwxsys/worktype',
                            'title'   => '工作性质管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 2,
                            'sublist' => [
                                ['name' => 'zpwxsys/worktype/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/worktype/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/worktype/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/worktype/del', 'title' => '删除'],
                            ]
                        ],

                         [
                            'name'    => 'zpwxsys/current',
                            'title'   => '目前状态管理',
                            'icon'    => 'fa fa-table',
                            'ismenu'  => 1,
                            'weigh'   => 1,
                            'sublist' => [
                                ['name' => 'zpwxsys/current/index', 'title' => '查看'],
                                ['name' => 'zpwxsys/current/add', 'title' => '添加'],
                                ['name' => 'zpwxsys/current/edit', 'title' => '编辑'],
                                ['name' => 'zpwxsys/current/del', 'title' => '删除'],
                            ]
                        ],


                
                  
                ],
            ],


        

            [
                'type' => 'file',
                'name' => 'zpwxsys/adv',
                'title' => '幻灯片管理',
                'icon' => 'fa fa-picture-o',
                'weigh'   => 10,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/adv/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/adv/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/adv/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/adv/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],


            [
                'type' => 'file',
                'name' => 'zpwxsys/active',
                'title' => '招聘会管理',
                'icon' => 'fa fa-bank',
                'weigh'   => 9,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/active/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/active/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/active/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/active/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/activerecord',
                'title' => '招聘会报名管理',
                'icon' => 'fa fa-bank',
                'weigh'   => 8,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/activerecord/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/activerecord/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

         [
                'type' => 'file',
                'name' => 'zpwxsys/cate',
                'title' => '资讯分类',
                'icon' => 'fa fa-cloud',
                'weigh'   => 7,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/cate/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/cate/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/cate/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/cate/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],
            [
                'type' => 'file',
                'name' => 'zpwxsys/news',
                'title' => '资讯管理',
                'icon' => 'fa fa-newspaper-o',
                'weigh'   => 6,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/news/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/news/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/news/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/news/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/notice',
                'title' => '公告管理',
                'icon' => 'fa fa-newspaper-o',
                'weigh'   => 5,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/notice/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/notice/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/notice/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/notice/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/user',
                'title' => '用户管理',
                'icon' => 'fa fa-user',
                'weigh'   => 4,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/user/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/user/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/user/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/user/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],



            [
                'type' => 'file',
                'name' => 'zpwxsys/order',
                'title' => '订单管理',
                'icon' => 'fa fa-arrows',
                'weigh'   => 3,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/order/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/order/add',
                        'title' => '添加',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/order/edit',
                        'title' => '编辑',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/order/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],


            [
                'type' => 'file',
                'name' => 'zpwxsys/ploite',
                'title' => '举报信息管理',
                'icon' => 'fa fa-film',
                'weigh'   => 2,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/ploite/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                 
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/ploite/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],                  
                ],
            ],

            [
                'type' => 'file',
                'name' => 'zpwxsys/money',
                'title' => '提现管理',
                'icon' => 'fa fa-map',
                'weigh'   => 14,
                'condition' => '',
                'remark' => '',
                'ismenu' => 1,
                'sublist' => [
                   
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/money/index',
                        'title' => '查看',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                    [
                        'type' => 'file',
                        'name' => 'zpwxsys/money/del',
                        'title' => '删除',
                        'icon' => 'fa fa-circle-o',
                        'condition' => '',
                        'remark' => '',
                        'ismenu' => 0,
                    ],
                  
                ],
            ],




            
        ],
    ],
];