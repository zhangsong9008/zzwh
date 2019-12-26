;(function ($) {
    var active = {};
    $.fn.superTable = function (options) {
        var config = $.extend(true, {
            method: "get",  //使用get请求到服务器获取数据
            url: "", //获取数据的地址
            striped: true,  //表格显示条纹
            pagination: true, //启动分页
            pageSize: 20,  //每页显示的记录数
            pageNumber: 1, //当前第几页
            pageList: [5, 10, 15, 20, 25],  //记录数可选列表
            sidePagination: "server", //表示服务端请求
            paginationFirstText: "首页",
            paginationPreText: "上一页",
            paginationNextText: "下一页",
            searchClass:"search-key",
            paginationLastText: "尾页",
            queryParamsType: "undefined",
            pk:'id',
            queryParams: function queryParams(params) {   //设置查询参数
                var param = {};
                var searchParam = makeSearch($("." + config.searchClass));
                param.page = params.pageNumber;
                param.limit = params.pageSize;
                param.searchParam = searchParam;
                if(this.sortName){
                    param.sidx = this.sortName;
                }
                if(this.sortOrder){
                    param.sord = this.sortOrder;
                }

                return param;
            },
            onLoadSuccess: function (res) {  //加载成功时执行
                if (1 == res.code) {
                   // window.location.reload();
                }
                layer.msg("加载成功", {time: 1000});
            },
            onLoadError: function () {  //加载失败时执行
                layer.msg("加载数据失败");
            }
        }, options);
        var me = this;
        function init(me,config){
            $(me).bootstrapTable(config);
        }
        init(me,config);
        active = {
            //页面重载，清空搜索框，重置页码为1,重置limit为最小值,清除排序
            reload: function () {
                $("form")[0].reset();
                var reloadParam = makeSearch($("." + config.searchClass));
                config.queryParams.searchParam = reloadParam;
                $(me).bootstrapTable('refresh',reloadParam);
            },
            //搜索，根据当前搜索框内容,重置页码为1
            search: function () {
                var reloadParam = makeSearch($("." + config.searchClass));
                config.queryParams.searchParam = reloadParam;
                $(me).bootstrapTable('refresh',reloadParam);
            }
        };
    }


    //触发重置/搜索等内置操作
    $('button').on('click', function () {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });

    function evalAction(layEvent, data) {
        var func = layEvent + 'Action';
        eval(func + "(data)");
    }

    function makeSearch(obj) {
        var param = {};
        $.each(obj, function (index, value) {
            var THIS = $(this),
                search_key = $(this).attr('name'),
                search_value = $(this).val(),
                data_type = THIS.attr('data-type') || '';
            var op = $(this).data('op');
            op = (op !== undefined) ? op : 'eq';
            var alias = $(this).data('alias');
            alias = (alias !== undefined) ? alias : '';
            if (search_value !== undefined && search_value !== null) {
                search_value = search_value.trim();
                if (search_value !== '') {
                    if (data_type == 'timePicker') {
                        var dateArr = search_value.split(',');
                        dateArr[0] = new Date(Date.parse(dateArr[0].replace(/-/g, "/"))).getTime() / 1000;
                        dateArr[1] = new Date(Date.parse(dateArr[1].replace(/-/g, "/"))).getTime() / 1000 + 86399;
                        search_value = dateArr.join(',');
                    }
                    if (search_key == 'search_value') {
                        if ($("select[name='data-op']").val() !== undefined) {
                            op = $("select[name='data-op']").val();
                        }
                    }
                    param[search_key] = {
                        value: search_value.trim(),
                        op: op.trim(),
                        alias: alias.trim()
                    };
                }
            }
        });
        if (param.search_value == undefined) delete param.search_key;
        return param;
    }

}(jQuery));