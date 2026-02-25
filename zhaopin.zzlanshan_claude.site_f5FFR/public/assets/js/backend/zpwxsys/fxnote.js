define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/fxnote/index' + location.search,
                    del_url: 'zpwxsys/fxnote/del',
                    multi_url: 'zpwxsys/fxnote/multi',
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
                        {field: 'cityname', title: __('cityname')},
                        {field: 'name', title: __('name')},
                        {field: 'sex', title: __('sex')},
                        {field: 'tel', title: __('tel')},
                        {field: 'education', title: __('education')},
                        {field: 'jobtitle', title: __('jobtitle')},
                        {field: 'birthday', title: __('birthday')},
                        {field: 'express', title: __('express')},
                        {field: 'address', title: __('address')},
                        {field: 'createtime', title: __('createtime'),formatter: Table.api.formatter.datetime},
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