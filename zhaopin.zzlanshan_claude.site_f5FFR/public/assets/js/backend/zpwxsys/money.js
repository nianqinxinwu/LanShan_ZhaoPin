define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/money/index' + location.search,
                    del_url: 'zpwxsys/money/del',
                    multi_url: 'zpwxsys/money/multi',
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
                        {field: 'nickname', title: __('nickname')},
                        {field: 'money', title: __('money')},
                        {field: 'totalmoney', title: __('totalmoney')},
                        {field: 'orderinfo', title: __('orderinfo')},
                        {field: 'paystatus', title: __('paystatus')},
                        {field: 'createtime', title: __('createtime')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                        
                            buttons: [
                                {
                                    name: 'addsub',
                                    text: '打款设置',
                                    classname: 'btn btn-info btn-xs btn-dialog',
                                    icon: 'fa fa-plus',
                                    url: 'zpwxsys/money/setmoney/id/{ids}'
                                }
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
         setmoney: function () {
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