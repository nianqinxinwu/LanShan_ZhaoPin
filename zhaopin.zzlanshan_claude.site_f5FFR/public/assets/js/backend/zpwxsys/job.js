define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zpwxsys/job/index' + location.search,
                    add_url: 'zpwxsys/job/add',
                    edit_url: 'zpwxsys/job/edit',
                    del_url: 'zpwxsys/job/del',
                    multi_url: 'zpwxsys/job/multi',
                    table: 'zpwxsys_job',
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
                        {field: 'companyname', title: __('companyid')},
                        {field: 'worktypename', title: __('type'), operate:false},
                        {field: 'jobtitle', title: __('jobtitle')},
                        {field: 'jobcatename', title: __('jobcatename'), operate:false},
                        {field: 'sex', title: __('sex'), operate:false},
                        {field: 'num', title: __('num'), operate:false},
                        {field: 'money', title: __('money'), operate:false},
                        {field: 'createtime', title: __('createtime'),formatter: Table.api.formatter.datetime},

                        {
                            field: 'sort',
                            title: __('sort'),
                            operate:false,
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
                        {field: 'ischeck', title: __('ischeck'), operate:false},
                        {field: 'status', title: __('status'), formatter: Table.api.formatter.toggle, operate:false},
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


            $(document).on("change", "#selectCity", function (a) {

                var cityid = $("#selectCity").val();
                // alert(cityid);
                Fast.api.ajax({
                    url: "zpwxsys/area/getarealist",
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