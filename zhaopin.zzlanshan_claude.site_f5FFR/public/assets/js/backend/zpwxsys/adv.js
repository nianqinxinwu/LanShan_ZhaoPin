define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/adv/index' + location.search,
                    add_url: 'zpwxsys/adv/add',
                    edit_url: 'zpwxsys/adv/edit',
                    del_url: 'zpwxsys/adv/del',
                    multi_url: 'zpwxsys/adv/multi',
                    table: 'zpwxsys_adv',
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
                        {field: 'thumb', title: __('thumb'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'cityname', title: __('cityname')},
                        {field: 'advname', title: __('advname')},
                        {field: 'link', title: __('link')},
                        {
                            field: 'sort',
                            title: __('sort'),
                            formatter: function (value, row, index) {
                                return '<input type="number" class="form-control text-center text-sort" data-id="' + row.id + '" value="' + value + '" style="width:100px;margin:0 auto;" />';
                            },
                            events: {
                                "dblclick .text-sort": function (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return false;
                                }
                            }
                        },
                        {field: 'enabled', title: __('status'), formatter: Table.api.formatter.toggle},
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






