define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/note/index' + location.search,
                    add_url: 'zpwxsys/note/add',
                    edit_url: 'zpwxsys/note/edit',
                    del_url: 'zpwxsys/note/del',
                    multi_url: 'zpwxsys/note/multi',
                    table: 'zpwxsys_note',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('id'), operate:false},
                        {field: 'cityname', title: __('cityid')},
                        {field: 'areaname', title: __('areaid')},
                        {field: 'name', title: __('name')},
                        {field: 'jobtitle', title: __('jobtitle')},
                        {field: 'tel', title: __('tel')},
                        {field: 'address', title: __('address'), operate:false},
                        {field: 'ishidden', title: __('ishidden'), operate:false, formatter: Table.api.formatter.toggle},
                        {field: 'status', title: __('enabled'), operate:false, formatter: Table.api.formatter.toggle},
                        {field: 'createtime', title: __('createtime'),formatter: Table.api.formatter.datetime},

                        
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            
                             buttons: [
                                {
                                    name: 'addedunote',
                                    text: '添加教育经历',
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-plus',
                                    url: 'zpwxsys/note/addedunote/noteid/{ids}'
                                },
                                 {
                                    name: 'lookedunote',
                                    text: '查看教育经历',
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-plus',
                                    url: 'zpwxsys/note/lookedunote/noteid/{ids}'
                                },
                                 {
                                    name: 'addexpressnote',
                                    text: '添加工作经历',
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-plus',
                                    url: 'zpwxsys/note/addexpressnote/noteid/{ids}'
                                }, {
                                    name: 'lookexpressnote',
                                    text: '查看工作经历',
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-plus',
                                    url: 'zpwxsys/note/lookexpressnote/noteid/{ids}'
                                },
                            ]
                            
                        }
                    ]
                ]
            });


            $(document).on("change", ".text-sort", function () {
                $(this).data("params", {sort: $(this).val()});
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        
        
        add: function () {


            $(document).on("change", "#selectCity", function (a) {

                var cityid = $("#selectCity").val();
                // alert(cityid);
                Fast.api.ajax({
                    url: "zpwxsys/note/getarealist",
                    type: "post",
                    data: {cityid: cityid},
                }, function (res) {

                    console.log(res);
                    var list = res;
                    $("#areaid").empty();
                    $("#areaid").append("<option value='0'>请选择地区</option>");
                    $.each(list, function (index) {
                        //循环获取数据
                        console.log(list[index].name);

                        $("#areaid").append("<option value="+list[index].id+">"+list[index].name+"</option>");
                    });


                });
            });


            Controller.api.bindevent();





        },
        addedunote:function(){
            
             Controller.api.bindevent();
        },
        lookedunote:function(){
            
            
             var noteid = $('#noteid').val();
             // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/note/lookedunote' + location.search+'&noteid='+noteid,
                    del_url: 'zpwxsys/note/delEdunote',
                    multi_url: 'zpwxsys/note/multi',
                    table: 'zpwxsys_note',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                         {field: 'id', title: 'ID', operate:false},
                        {field: 'begindateschool', title: '入学时间'},
                        {field: 'enddateschool', title: '毕业时间'},
                        {field: 'school', title: '毕业学校'},
                        {field: 'vocation', title: '学历'},
                        {field: 'educationname', title:'专业'},
                       
                        
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate
                            
                     
                            
                        }
                    ]
                ]
            });


            $(document).on("change", ".text-sort", function () {
                $(this).data("params", {sort: $(this).val()});
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });
            
            
            
            
            
             Controller.api.bindevent();
        },
        
        
        lookexpressnote:function(){
            
            
             var noteid = $('#noteid').val();
             
             // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/note/lookexpressnote' + location.search+'&noteid='+noteid,
                    del_url: 'zpwxsys/note/delExpressnote',
                    multi_url: 'zpwxsys/note/multi',
                    table: 'zpwxsys_note',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                         {field: 'id', title: 'ID', operate:false},
                        {field: 'begindatejob', title: '入职时间'},
                        {field: 'enddatejob', title: '离职时间'},
                        {field: 'companyname', title: '公司'},
                        {field: 'jobtitle', title: '职位'},

                        
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate
                            
                        }
                    ]
                ]
            });


            $(document).on("change", ".text-sort", function () {
                $(this).data("params", {sort: $(this).val()});
                Table.api.multi('', [$(this).data("id")], table, this);
                return false;
            });
            
            
            
            
            
             Controller.api.bindevent();
        },
        
        addexpressnote:function(){
            
             Controller.api.bindevent();
        },
    
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});