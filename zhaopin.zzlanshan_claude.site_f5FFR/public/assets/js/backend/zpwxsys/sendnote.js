define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/sendnote/index' + location.search,
                    add_url: 'zpwxsys/sendnote/add',
                    edit_url: 'zpwxsys/sendnote/edit',
                    del_url: 'zpwxsys/sendnote/del',
                    multi_url: 'zpwxsys/sendnote/multi',
                    table: 'zpwxsys_jobrecord',
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
                        {field: 'agentname', title: '所属经纪人'},
                        
                        {field: 'tasktitle', title: '任务标题'},
                        {field: 'money', title: '任务佣金(元)'},

                        {field: 'name', title: '求职者'},
                        {field: 'tel', title: '求职者电话'},
                        {field: 'jobtitle', title: '应聘职位'},
                        {field: 'companyname', title: '应聘公司'},
                        {field: 'status', title:'状态', operate:false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,buttons: [
                                {
                                    name: 'dealMoney',
                                    text: '确认分配佣金',
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-plus',
                                    url: 'zpwxsys/sendnote/dealmoney/id/{ids}'
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
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        dealmoney:function(){
            
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