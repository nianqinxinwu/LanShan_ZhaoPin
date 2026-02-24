define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/user/index' + location.search,
                    add_url: 'zpwxsys/user/add',
                    edit_url: 'zpwxsys/user/edit',
                    del_url: 'zpwxsys/user/del',
                    multi_url: 'zpwxsys/user/multi',
                    table: 'zpwxsys_user',
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
                        {field: 'avatarUrl', title: __('avatarUrl'), formatter:Table.api.formatter.image, operate:false},
                        {field: 'nickname', title: __('nickname')},

                        {field: 'tel', title: __('tel')},
                        {field: 'createtime', title: __('createtime'), operate:false},
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