layui.use(['formSelects', 'layer', 'table'], function () {
    var table = layui.table;
    let form = layui.form;
    let formSelects = layui.formSelects;
    let layer = layui.layer;

    let tableIns = table.render({
        elem: '#demo'
        , height: 'auto'
        , url: '/admin/user/user/index' //数据接口
        // , toolbar: '#toolbarDemo'       //头工具栏
        , page: true //开启分页
        , cols: [[ //表头
            { type: 'checkbox', fixed: 'left' }
            , { field: 'id', title: 'ID', width: 60 }
            , { field: 'username', title: '用户名', width: 120 }
            , { field: 'nickname', title: '昵称', width: 100 }
            , { field: 'mobile', title: '手机号', width: 120 }
            , { field: 'email', title: 'Email', width: 150 }
            , { field: 'card_id', title: '身份证号', width: 200 }
            , {
                field: 'status', title: '状态', width: 80, templet: function (d) {
                    if (d.status == 1) {
                        return '<span class="layui-badge-dot layui-bg-green"></span>&nbsp;&nbsp;<span style="color: #5FB878">正常</span>'
                    } else {
                        return '<span class="layui-badge-dot layui-bg-gray"></span>&nbsp;&nbsp;<span style="color: #e2e2e2">隐藏</span>'
                    }
                }
            }
            , {
                field: 'avatar',
                title: '头像',
                unresize: true,
                sort: false,
                width: 120,
                align: 'center',
                templet: function (d) {
                    return `<div class="layer-photos-demo" style="cursor:pointer;">
                            <img layer-pid="图片id，可以不写"  layer-src="/assets/img/photo1.png" 
                              src="/assets/img/photo1.png" alt="图片名">
                          </div>`;
                }
            }
            , {
                fixed: 'right', title: '操作', width: 160, align: 'center',
                templet: function (d) {
                    if (d.status !== -1) {
                        return `<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="disabled">禁用</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>`
                    } else {
                        return `<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>`;
                    }
                }
            }]]
        , done: function () {
            layer.photos({
                photos: '.layer-photos-demo'
                , anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            });
        }
    });


    // 监听工具条
    table.on('tool(test)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        let data = obj.data; //获得当前行数据
        let layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        let tr = obj.tr;   //获得当前行tr 的DOM对象

        if (layEvent === 'disabled') { //禁用
            layer.confirm('真的禁用行么', function (index) {
                layer.close(index)
                $.ajax({
                    url: '/admin/user/user/disabled?id=' + data.id,
                    type: 'get',
                    success: function (ret) {
                        tableIns.reload();
                    }
                })
            })
        } else if (layEvent === 'del') { //删除
            layer.confirm('真的删除行么', function (index) {
                // obj.del(); //删除对应行(tr) 的DOM结构，并更新缓存
                layer.close(index);
                // 向服务器发送删除指令
                $.ajax({
                    url: '/admin/user/user/del?id=' + data.id,
                    type: 'get',
                    success: function (ret) {
                        // 刷新表格
                        tableIns.reload();
                    }
                })
            })
        }
    })
});


