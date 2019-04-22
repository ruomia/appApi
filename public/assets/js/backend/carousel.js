layui.use(['formSelects', 'layer', 'table', 'upload'], function () {
    var table = layui.table;
    let form = layui.form;
    let layer = layui.layer;
    let upload = layui.upload;
    let uploadInst;
    let tableIns = table.render({
        elem: '#demo'
        , height: 'auto'
        , url: '/admin/carousel/index' //数据接口
        , toolbar: '#toolbarDemo'       //头工具栏
        , page: true //开启分页
        , cols: [[ //表头
            { type: 'checkbox', fixed: 'left' }
            , { field: 'id', title: 'ID', width: 60 }
            , {
                field: 'url',
                title: '图片',
                unresize: true,
                sort: false,
                // width: 120,
                align: 'center',
                templet: function (d) {
                    return `<div class="layer-photos-demo" style="cursor:pointer;">
                            <img layer-pid="图片id，可以不写"  layer-src="${d.image}" 
                              src="${d.image}" alt="图片名">
                          </div>`;
                }
            }
            , { field: 'weigh', title: '权重', width: 120 }
            , {
                field: 'status', title: '状态', width: 80, templet: function (d) {
                    if (d.status == 1) {
                        return '<span class="layui-badge layui-bg-orange">正常</span>'
                    } else {
                        return '<span class="layui-badge layui-bg-gray">隐藏</span>'
                    }
                }
            }
            , { field: 'create_time', title: '创建时间', width: 120 }
            , {
                fixed: 'right', title: '操作', width: 160, align: 'center',
                templet: function (d) {
                    return `<a class="layui-btn layui-btn-xs layui-btn-warning" lay-event="edit">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>`
                }
            }]]
        , done: function () {
            layer.photos({
                photos: '.layer-photos-demo'
                , anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            });
        }
    });

    // 头工具栏事件
    table.on('toolbar(test)', function (obj) {
        let checkStatus = table.checkStatus(obj.config.id);
        switch (obj.event) {
            case 'add':
                add();
                break;
            case 'delete':
                var data = checkStatus.data;
                del(data);
                break;

        }

    })
    // 监听工具条
    table.on('tool(test)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
        let data = obj.data; //获得当前行数据
        let layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        let tr = obj.tr;   //获得当前行tr 的DOM对象

        if (layEvent === 'edit') { 
            edit(data.id)
        } else if (layEvent === 'del') { //删除
            layer.confirm('真的删除行么', function (index) {
                // obj.del(); //删除对应行(tr) 的DOM结构，并更新缓存
                layer.close(index);
                // 向服务器发送删除指令
                $.ajax({
                    url: '/admin/carousel/del?ids=' + data.id,
                    type: 'get',
                    success: function (ret) {
                        // 刷新表格
                        tableIns.reload();
                    }
                })
            })
        }
    })
    //监听提交
    form.on('submit(*)', function (data) {
        $.ajax({
            url: data.form.action,
            type: 'post',
            data: data.field,
            dataType: 'json',
            success: function (ret) {
                if (ret.code === 1) {
                    layer.msg('操作成功！');
                    layer.close(formIndex)
                    tableIns.reload();
                } else {
                    layer.msg(ret.msg);
                }
            }
        })
        return false;
    });
    function add() {
        $.ajax({
            url: '/admin/carousel/add',
            type: 'get',
            dataType: 'json',
            success: function (res) {
                formIndex = layer.open({
                    type: 1,
                    area: ['60%', '75%'],
                    content: res,
                    success: function (res) {
                        form.render();
                        uploadInst = upload.render({
                            elem: '#uploadImage'
                            , url: '/admin/index/upload/'
                            , before: function (obj) {
                                //预读本地文件示例，不支持ie8
                                obj.preview(function (index, file, result) {
                                    $('#demo1').attr('src', result); //图片链接（base64）
                                });
                            }
                            , done: function (res) {
                                //如果上传失败
                                if (res.code > 0) {
                                    return layer.msg(res.msg);
                                }
                                //上传成功
                                $("input[name=image]").val(res.data.src);
                            }
                            , error: function () {
                                //演示失败状态，并实现重传
                                var demoText = $('#demoText');
                                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                                demoText.find('.demo-reload').on('click', function () {
                                    uploadInst.upload();
                                });
                            }
                        });
                    }
                })
            }
        })
    }
    function edit(id) {
        $.ajax({
            url: '/admin/carousel/edit?id=' + id,
            type: 'get',
            dataType: 'json',
            success: function (res) {
                formIndex = layer.open({
                    type: 1,
                    area: ['60%', '75%'],
                    content: res,
                    success: function (res) {
                        form.render();
                        uploadInst = upload.render({
                            elem: '#uploadImage'
                            , url: '/admin/index/upload/'
                            , before: function (obj) {
                                //预读本地文件示例，不支持ie8
                                obj.preview(function (index, file, result) {
                                    $('#demo1').attr('src', result); //图片链接（base64）
                                });
                            }
                            , done: function (res) {
                                //如果上传失败
                                if (res.code > 0) {
                                    return layer.msg(res.msg);
                                }
                                //上传成功
                                $("input[name=image]").val(res.data.src);
                            }
                            , error: function () {
                                //演示失败状态，并实现重传
                                var demoText = $('#demoText');
                                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                                demoText.find('.demo-reload').on('click', function () {
                                    uploadInst.upload();
                                });
                            }
                        });
                    }
                })
            }
        })
    }
});


