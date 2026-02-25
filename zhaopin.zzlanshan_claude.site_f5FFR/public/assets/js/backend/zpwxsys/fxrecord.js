define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/fxrecord/index' + location.search,
                    del_url: 'zpwxsys/fxrecord/del',
                    multi_url: 'zpwxsys/fxrecord/multi',
                    table: 'zpwxsys_money',
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
                        {field: 'agentname', title: __('agentname')},
                        {field: 'money', title: __('money')},
                        {field: 'orderinfo', title: __('orderinfo')},
                        {field: 'ordertype', width: '', title: __('ordertype'), align: 'center'},
                        {field: 'status', width: '', title: __('status'), align: 'center'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
    
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});