define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/agent/index' + location.search,
                    add_url: 'zpwxsys/agent/add',
                    edit_url: 'zpwxsys/agent/edit',
                    del_url: 'zpwxsys/agent/del',
                    multi_url: 'zpwxsys/agent/multi',
                    table: 'zpwxsys_agent',
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
                        {field: 'id', title: __('id')},
                        {field: 'name', title: __('name')},
                        {field: 'tel', title: __('tel')},
                        {field: 'weixin', title: __('weixin')},
                         {field: 'createtime', title: __('createtime'),formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('status'), formatter: Table.api.formatter.toggle},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});